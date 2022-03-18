<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnalysisQuestionEditResource;
use App\Models\AnalysisQuestion;
use App\Models\AnalysisQuestionCondition;
use Illuminate\Http\Request;
use App\Services\AnalysisQuestionService;
use phpDocumentor\Reflection\Types\Collection;

class AnalysisQuestionController extends Controller
{
    protected $answerService;

    public function __construct(AnalysisQuestionService $service)
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

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $analysisQuestion=$this->service->autoStore($request);

        $this->service->insertTranslations($request->get('friendlyTranslations'), $analysisQuestion);
        if($request->has('answers')){
            $this->service->insertAnswers($request->get('answers'), $analysisQuestion);
        }
        if($request->has('company_size')){
            $this->service->insertCompanySize($request->get('company_size'), $analysisQuestion);
        }
        if($request->has('industries')){
            $this->service->insertIndustryRelation($request->get('industries'), $analysisQuestion);
        }
        if($request->has('assets')){
            $this->service->insertAssetRelation($request->get('assets'), $analysisQuestion);
        }
        if ($request->has('display_conditions')){
            $this->service->insertDisplayCondition($request->get('display_conditions'), $analysisQuestion);
        }


        return $this->success([], "Analysis Question created successfully");

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
     * @return AnalysisQuestionEditResource
     */
    public function edit($id)
    {
        $analysis_question_with_relations=AnalysisQuestion::with('translations','analysisQuestionAnswer.media','industryRelation','companySize','assetRelation','recommendation','recommendation.translations','analysisQuestionAnswer','analysisQuestionAnswer.translations','threat.analysisQuestion','threat.analysisQuestion.translations')->find($id);
        return $this->success(AnalysisQuestionEditResource::make($analysis_question_with_relations),"Analysis Questions fetched successfully");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AnalysisQuestion $analysisQuestion)
    {

        $this->service->autoUpdate($request, $analysisQuestion);

        $this->service->updateTranslations(($request->get('friendlyTranslations')));

        if ($request->has('industries')) {
            $this->service->deleteIndustryRelation($analysisQuestion);
            $this->service->insertIndustryRelation($request->get('industries'), $analysisQuestion);
        }
        if ($request->has('assets')) {
            $this->service->deleteAssetRelation($analysisQuestion);
            $this->service->insertAssetRelation($request->get('assets'), $analysisQuestion);
        }
        if ($request->has('company_size')) {
            $this->service->updateCompanySize($request->get('company_size'), $analysisQuestion);
        }
        if ($request->has('display_conditions')) {
            $analysisQuestion->analysisQuestionCondition()->delete();
            $this->service->insertDisplayCondition($request->get('display_conditions'), $analysisQuestion);
        }

        if ($request->has('answers')) {
            $this->service->updateAnswers($request->get('answers'), $analysisQuestion);
         }

        return $this->success([], 'Analysis Question updated successfully');
    }


        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         * @return \Illuminate\Http\Response
         */

        public function destroy(AnalysisQuestion $analysisQuestion)
        {
            $this->service->delete($analysisQuestion);
            $this->service->deleteIndustryRelation($analysisQuestion);
            $this->service->deleteCompanySize($analysisQuestion);
            $this->service->deleteAssetRelation($analysisQuestion);

            return $this->success([], "Analysis Question deleted successfully");

        }
    }
