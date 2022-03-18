<?php

namespace App\Traits;
use App\Models\DescriptionFor;
trait HasDescriptionFor
{
public function insertIntoDescription($items, $recommendation)
    {
        foreach($items as $item){
            $description_for = new DescriptionFor();
            $description_for = $recommendation->descriptionFor()->save($description_for);
            $description_for->descriptionForAsset()->sync($item['solution_partner_product_id']);
        }
    }


//public function updateIntoDescription($items, $recommendation) {
//    foreach($items as $item){
//
//
//        $description_for->descriptionForAsset()->sync($item['solution_partner_product_id']);
//    }
//}



}
