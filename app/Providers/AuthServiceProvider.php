<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\Comment::class => \App\Policies\CommentPolicy::class,
        \App\Models\Company::class => \App\Policies\CompanyPolicy::class,
        \App\Models\Contact::class => \App\Policies\ContactPolicy::class,
        \App\Models\Group::class => \App\Policies\GroupPolicy::class,
        \App\Models\Issue::class => \App\Policies\IssuePolicy::class,
        \App\Models\VehicleMaker::class => \App\Policies\MakerPolicy::class,
        \App\Models\Medium::class => \App\Policies\MediaPolicy::class,
        \App\Models\VehicleModel::class => \App\Policies\ModelPolicy::class,
        \App\Models\Reminder::class => \App\Policies\ReminderPolicy::class,
        \App\Models\RenewalType::class => \App\Policies\RenewaltypePolicy::class,
        \App\Models\Service::class => \App\Policies\ServicePolicy::class,
        \App\Models\ServiceType::class => \App\Policies\ServicetypePolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Vehicle::class => \App\Policies\VehiclePolicy::class,
        \App\Models\Vendor::class => \App\Policies\VendorPolicy::class,
        \App\Models\Notification::class => \App\Policies\NotificationPolicy::class,
        \App\Models\Country::class => \App\Policies\CountryPolicy::class,
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
