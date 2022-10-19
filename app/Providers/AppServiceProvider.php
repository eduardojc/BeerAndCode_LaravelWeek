<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // METODO PARA ACESSAR A URL DA API
        // PARA PASSAR O TOKEN PODE SER USANDO
        // Http::withHeaders(['accept' => 'application/json'])->withToken('token','tipo ex:Bearer, token')->baseUrl('url da api');
        // retry tentar 3 vezes em 100 milisegundos.

        Http::macro('punkapi', function() {
            return Http::acceptJson()
                ->baseUrl(config('punkapi.url'))
                ->retry(3,100);
        });
    }
}
