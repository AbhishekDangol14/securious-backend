<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ShowDropDownResource;
use App\Services\IntroService;
use App\Models\Country;
use App\Models\CompanyRole;
use App\Models\LegalRole;

class IntroController extends Controller
{
    public function __construct(IntroService $service)
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
        $industries = new ShowDropDownResource($this->service->showIndustry());
        $countries = Country::select('id','name')->get();
        $roles = CompanyRole::select('id','name')->get();
        $legal_roles = LegalRole::select('id','title')->get();

        return $this->success(['industries' => $industries, 'countries' => $countries, 'roles' => $roles, 'legal_roles' => $legal_roles],'DropDown fetched successfully');
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
        $user = auth()->user();
        $domain = $request['website_url'] ? $this->purifyDomain($request['website_url']) : null;
        
        $company = $this->service->storeCompany($request['company'],$user);
        $this->service->updateUserCompany($company->id,$user);
        
        $profile = $this->service->storeProfile($request['profile'],$user);

        $this->service->scanToDetectAssets($domain,$user);

        $this->service->updateIntroduction($user);
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

    public function purifyDomain($domain)
    {
        $domain = trim($domain, '/'); // removes extra slash from url
        if (!preg_match('#^http(s)?://#', $domain)) {
            $domain = 'http://' . $domain; // adds http in url if don't contain
        }
        $urlParts = parse_url($domain);

        return preg_replace('/^www\./', '', $urlParts['host']);
    }
}
