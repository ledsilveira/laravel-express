<?php

namespace CodeProject\Providers;

use Illuminate\Support\ServiceProvider;

class CodeProjectRepositoryProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //Carrega o eloquent a partir da interface, aqui pode mudar para Doctrine ou qualquer outro
        $this->app->bind(
            \CodeProject\Repositories\ClientRepository::class,
            \CodeProject\Repositories\ClientRepositoryEloquent::class);
        $this->app->bind(
            \CodeProject\Repositories\ProjectRepository::class,
            \CodeProject\Repositories\ProjectRepositoryEloquent::class);
    }
}
