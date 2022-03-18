<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSolutionPartnerProductRequest;
use App\Http\Requests\UpdateSolutionPartnerProductRequest;
use App\Http\Resources\GetSolutionPartnerProductsResource;
use App\Http\Resources\SolutionPartnerProductResource;
use App\Models\SolutionPartnerProduct;
use App\Services\SolutionPartnerProductService;
use Facade\IgnitionContracts\Solution;
use Illuminate\Http\Request;
use Plank\Mediable\Facades\MediaUploader;


class SolutionPartnerProductController extends Controller
{
    public function __construct(SolutionPartnerProductService $service)
    {
        $this->service = $service;
    }

    public function index(StoreSolutionPartnerProductRequest $request)
    {
        return $this->success(new GetSolutionPartnerProductsResource($this->service->paginate(14,
                [
                    'id',
                    'solution_partner_id',
                    'is_solution_partner',
                    'is_company_asset',
                    'product_link',
                    'company_size',
                    'industry_id',
                    'show_industry',
                    'show_company_size'
                ],
                ['translations'],
                [
                    'solution_partner_id' => $request->get('solution_partner_id'),
                    'product_link' => $request->get('product_link')
                ]
            )),
            'Solution Partner Products fetched successfully!'
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param SolutionPartnerProduct $solutionPartnerProduct
     * @return \Illuminate\Http\JsonResponse
     */
//    public function index (Request $request, SolutionPartnerProduct $solutionPartnerProduct)
//    {
//        if ($request->has('solution_partner_id'))
//            $solutionPartnerProduct = $solutionPartnerProduct->where('solution_partner_id', $request->get('solution_partner_id'));
//        return $this->success(
//            new ShowSolutionPartnerProductResource(
//                $solutionPartnerProduct->paginate()
//            ),
//            'Solution Partner Products Fetched successfully!'
//        );
//    }

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
     * @param  \App\Http\Requests\StoreSolutionPartnerProductRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSolutionPartnerProductRequest $request)
    {

        $solutionPartnerProduct = $this->service->autoStore($request);

        $this->service->insertTranslations($request->get('friendlyTranslations'), $solutionPartnerProduct);

        return $this->success(
            (array)SolutionPartnerProductResource::make($solutionPartnerProduct),
            'Solution Partner Product created successfully!',
            201
        );

//        $solutionPartnerProduct = $this->service->autoStore($request);
//        $this->service->insertTranslations($request->get('languages'), $solutionPartnerProduct);
//
//        $media = MediaUploader::fromSource($request->file('image'))
//            ->toDestination('public', 'product/logo')
//            ->useFilename(uniqid('logo_', true))
//            ->upload();
//        $solutionPartnerProduct->attachMedia($media, ['logo']);
//        $solutionPartnerProduct->logo = $solutionPartnerProduct->getMedia('logo')->first()->getUrl();
//
//        return $this->success(
//            (array)ShowSolutionPartnerProductResource::make($solutionPartnerProduct),
//            'Solution Partner Product created successfully!',
//            201
//        );
//        $solutionPartnerProduct=new SolutionPartnerProduct();
//        $solutionPartnerProduct->solution_partner_id=$request->get('solution_partner_id');
//        $solutionPartnerProduct->is_solution_partner=$request->get('is_solution_partner');
//        $solutionPartnerProduct->is_company_asset=$request->get('is_company_asset');
//        $solutionPartnerProduct->product_link=$request->get('product_link');
//        $solutionPartnerProduct->company_size=$request->get('company_size');
//        $solutionPartnerProduct->industry_id=$request->get('industry_id');
//        $solutionPartnerProduct->show_industry=$request->get('show_industry');
//        $solutionPartnerProduct->show_company_size=$request->get('show_company_size');
//        $solutionPartnerProduct->save();

//        return $this->success(
//            GetSolutionPartnerProductsResource::make($solutionPartnerProduct),
//            'Solution Partner Product created successfully!',
//            201
//        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SolutionPartnerProduct  $solutionPartnerProduct
     * @return \Illuminate\Http\Response
     */
    public function show(SolutionPartnerProduct $solutionPartnerProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SolutionPartnerProduct  $solutionPartnerProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(SolutionPartnerProduct $solutionPartnerProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSolutionPartnerProductRequest  $request
     * @param  \App\Models\SolutionPartnerProduct  $solutionPartnerProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SolutionPartnerProduct $solutionPartnerProduct)
    {

        $updatedSolutionPartnerProduct = $this->service->autoUpdate($request, $solutionPartnerProduct);

        $this->service->updateTranslations(($request->get('friendlyTranslations')));

//        $this->service->updateTranslations(json_decode($request->get('friendlyTranslations'),true));

        return $this->success(
            (array)SolutionPartnerProductResource::make($updatedSolutionPartnerProduct), 'Solution Partner Product updated successfully!');


//        $updatedSolutionPartnerProduct = $this->service->autoUpdate($request, $solutionPartnerProduct);
//        $this->service->updateTranslations(json_decode($request->get('friendlyTranslations'),true));
//
//        return $this->success(
//            (array)ShowNewsResource::make($updatedSolutionPartnerProduct), 'News updated successfully!');

        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SolutionPartnerProduct  $solutionPartnerProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(SolutionPartnerProduct $solutionPartnerProduct)
    {
        $this->service->delete($solutionPartnerProduct);

        return $this->success('Solution Partner Product deleted successfully!');
    }

    public function deleteImage($id,SolutionPartnerProduct $solutionPartnerProduct)
    {
        $this->service->deleteImage($id,$solutionPartnerProduct);
    }
}
