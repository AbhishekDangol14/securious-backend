<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShowDropDownResource;
use App\Http\Resources\GetThreatsResource;
use App\Http\Resources\ShowThreatsResource;
use App\Http\Resources\ThreatEditResource;
use App\Models\AnalysisQuestion;
use App\Models\Category;
use App\Models\ImportantIndustry;
use App\Models\Recommendation;
use App\Models\SolutionPartner;
use App\Models\Threat;
use App\Services\AnalysisQuestionService;
use App\Services\RecommendationService;
use App\Services\ThreatService;
use Facade\IgnitionContracts\Solution;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ThreatController extends Controller
{
    protected $analysisQuestionService;
    protected $recommendationService;
    public function __construct(ThreatService $service, AnalysisQuestionService $analysisQuestionService, RecommendationService $recommendationService)
    {
        $this->service = $service;
        $this->analysisQuestionService=$analysisQuestionService;
        $this->recommendationService=$recommendationService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $threats = new GetThreatsResource($this->service->paginate(14, [],['translations','industryRelation','companySize','assetRelation','media','recommendation']));
        return $this->success($threats,'Threats fetched successfully!',201);
    }



    /**
     * Store a newly created resource in s,torage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $threat = $this->service->autoStore($request);
        $this->service->insertTranslations($request->get('friendlyTranslations'), $threat);

        if($request->has('important_industry')){
            $threat->importantIndustry()->sync($request->get('important_industry'));
        }
        if($request->has('industries')){
            $this->service->insertIndustryRelation($request->get('industries'), $threat);
        }
        if($request->has('company_size')){
            $this->service->insertCompanySize($request->get('company_size'), $threat);
        }

        if($request->has('assets')){
            $this->service->insertAssetRelation($request->get('assets'), $threat);
        }
        if($request->has('important_company_size')){
            $threat->importantCompanySize()->create([
                'company_size_from'=>$request->important_company_size[0],
                'company_size_to'=>$request->important_company_size[1]
            ]);
        }
        if ($request->has('categories')){
            $this->service->insertCategoryRelation($request->get('categories'), $threat);
        }
        return $this->success(
            (array)ShowThreatsResource::make($threat->importantIndustry),
            'Threat created successfully!',
            201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $threat_with_relations=Threat::with('translations','media','industryRelation','companySize','assetRelation','categoryRelation','importantIndustry','importantCompanySize','analysisQuestion','analysisQuestion.translations','recommendation','recommendation.translations')->find($id);
        return $this->success(ThreatEditResource::make($threat_with_relations), "Threat fetched successfully",201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Threat $threat
     * @return Response
     */
    public function update(Request $request, Threat $threat)
    {
        $this->service->autoUpdate($request, $threat);

        $this->service->updateTranslations(($request->get('friendlyTranslations')));

        if($request->has('important_industry')){
            $threat->importantIndustry()->sync($request->get('important_industry'));
        }
        if($request->has('industries')){
            $this->service->deleteIndustryRelation($threat);
            $this->service->insertIndustryRelation($request->get('industries'), $threat);
        }
        if($request->has('company_size')){
            $this->service->deleteCompanySize($threat);
            $this->service->insertCompanySize($request->get('company_size'), $threat);
        }
        if($request->has('assets')){
            $this->service->deleteAssetRelation($threat);
            $this->service->insertAssetRelation($request->get('assets'), $threat);
        }
        if($request->has('important_company_size')) {
            $important_company_size = $request->get('important_company_size');
            $threat->importantCompanySize()->update([
                'company_size_from' => $important_company_size[0],
                'company_size_to' => $important_company_size[1]
            ]);
        }
        if($request->has('analysisQuestion')) {
            $items = $request->get('analysisQuestion');
            $model = new AnalysisQuestion();
            $this->service->insertOrder($items, $model);
            foreach($items as $item)
                $this->analysisQuestionService->updateTranslations($item['friendlyTranslations']);
        }
        if($request->has('recommendation')){
            $items=$request->get('recommendation');
            $model=new Recommendation();
            $this->service->insertOrder($items, $model);
            foreach($items as $item)
                $this->recommendationService->updateTranslations($item['friendlyTranslations']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $industries = new ShowDropDownResource($this->service->showIndustry());
        $assets = new ShowDropDownResource($this->service->showAsset());
        return $this->success([$industries,$assets],'DropDown fetched successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Threat $threat
     * @return Response
     */
    public function destroy(Threat $threat)
    {
        $this->service->delete($threat);
        $this->service->deleteIndustryRelation($threat);
        $this->service->deleteCompanySize($threat);
        $this->service->deleteAssetRelation($threat);

        return $this->success('Threat deleted successfully!');
    }

    public function getDropDownItems(){
      $industry=$this->service->showIndustry();
      $category=$this->service->showCategory();
      $asset=$this->service->showAsset();

      return response()->json([
          'industry'=>$industry,
         'category'=>$category,
          'asset'=>$asset]);
    }

    public function deleteImage($id, Threat $threat)
    {
        $this->service->deleteImage($id, $threat);
    }
}
