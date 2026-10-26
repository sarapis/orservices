<?php

namespace Tests\Smoke\Concerns;

use App\User;
use Illuminate\Support\Facades\DB;

/** Minimal site state for booting pages on a freshly migrated in-memory SQLite database. */
trait SetsUpSite
{
    // Single-row settings tables the app reads as Model::find(1) on every page.
    private const SETTINGS_TABLES = ['layouts', 'maps', 'source_datas'];

    protected function setUpSite(): void
    {
        // MySQL's FIND_IN_SET, which some import duplicate checks use.
        $pdo = DB::connection()->getPdo();
        $findInSet = fn ($needle, $set) => (int) array_search((string) $needle, explode(',', (string) $set), true) + (in_array((string) $needle, explode(',', (string) $set), true) ? 1 : 0);
        method_exists($pdo, 'createFunction')
            ? $pdo->createFunction('FIND_IN_SET', $findInSet, 2)
            : $pdo->sqliteCreateFunction('FIND_IN_SET', $findInSet, 2);

        foreach (self::SETTINGS_TABLES as $table) {
            $this->insertRow($table, ['id' => 1]);
        }
    }

    /** Insert $values, filling any other NOT NULL column without a default with 0 or ''. */
    protected function insertRow(string $table, array $values): void
    {
        foreach (DB::select("PRAGMA table_info($table)") as $col) {
            if ($col->notnull && $col->dflt_value === null && ! array_key_exists($col->name, $values) && ! $col->pk) {
                $values[$col->name] = preg_match('/int|real|num|dec|float|double|bool/i', $col->type) ? 0 : '';
            }
        }
        DB::table($table)->insert($values);
    }

    /** A user whose role grants exactly $permissions (route names, as the app stores them). */
    protected function userWith(array $permissions): User
    {
        $this->insertRow('roles', ['name' => 'Smoke', 'permissions' => json_encode($permissions)]);
        $roleId = DB::table('roles')->max('id');

        return User::factory()->create(['role_id' => $roleId]);
    }
}
