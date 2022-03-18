<?php

namespace App\Traits;

use App\Models\Industry;
use App\Models\IndustryRelation;

trait HasIndustryService
{
    public function insertIndustryRelation($items, $model) {
       $data= array_map(function($industry){
            return new IndustryRelation([
                    'industry_id'=> $industry,
                    'created_at' => now(),
                    'updated_at' => now()
            ]);
        }, $items);

       $model->industryRelation()->saveMany($data);
    }

   public function updateIndustryRelation($items, $model): void
   {
       //Code can be more optimized
       $model->industryRelation()->delete();

       $this->insertIndustryRelation($items, $model);
   }

   public function deleteIndustryRelation($model){
        $model->industryRelation()->delete();
   }

   public function showIndustry(){
       return Industry::with('translations')->where('is_active',1)->get();
   }
}
