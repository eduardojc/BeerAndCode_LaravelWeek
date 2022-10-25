<?php

namespace App\Http\Controllers;

use App\Exports\BeerExport;
use App\Http\Requests\BeerRequest;
use App\Jobs\ExportJob;
use App\Jobs\SendExportEmailJob;
use App\Jobs\StoreExportDataJob;
use App\Mail\ExportEmail;
use App\Services\PunkapiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Export;
use App\Models\Meal;
use Inertia\Inertia;



class BeerController extends Controller
{
    // PunkapiSerice a classe é injetada (injeção de dependencia);
    public function index(BeerRequest $request,PunkapiService $service)
    {
        /*
            atribui o array associativo conforme a sequencia enviada pelo request,
            sem sem importar com a sequencia requisitada pela classe;
        */
        $filters = $request->validated();
        $beers = $service->getBeers(...$filters);
        $meals = Meal::all();

        return Inertia::render('Beers',[
                'beers' => $beers,
                'meals' => $meals,
                'filters' => $filters
            ]);
    }

    public function export(BeerRequest $request,PunkapiService $service)
    {
        $filename = "cervejas-encontradas-".now()->format("Y-m-d H_i").".xlsx";
      
        // FORMA ASSINCRONA
        ExportJob::withChain([
            new SendExportEmailJob($filename),
            new StoreExportDataJob(Auth::user(), $filename)
        ])->dispatch($request->validated(),$filename);
      
        return redirect()->back()
            ->with('success', 'Seu Arquivo foi enviado para processamento e em breve estará em seu email');
    }
}
