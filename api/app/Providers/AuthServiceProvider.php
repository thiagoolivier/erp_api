<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        $permissions = Permission::select('id', 'name')->with('roles')->get();

        foreach ($permissions as $permission) {
            Gate::define($permission->name, function(User $user) use ($permission) {
                return $permission->roles->intersect($user->roles)->isNotEmpty() || $user->isAdmin();
            });
        }
    }
}
