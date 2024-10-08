<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ApiController
 */
class ApiControllerTest extends TestCase
{
    /**
     * @test
     */
    public function deleteTokenReturnsAnOkResponse()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->post('api/v1/deletetoken', [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function getTokensReturnsAnOkResponse()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->post('api/v1/gettokens', [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function getpresettokenimagesReturnsAnOkResponse()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->post('api/v1/getpresettokenimages', [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function savetokenReturnsAnOkResponse()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->post('api/v1/savetoken', [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    // test cases...
}
