<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSolutionPartnerTranslationRequest;
use App\Http\Requests\UpdateSolutionPartnerTranslationRequest;
use App\Http\Resources\GetSolutionPartnerTranslationResource;
use App\Http\Resources\ShowSolutionPartnerTranslationResource;
use App\Models\SolutionPartnerTranslation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SolutionPartnerTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param SolutionPartnerTranslation $translation
     * @return JsonResponse
     */
    public function index(Request $request, SolutionPartnerTranslation $translation): JsonResponse
    {
        if ($request->has('solution_partner_id')) {
            $translation = $translation->where('solution_partner_id', $request->get('solution_partner_id'));
        }
        return $this->success(new GetSolutionPartnerTranslationResource($translation->paginate()), 'Solution Partner Translations Fetched successfully!');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSolutionPartnerTranslationRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSolutionPartnerTranslationRequest $request): JsonResponse
    {
        $solutionPartnerTranslation = new SolutionPartnerTranslation();
        $solutionPartnerTranslation->solution_partner_id = $request->get('solution_partner_id');
        $solutionPartnerTranslation->attribute_name = $request->get('attribute_name');
        $solutionPartnerTranslation->language =  $request->get('language', $request->user()->selected_language);
        $solutionPartnerTranslation->attribute_value = $request->get('attribute_value');
        $solutionPartnerTranslation->save();
        return $this->success(
            SolutionPartnerTranslation::make($solutionPartnerTranslation),
            'Solution Partner Translation created successfully!',
            201
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateSolutionPartnerTranslationRequest $request
     * @param \App\Models\SolutionPartnerTranslation $solutionPartnerTranslation
     * @return JsonResponse
     */
    public function update(UpdateSolutionPartnerTranslationRequest $request, SolutionPartnerTranslation $solutionPartnerTranslation): JsonResponse
    {
        $solutionPartnerTranslation->attribute_value = $request->get('attribute_value');

        $solutionPartnerTranslation->save();

        return $this->success(
            ShowSolutionPartnerTranslationResource::make($solutionPartnerTranslation),
            'Solution Partner Translation updated successfully!'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SolutionPartnerTranslation  $solutionPartnerTranslation
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(SolutionPartnerTranslation $solutionPartnerTranslation): JsonResponse
    {
        $solutionPartnerTranslation->delete();
        return $this->success([], 'Translation deleted successfully.');
    }
}
