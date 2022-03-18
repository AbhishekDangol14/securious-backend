<?php

namespace App\Traits;

trait HasSortOrder
{
 public function insertOrder($items, $model){

     foreach($items as $key=>$value){
         $id=$value['id'];
         $model->where('id', $id)->update(['order'=>$key]);
     }
 }
}
