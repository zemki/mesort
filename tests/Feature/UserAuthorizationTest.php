<?php

namespace Tests\Feature;

use App\Sorting;
use App\Study;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create default sortings
        Sorting::create(['id' => 1, 'name' => 'Circular Sorting', 'description' => 'Circular sorting method']);
        Sorting::create(['id' => 2, 'name' => 'Section Sorting', 'description' => 'Section sorting method']);
        Sorting::create(['id' => 3, 'name' => 'Q-Sort', 'description' => 'Q-Sort method']);
    }

    public function test_invited_user_can_view_study()
    {
        $owner = User::factory()->create();
        $invited = User::factory()->create();
        $study = Study::factory()->create(['user_id' => $owner->id]);

        $study->invited()->attach($invited->id);

        $response = $this->actingAs($invited)->get("/studies/{$study->id}");

        $response->assertOk();
    }

    public function test_user_can_invite_another_user_to_study()
    {
        $owner = User::factory()->create();
        $toInvite = User::factory()->create();
        $study = Study::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($owner)->postJson('/studies/invite', [
            'study' => $study->id,
            'email' => $toInvite->email
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('user_studies', [
            'study_id' => $study->id,
            'user_id' => $toInvite->id
        ]);
    }

    public function test_user_can_remove_invited_user_from_study()
    {
        $owner = User::factory()->create();
        $invited = User::factory()->create();
        $study = Study::factory()->create(['user_id' => $owner->id]);
        $study->invited()->attach($invited->id);

        $response = $this->actingAs($owner)->postJson("/studies/invite/{$invited->id}", [
            'study' => $study->id,
            'email' => $invited->email
        ]);

        $response->assertOk();
        $this->assertDatabaseMissing('user_studies', [
            'study_id' => $study->id,
            'user_id' => $invited->id
        ]);
    }

    public function test_study_owner_cannot_invite_themselves()
    {
        $owner = User::factory()->create();
        $study = Study::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($owner)->postJson('/studies/invite', [
            'study' => $study->id,
            'email' => $owner->email
        ]);

        $response->assertOk();
        $response->assertJson(['message' => __("You can't invite who created the study.!")]);
    }

    public function test_non_owner_cannot_remove_users_from_study()
    {
        $owner = User::factory()->create();
        $invited = User::factory()->create();
        $other = User::factory()->create();
        $study = Study::factory()->create(['user_id' => $owner->id]);
        $study->invited()->attach($invited->id);

        $response = $this->actingAs($other)->postJson("/studies/invite/{$invited->id}", [
            'study' => $study->id,
            'email' => $invited->email
        ]);

        $response->assertForbidden();
    }

    public function test_guest_users_are_redirected_to_login()
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_home()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
    }
}
