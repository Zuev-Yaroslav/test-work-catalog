<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_successfully()
    {
        $user = User::factory()->create();

        $response = $this->post(route('api.v1.auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertOk();
        $responseMe = $this->withToken($response->collect()['token'])->get(route('api.v1.auth.me'));
        $responseMe->assertOk();
        $this->assertEquals($user->id, $responseMe->collect()['id']);
    }
    public function test_login_failed()
    {
        $user = User::factory()->create();

        $response = $this->post(route('api.v1.auth.login'), [
            'email' => $user->email,
            'password' => 'pass',
        ]);

        $response->assertUnauthorized();
    }
    public function test_invalid_validation()
    {
        User::factory()->create();

        $response = $this->post(route('api.v1.auth.login'), [
            'email' => 'kkdioad',
            'password' => '',
        ]);
        $response->assertUnprocessable();
    }
    public function test_logout()
    {
        $user = User::factory()->create();
        $response = $this
            ->post(route('api.v1.auth.logout'));
        $response->assertUnauthorized();

        $token = $user->createToken('token')->plainTextToken;
        $response = $this
            ->withToken($token)
            ->post(route('api.v1.auth.logout'));
        $response->assertNoContent();

    }
    public function test_cannot_logout_twice_with_same_token()
    {
        $user = User::factory()->create();

        $token = $user->createToken('token')->plainTextToken;
        $response = $this
            ->withToken($token)
            ->post(route('api.v1.auth.logout'));
        $response->assertNoContent();
        app('auth')->guard('sanctum')->forgetUser();

        $response = $this
            ->withToken($token)
            ->post(route('api.v1.auth.logout'));
        $response->assertUnauthorized();
    }
}
