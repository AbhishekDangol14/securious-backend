<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Threat;
use Illuminate\Http\Request;
use App\Services\Customer\ThreatService;
use App\Http\Resources\GetCustomerThreatResource;
use App\Http\Resources\GetCustomerNeutralizedThreatResource;
use App\Http\Resources\GetCustomerReAnalyzedThreatResource;
use App\Http\Resources\ShowCustomerThreatResource;

class ThreatController extends Controller
{
    public function __construct(ThreatService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $threats = new GetCustomerThreatResource($this->service->getThreats());

        $neutralizedThreats = new GetCustomerNeutralizedThreatResource($this->service->getNeutralizedThreats());

        $toReAnalyzedThreats = new GetCustomerReAnalyzedThreatResource($this->service->getReAnalyzedThreats());

        return $this->success([
            'threats' => $threats,
            'neutralizedThreats' => $neutralizedThreats,
            'toReAnalyzedThreats' => $toReAnalyzedThreats
        ],'Threats fetched successfully');


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $threat = new ShowCustomerThreatResource($this->service->getShowThreat($id));
        $nextThreat = $this->service->getNextThreat($id);

        return $threat;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
