<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginFormTest extends TestCase
{
    public function test_login_form_submits_to_login_endpoint(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('action="'.url('/login').'"', false);
    }
}
