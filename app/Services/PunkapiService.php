<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PunkapiService
{
    public function getBeers(
        ?string $beer_name = null,
        ?string $food = null,
        ?string $malt = null,
        ?int $ibu_gt = null
    )
    {
        // array_filter por padrão retira todas as chaves com valor vazio.
        $params = array_filter(get_defined_vars());

        // configurado no App/Provides/AppServiceProvider através de MACRO.
        // throw lança a exceção para tratar
        return Http::punkapi()
            ->get('/beers', $params)
            ->throw()
            ->json();
    }
}