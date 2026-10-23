<?php

namespace Tests\Smoke;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    // Single-row settings tables the app reads as Model::find(1) on every page.
    private const SETTINGS_TABLES = ['layouts', 'maps', 'source_datas'];

    protected function setUp(): void
    {
        parent::setUp();
        foreach (self::SETTINGS_TABLES as $table) {
            $this->insertRow($table, ['id' => 1]);
        }
    }

    /** Insert $values, filling any other NOT NULL column without a default with 0 or ''. */
    private function insertRow(string $table, array $values): void
    {
        foreach (DB::select("PRAGMA table_info($table)") as $col) {
            if ($col->notnull && $col->dflt_value === null && ! array_key_exists($col->name, $values) && ! $col->pk) {
                $values[$col->name] = preg_match('/int|real|num|dec|float|double|bool/i', $col->type) ? 0 : '';
            }
        }
        DB::table($table)->insert($values);
    }

    public function test_home_page()
    {
        $this->get('/')->assertOk();
    }

    public function test_login_page()
    {
        $this->get('/login')->assertOk();
    }

    /** A user whose role grants exactly $permissions (route names, as the app stores them). */
    private function userWith(array $permissions): User
    {
        $this->insertRow('roles', ['name' => 'Smoke', 'permissions' => json_encode($permissions)]);
        $roleId = DB::table('roles')->max('id');

        return User::factory()->create(['role_id' => $roleId]);
    }

    public function test_admin_page_for_a_user_with_the_permission()
    {
        $this->actingAs($this->userWith(['layout_edit.index']))
            ->get('/layout_edit')
            ->assertOk();
    }

    public function test_admin_page_turns_away_a_user_without_the_permission()
    {
        $this->actingAs($this->userWith([]))
            ->get('/layout_edit')
            ->assertRedirect();
    }

    public function test_admin_page_sends_guests_to_login()
    {
        $this->get('/layout_edit')->assertRedirect('/login');
    }

    public function test_log_viewer_sends_guests_to_login()
    {
        // The old arcanedev viewer was public unless its middleware was configured.
        $this->get('/log-viewer')->assertRedirect('/login');
    }

    public function test_log_viewer_is_forbidden_without_the_permission()
    {
        $this->actingAs($this->userWith(['layout_edit.index']))
            ->get('/log-viewer')
            ->assertForbidden();
    }

    public function test_log_viewer_for_a_role_with_the_permission()
    {
        $this->actingAs($this->userWith(['log-viewer::logs.list']))
            ->get('/log-viewer')
            ->assertOk();
    }
}
