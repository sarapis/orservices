<?php

namespace Tests\Smoke;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Smoke\Concerns\SetsUpSite;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase, SetsUpSite;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpSite();
    }

    public function test_home_page()
    {
        $this->get('/')->assertOk();
    }

    public function test_login_page()
    {
        $this->get('/login')->assertOk();
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
