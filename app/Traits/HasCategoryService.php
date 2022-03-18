<?php

namespace App\Traits;

use App\Models\Category;
use App\Models\CategoryRelation;


trait HasCategoryService
{
    public function insertCategoryRelation($items, $model) {
        $data= array_map(function($category){
            return new CategoryRelation([
                'category_id'=>$category,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }, $items);
        $model->categoryRelation()->saveMany($data);
    }

//    public function updateCategoryRelation($items): void
//    {
////        foreach ($items as $key) {
////            $assetRelation = AssetRelation::find($key['id']);
////            $assetRelation->industry_id=$key['solution_partner_product_id'];
////            $assetRelation->status=$key['status'];
////            $assetRelation->save();
////        }
//    }

    public function showCategory(){
        $category=Category::pluck('id');
        return $category;
    }


}
