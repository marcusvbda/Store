<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        //checa se tem migrations
        if(Schema::hasTable('migrations'))
        {
            $this->registerPolicies();
            $permissoes = \App\Permissoes::get();
            foreach ($permissoes as $permissao) 
            {
                Gate::define($permissao->nome, function(\App\User $user) use ($permissao)
                {                
                    return $user->hasPermission($permissao);
                });
            }
        }
    }
}
