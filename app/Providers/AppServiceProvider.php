<?php

namespace App\Providers;

use App\Models\Assignment;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Issue;
use App\Models\Medium;
use App\Models\OdometerEntries;
use App\Models\Reminder;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Vendor;
use App\Observers\AssignmentObserver;
use App\Observers\CommentObserver;
use App\Observers\CompanyObserver;
use App\Observers\ContactObserver;
use App\Observers\GroupObserver;
use App\Observers\IssueObserver;
use App\Observers\MediaObserver;
use App\Observers\OdometerEntryObserver;
use App\Observers\ReminderObserver;
use App\Observers\ServiceObserver;
use App\Observers\SubscriptionObserver;
use App\Observers\UserObserver;
use App\Observers\VehicleObserver;
use App\Observers\VendorObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Comment::observe(CommentObserver::class);
        Assignment::observe(AssignmentObserver::class);
        Company::observe(CompanyObserver::class);
        Contact::observe(ContactObserver::class);
        Group::observe(GroupObserver::class);
        Issue::observe(IssueObserver::class);
        Medium::observe(MediaObserver::class);
        Reminder::observe(ReminderObserver::class);
        Service::observe(ServiceObserver::class);
        Subscription::observe(SubscriptionObserver::class);
        User::observe(UserObserver::class);
        Vehicle::observe(VehicleObserver::class);
        Vendor::observe(VendorObserver::class);
        OdometerEntries::observe(OdometerEntryObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        if ($this->app->environment() == 'local') {
            $this->app->register(\Reliese\Coders\CodersServiceProvider::class);
        }

    }
}
