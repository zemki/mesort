<?php

namespace Tests\Feature;

use App\User;
use Database\Factories\UserFactory;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class TestRateLimiter extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        // Load the UserFactory
        User::factory()->create();
    }

    /**
     * Test the RateLimiter.
     *
     * @return void
     */
    public function testRateLimiter()
    {
        $user = User::factory()->create();

        // Simulate 100 requests from the user within a minute.
        for ($i = 0; $i < 100; $i++) {
            $response = $this->actingAs($user)
                ->get('/');
        }

        // Assert that the RateLimiter blocks further requests from the user.
        $limiter = RateLimiter::limiter('global', $user->id);
        $request = new Request();
        $request->headers->set('X-Forwarded-For', '192.0.2.1');
        $request->server->set('REQUEST_METHOD', 'GET');
        $request->server->set('REQUEST_URI', '/example');
        $key = $request->fingerprint();
        $this->assertTrue(
            Cache::store('file')->tooManyAttempts(
                $key,
                $limiter->maxAttempts,
                $limiter->decayMinutes
            )
        );

        // Wait for a minute to reset the limit.
        sleep(60);

        // Make another request from the user and assert that it succeeds.
        $response = $this->actingAs($user)
            ->get('/example');

        $this->assertNotEquals(429, $response->getStatusCode());
    }
}
