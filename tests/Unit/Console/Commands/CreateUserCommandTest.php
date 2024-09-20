<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\CreateUserCommand
 */
class CreateUserCommandTest extends TestCase
{
    /**
     * @test
     */
    public function itRunsSuccessfully()
    {

        $this->artisan('user:create')
            ->assertExitCode(0)
            ->run();

        // TODO: perform additional assertions to ensure the command behaved as expected
    }
}
