<?php

namespace Modules\DeliveryOptions\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\DeliveryOptions\Entities\DeliveryOption;

class DeliveryOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $shippingOptionCodes = ['lalamove', 'shiprocket', 'dunzo', 'ahoy', 'shippo', 'kwikapi', 'roadie','shipengine','borzo','d4b_dunzo'];
        $shippingOptions = DeliveryOption::whereIn('code', $shippingOptionCodes)->get()->keyBy('code');

        $d4b_dunzo = DeliveryOption::where('code', 'd4b_dunzo')->first();

        return view('deliveryoptions::index')->with([
            'delOption' => $shippingOptions->get('lalamove'),
            'opt' => $shippingOptions->get('shiprocket'),
            'optDunzo' => $shippingOptions->get('dunzo'),
            'optAhoy' => $shippingOptions->get('ahoy'),
            'shippoOption' => $shippingOptions->get('shippo'),
            'kwikOption' => $shippingOptions->get('kwikapi'),
            'roadieOption' => $shippingOptions->get('roadie'),
            'shipEngineOption' => $shippingOptions->get('shipengine'),
            'd4b_dunzo'=>$d4b_dunzo,
            'borzoOption' => $shippingOptions->get('borzo'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('deliveryoptions::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('deliveryoptions::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('deliveryoptions::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
