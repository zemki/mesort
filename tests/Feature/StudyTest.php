<?php

namespace Tests\Feature;

use App\Sorting;
use App\Study;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudyTest extends TestCase
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

    public function test_authenticated_user_can_view_own_study()
    {
        $user = User::factory()->create();
        $study = Study::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/studies/{$study->id}");

        $response->assertOk();
        $response->assertViewIs('study.show');
        $response->assertViewHas('study', $study);
    }

    public function test_user_cannot_view_other_users_study()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $study = Study::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($other)->get("/studies/{$study->id}");

        $response->assertForbidden();
    }

    public function test_user_can_create_study()
    {
        $user = User::factory()->create();

        $studyData = [
            'name' => 'Test Study',
            'display_name' => 'Test Display Name',
            'author' => 'Test Author',
            'description' => 'Test Description',
            'sorting' => [
                'id' => 1,
                'numberofcircles' => 5,
                'description' => 'Test sorting',
                'tokens' => []
            ],
            'presort' => [
                'questions' => [
                    [
                        'question' => 'Presort question 1',
                        'ismultiple' => false,
                        'isonechoice' => false,
                        'isopen' => true,
                        'isscale' => false,
                        'answers' => []
                    ]
                ]
            ],
            'postsort' => [
                'questions' => [
                    [
                        'question' => 'Postsort question 1',
                        'ismultiple' => false,
                        'isonechoice' => false,
                        'isopen' => true,
                        'isscale' => false,
                        'answers' => []
                    ]
                ]
            ]
        ];

        $response = $this->actingAs($user)->postJson('/studies', $studyData);

        $response->assertOk();
        $this->assertDatabaseHas('studies', [
            'name' => 'Test Study',
            'user_id' => $user->id
        ]);
    }

    public function test_user_can_delete_own_study()
    {
        $user = User::factory()->create();
        $study = Study::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/studies/{$study->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('studies', ['id' => $study->id]);
    }

    public function test_user_cannot_delete_other_users_study()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $study = Study::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($other)->delete("/studies/{$study->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('studies', ['id' => $study->id]);
    }

    public function test_user_can_duplicate_study()
    {
        $user = User::factory()->create();
        $study = Study::factory()->create(['user_id' => $user->id, 'name' => 'Original Study']);

        $response = $this->actingAs($user)->get("/studies/{$study->id}/duplicate");

        $response->assertOk();
        $this->assertDatabaseHas('studies', [
            'name' => 'Original Study duplicate',
            'user_id' => $user->id
        ]);
    }

    public function test_guest_cannot_access_study()
    {
        $study = Study::factory()->create();

        $response = $this->get("/studies/{$study->id}");

        $response->assertRedirect('/login');
    }

    public function test_user_can_access_create_study_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/studies/new');

        $response->assertOk();
        $response->assertViewIs('study.create_edit');
    }

    public function test_user_can_edit_study_without_interviews()
    {
        $user = User::factory()->create();
        $study = Study::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/studies/{$study->id}/edit");

        $response->assertOk();
        $response->assertViewIs('study.create_edit');
    }
}
