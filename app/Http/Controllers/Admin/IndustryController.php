<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIndustryRequest;
use App\Http\Resources\GetIndustriesResource;
use App\Http\Resources\ShowIndustryResource;
use App\Models\Industry;
use App\Services\IndustryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
    public function __construct(IndustryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $pages = $request->get('pages') ?? 8;
        return $this->success(new GetIndustriesResource($this->service->paginate($pages, ['id', 'details_level', 'is_active'],['translations'],[],'created_at')), 'Industries fetched successfully!');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(StoreIndustryRequest $request): JsonResponse{

        /** @var Industry $industry */
        $industry = $this->service->autoStore($request);

        $this->service->insertTranslations($request->get('friendlyTranslations'), $industry);

        return $this->success(
            (array)ShowIndustryResource::make($industry),
            'Industry created successfully!',
            201
        );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateIndustryRequest $request
     * @param \App\Models\Industry $industry
     * @return JsonResponse
     */
    public function update(Request $request, Industry $industry): JsonResponse
    {
       $updatedIndustry = $this->service->autoUpdate($request, $industry);
       $this->service->updateTranslations($request->get('friendlyTranslations'));

        return $this->success(
            (array)ShowIndustryResource::make($updatedIndustry), 'Industry updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Industry $industry
     * @return JsonResponse
     */
    public function destroy(Industry $industry): JsonResponse
    {
        $this->service->delete($industry);

        return $this->success('Industry deleted successfully!');
    }

    public function toggleStatus(Request $request) { 
        $this->service->toggleStatus($request);

        return $this->success('Status Updated successfully');        
    }
}
