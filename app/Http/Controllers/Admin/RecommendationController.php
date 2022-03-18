<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Resources\AnalysisQuestionEditResource;
use App\Http\Resources\RecommendationEditResource;
use App\Models\AnalysisQuestion;
use App\Models\DescriptionFor;
use App\Models\Recommendation;
use App\Models\RecommendationQuestionAnswer;
use App\Services\RecommendationService;
use Illuminate\Http\Request;


class RecommendationController extends Controller
{

    /**
     * @param RecommendationService $service
     */
    public function __construct(RecommendationService $service){
        $this->service=$service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $recommendation= $this->service->autoStore($request);

        $this->service->insertTranslations($request->get('friendlyTranslations'), $recommendation);

        if($request->has('industries')){
            $this->service->insertIndustryRelation($request->get('industries'), $recommendation);
        }
        if($request->has('company_size')){
            $this->service->insertCompanySize($request->get('company_size'), $recommendation);
        }
        if($request->has('assets')){
            $this->service->insertAssetRelation($request->get('assets'), $recommendation);
        }

        if ($request->has('question_answer')) {
            $this->service->insertIntoRecommendationQuestionAnswer($request->get('question_answer'), $recommendation);
        }
        if($request->has('solution_partner')){
            $recommendation->solutionPartner()->sync($request->get('solution_partner'));
        }
        if($request->has('description')){
            $items=$request->get('description');
            $this->service->insertIntoDescription($items, $recommendation);
        }

        return $this->success([], "Recommendation created successfully");
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

        public function edit($id)
    {

//        $a=Recommendation::with('threat.analysisQuestion','threat.analysisQuestion.translations','industryRelation','assetRelation','companySize','recommendationQuestionAnswer')->find($id);//        return RecommendationEditResource::make($a);
        $a = Recommendation::with('recommendationQuestionAnswer')->find($id);
        return $a;
//        $recommendation_with_relations=Recommendation::with('recommendationQuestionAnswer')->find($id);
//        return $recommendation_with_relations;
//        $recommendation_with_relations=Recommendation::with('translations','media','industryRelation','companySize','assetRelation','categoryRelation','importantIndustry','importantCompanySize','analysisQuestion','analysisQuestion.translations','recommendation','recommendation.translations')->find($id);
//        return $this->success(RecommeEditResource::make($recommendation_with_relations), "Analysis Questions fetched successfully",201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recommendation $recommendation)
    {
        $this->service->autoUpdate($request, $recommendation);

        $this->service->updateTranslations(($request->get('friendlyTranslations')));

        if($request->has('industries')){
            $this->service->deleteIndustryRelation($recommendation);
            $this->service->insertIndustryRelation($request->get('industries'), $recommendation);
        }
        if($request->has('company_size')){
            $this->service->deleteCompanySize($recommendation);
            $this->service->insertCompanySize($request->get('company_size'), $recommendation);
        }
        if($request->has('assets')){
            $this->service->deleteAssetRelation($recommendation);
            $this->service->insertAssetRelation($request->get('assets'), $recommendation);
        }

          if($request->has('question_answer')){
            $this->service->updateIntoRecommendationQuestionAnswer($request->get('question_answer'), $recommendation->id);
        }
        if($request->has('solution_partner')){
            $recommendation->solutionPartner()->sync($request->get('solution_partner'));
        }
                  if($request->has('questions')){
                $recommendation->analysisQuestion()->sync($request->get('questions'));
          }
//Only problem in Description For
        if($request->has('description_for')){
            $items=$request->get('description_for');
            $this->service->updateIntoDescription($items,$recommendation);
        }
            return $this->success([], "Analysis Question updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recommendation $recommendation)
    {
        $this->service->delete($recommendation);
        $this->service->deleteIndustryRelation($recommendation);
        $this->service->deleteCompanySize($recommendation);
        $this->service->deleteAssetRelation($recommendation);

        return $this->success([], "Recommendation deleted successfully");
    }
}
