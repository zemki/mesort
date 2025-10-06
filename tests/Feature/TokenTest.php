<?php

namespace Tests\Feature;

use App\Study;
use App\Token;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_tokens_are_associated_with_study()
    {
        $user = User::factory()->create();
        $study = Study::factory()->create(['user_id' => $user->id]);
        $token = Token::factory()->create();
        $study->tokens()->attach($token->id);

        $this->assertDatabaseHas('study_tokens', [
            'token_id' => $token->id,
            'study_id' => $study->id
        ]);
    }

    public function test_study_can_have_multiple_tokens()
    {
        $user = User::factory()->create();
        $study = Study::factory()->create(['user_id' => $user->id]);
        $tokens = Token::factory()->count(5)->create();
        $study->tokens()->attach($tokens->pluck('id'));

        $this->assertEquals(5, $study->tokens()->count());
    }

    public function test_deleting_study_removes_tokens()
    {
        $user = User::factory()->create();
        $study = Study::factory()->create(['user_id' => $user->id]);
        $token = Token::factory()->create();
        $study->tokens()->attach($token->id);

        $study->delete();

        $this->assertDatabaseMissing('tokens', ['id' => $token->id]);
    }

    public function test_available_tokens_excludes_author_zero()
    {
        $user = User::factory()->create();
        $study = Study::factory()->create(['user_id' => $user->id]);
        $token1 = Token::factory()->create(['author' => 0]);
        $token2 = Token::factory()->create(['author' => 1]);
        $study->tokens()->attach([$token1->id, $token2->id]);

        $availableTokens = $study->available_tokens;

        $this->assertEquals(1, $availableTokens->count());
    }
}
