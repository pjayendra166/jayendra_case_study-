<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {

        $userData = [
            "email" => "pjayendra166@gmail.com",
            "password" => "password"
        ];

        $response = $this->json('POST', '/api/auth/login', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'token'
        ]);
        dd($response);
    }
}
