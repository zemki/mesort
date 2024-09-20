<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\StudyInterviewController
 */
class StudyInterviewControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function indexReturnsAnOkResponse()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $study = factory(\App\Study::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get('studies/{study}/interviews');

        $response->assertOk();
        $response->assertViewIs('interview.index');

        // TODO: perform additional assertions
    }

    // test cases...
}
