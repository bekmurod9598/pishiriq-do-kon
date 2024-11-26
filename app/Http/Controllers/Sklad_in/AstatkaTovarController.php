<?php

namespace App\Http\Controllers\Sklad_in;

use App\Models\Sklad\Brand;
use App\Models\Unik;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sklad\StoreBrandRequest;
use App\Services\FunctionsServices;


class AstatkaTovarController extends Controller
{
    protected $functionsServices;

    // Dependency Injection yordamida FunctionsService-ni controllerga o'tkazish
    public function __construct(FunctionsServices $functionsServices)
    {
        $this->functionsServices = $functionsServices;
    }
    
    public function index()
    {
        // Barcha astatkada mavjud tovarlar servisdagi funksiya orqali olinyapti
        $items = $this->functionsServices->getAstatkaProducts();
        // dd($items);
        return view('sklad_in/astatka_tovar', compact('items'));
    }

    public function create()
    {
        //
    }

    public function show(Brand $brand)
    {
        //
    }

    public function edit(Brand $brand)
    {
        //
    }


    public function destroy(Request $request, Brand $brand) {}
}
