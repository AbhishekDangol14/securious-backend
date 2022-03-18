<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Industry;
use App\Models\SolutionPartnerProduct;
use Illuminate\Http\Response;

class FetchDropDownController extends Controller
{
    public function fetchDropDownData(){

        //Industry, Category, Asset
        //Needs Re-factoring
        $industry=Industry::with('translations')->get();

        $category=Category::with('translations')->get();
        $asset=SolutionPartnerProduct::with('translations')->get();

        return response()->json(['message'=> "Fetched"]);
//        return reponse()->json([
//            'industry'=>$industry,
//            'category'=>$category,
//            'asset'=>$asset
//        ]);
    }

}
