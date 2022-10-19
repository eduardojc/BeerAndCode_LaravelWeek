<?php

namespace App\Http\Controllers;

use App\Exports\BeerExport;
use App\Http\Requests\BeerRequest;
use App\Services\PunkapiService;
use Maatwebsite\Excel\Facades\Excel;

class BeerController extends Controller
{
    // PunkapiSerice a classe é injetada (injeção de dependencia);
    public function index(BeerRequest $request,PunkapiService $service)
    {
        /*
            atribui o array associativo conforme a sequencia enviada pelo request,
            sem sem importar com a sequencia requisitada pela classe;
        */
        return $service->getBeers(...$request->validated());
    }

    public function export(BeerRequest $request,PunkapiService $service)
    {
        $beers =  $service->getBeers(...$request->validated());

        $filteredBeers = collect($beers)->map(function($value,$key) {
            return collect($value)
                ->only(['name','tagline','first_brewed','description']);
        })->toArray();

        // dd($filteredBeers);

        Excel::store(
            new BeerExport($filteredBeers),
            'olw-report.xlsx',
            's3'
        );

        return 'Relatorio Criado';
    }
}
