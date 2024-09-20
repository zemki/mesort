<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\DeleteUserCommand
 */
class DeleteUserCommandTest extends TestCase
{
    /**
     * @test
     */
    public function itRunsSuccessfully()
    {

        $this->artisan('user:delete')
            ->assertExitCode(0)
            ->run();

        // TODO: perform additional assertions to ensure the command behaved as expected
    }
}
