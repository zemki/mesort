<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;

/**
 * @see \App\Console\Commands\DeleteClosedStudies
 */
class DeleteClosedStudiesTest extends TestCase
{
    /**
     * @test
     */
    public function itRunsSuccessfully()
    {

        $this->artisan('studies:delete')
            ->assertExitCode(0)
            ->run();

        // TODO: perform additional assertions to ensure the command behaved as expected
    }
}
