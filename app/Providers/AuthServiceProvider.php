<?php

namespace App\Providers;

use App\Interview;
use App\Policies\InterviewPolicy;
use App\Policies\SortingPolicy;
use App\Policies\StudyPolicy;
use App\Policies\UserPolicy;
use App\Sorting;
use App\Study;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Study::class => StudyPolicy::class,
        Interview::class => InterviewPolicy::class,
        Sorting::class => SortingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
