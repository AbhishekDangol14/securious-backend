<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSolutionPartnerProductTranslationRequest;
use App\Http\Requests\UpdateSolutionPartnerProductTranslationRequest;
use App\Http\Resources\GetSolutionPartnerProductTranslationsResource;
use App\Models\SolutionPartnerProductTranslation;

class SolutionPartnerProductTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, SolutionPartnerProductTranslation $solutionPartnerProductTranslation)
    {
        if ($request->has('solution_partner_product_id')) {
            $solutionPartnerProductTranslation = $solutionPartnerProductTranslation->where('solution_partner_product_id', $request->get('solution_partner_product_id'));
        }
        return $this->success(new GetSolutionPartnerProductTranslationsResource($solutionPartnerProductTranslation->paginate()), 'Solution Partner Product Translations Fetched successfully!');
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
     * @param  \App\Http\Requests\StoreSolutionPartnerProductTranslationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSolutionPartnerProductTranslationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SolutionPartnerProductTranslation  $solutionPartnerProductTranslation
     * @return \Illuminate\Http\Response
     */
    public function show(SolutionPartnerProductTranslation $solutionPartnerProductTranslation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SolutionPartnerProductTranslation  $solutionPartnerProductTranslation
     * @return \Illuminate\Http\Response
     */
    public function edit(SolutionPartnerProductTranslation $solutionPartnerProductTranslation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSolutionPartnerProductTranslationRequest  $request
     * @param  \App\Models\SolutionPartnerProductTranslation  $solutionPartnerProductTranslation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSolutionPartnerProductTranslationRequest $request, SolutionPartnerProductTranslation $solutionPartnerProductTranslation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SolutionPartnerProductTranslation  $solutionPartnerProductTranslation
     * @return \Illuminate\Http\Response
     */
    public function destroy(SolutionPartnerProductTranslation $solutionPartnerProductTranslation)
    {
        //
    }
}
