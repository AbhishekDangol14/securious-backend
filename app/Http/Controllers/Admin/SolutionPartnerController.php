<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSolutionPartnerRequest;
use App\Http\Requests\UpdateSolutionPartnerRequest;
use App\Http\Resources\GetSolutionPartnerProductsResource;
use App\Http\Resources\GetSolutionPartnersResource;
use App\Http\Resources\ShowSolutionPartnerResource;
use App\Http\Resources\EditSolutionPartner;
use App\Http\Resources\ShowDropDownResource;
use App\Models\SolutionPartner;
use App\Models\SolutionPartnerProduct;
use App\Models\SolutionPartnerTranslation;
use App\Models\AssetAlert;
use App\Services\SolutionPartnerProductService;
use App\Services\SolutionPartnerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Plank\Mediable\Facades\MediaUploader;

class SolutionPartnerController extends Controller
{
    public function __construct(SolutionPartnerService $service, SolutionPartnerProductService $solutionPartnerProductService)
    {
        $this->service = $service;
        $this->solutionPartnerProductService = $solutionPartnerProductService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $per_page = $request->get('per_page') ?? 12;
        return $this->success(new GetSolutionPartnersResource($this->service->paginate($per_page, ['id', 'is_active', 'status'], ['solutionPartnerProducts.translations','translations','media']  )), 'Solution Partner fetched successfully!');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreSolutionPartnerRequest $request
     * @return \Illuminate\Http\Response
     * @throws \JsonException
     */
    public function store(StoreSolutionPartnerRequest $request): JsonResponse
    {
        $solutionPartner = $this->service->autoStore($request);
        $this->service->insertTranslations($request->get('friendlyTranslations'), $solutionPartner);

        if($request->has('solution_partner_products'))
            $this->service->insertSolutionPartnerProduct($request->get('solution_partner_products'),$solutionPartner);

        // $newRequest = new \Illuminate\Http\Request();
        // $newRequest->setMethod('POST');
        // $newRequest->request->add($request->get('solution_partner_products'));

        // $solutionPartnerProduct = $this->solutionPartnerProductService->autoStore($newRequest);
        // $this->solutionPartnerProductService->insertTranslations($newRequest->get('friendlyTranslations'), $solutionPartnerProduct);

        return $this->success(
            (array)ShowSolutionPartnerResource::make($solutionPartner),
            'Solution Partner created successfully!',
            201
        );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateSolutionPartnerRequest $request
     * @param \App\Models\SolutionPartner $solutionPartner
     * @return JsonResponse
     */
    public function update(UpdateSolutionPartnerRequest $request, SolutionPartner $solutionPartner): JsonResponse
    {
        $updatedSolutionPartner = $this->service->autoUpdate($request, $solutionPartner);

        $this->service->updateTranslations($request->get('friendlyTranslations'));

        if($request->has('solutionPartnersProducts'))
            $this->service->updateSolutionPartnerProduct($request->get('solutionPartnersProducts'),$solutionPartner);

        return $this->success(
            (array)ShowSolutionPartnerResource::make($updatedSolutionPartner), 'Solution Partner updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SolutionPartner  $solutionPartner
     * @return JsonResponse
     */
    public function destroy(SolutionPartner $solutionPartner)
    {
        $this->service->delete($solutionPartner);
        return $this->success('Solution Partner deleted successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $solutionPartner = SolutionPartner::with('translations','media','solutionPartnerProducts','solutionPartnerProducts.media','solutionPartnerProducts.translations','solutionPartnerProducts.industryRelation','solutionPartnerProducts.companySize','solutionPartnerProducts.assetAlert')->find($id);
        $industry = $this->service->showIndustry();
        return $this->success(EditSolutionPartner::make($solutionPartner),'Solution Partner Fetched',200);
    }

    public function create()
    {
        $industries = new ShowDropDownResource($this->service->showIndustry());
        return $this->success($industries,'DropDown fetched successfully');

    }

    public function toggleStatus(Request $request) {
        $this->service->toggleStatus($request);
        return $this->success('Status Updated successfully');        
    }

    public function deleteImage($id,SolutionPartner $solutionPartner){
        $this->service->deleteImage($id,$solutionPartner);
    }

    public function deleteAssetAlert($id) 
    {
        $assetAlert = AssetAlert::find($id);
        $assetAlert->delete();
        return $this->success('Asset Alert deleted successfully!');
    }
}
