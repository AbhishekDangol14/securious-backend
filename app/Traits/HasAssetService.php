<?php

namespace App\Traits;

use App\Models\AssetRelation;
use App\Models\SolutionPartnerProduct;


trait HasAssetService
{
    public function insertAssetRelation($items, $model) {
        $data= array_map(function($asset){
            return new AssetRelation([
                'solution_partner_product_id'=>$asset,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }, $items);
        $model->assetRelation()->saveMany($data);
    }

    public function deleteAssetRelation($model){
        $model->assetRelation()->delete();
    }


    public function showAsset(){
        return SolutionPartnerProduct::with('translations')->where('is_company_asset', 1)->get();
    }
//    public function updateAssetRelation($items, $model): void
//    {
//        $model->assetRelation()->delete();
//        $data= array_map(function($asset){
//            return new AssetRelation([
//                'solution_partner_product_id'=>$asset,
//                'created_at' => now(),
//                'updated_at' => now()
//            ]);
//        }, $items);
//        $model->assetRelation()->saveMany($data);
//    }
}
