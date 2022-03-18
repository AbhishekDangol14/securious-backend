<?php

namespace App\Http\Controllers;

use App\Http\Resources\GetProfilesResource;
use App\Models\Profile;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function __construct(ProfileService $service){
            $this->service=$service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        return $this->success(new GetProfilesResource($this->service->paginate(14, ['id','company_name','company_website','company_size','business_address','business_area','company_logo','legal_role_id','industry_id','stripe_id','created_at','updated_at','last_assets_update','recommendation_view_limit'])),
            'Profiles fetched successfully!');
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

        $into_profile=$this->service->autoStore($request);
        return $this->success("Profile created successfully");
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
    public function update(Request $request, Profile $profile)
    {

        $profile=$this->service->autoUpdate($request, $profile);
        return "Profile Updated Successfully";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {

        $this->service->simpleDelete($profile);

        return $this->success("Profile deleted successfully ");
    }


}
