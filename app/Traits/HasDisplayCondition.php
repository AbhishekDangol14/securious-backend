<?php

namespace App\Traits;
 use App\Models\AnalysisQuestionCondition;

 trait HasDisplayCondition
 {
     public function insertDisplayCondition($conditions, $model)
     {

         $data = [];
         foreach ($conditions as $key => $value) {

             $displayCondition = new AnalysisQuestionCondition([
                 'question_id' => $conditions[$key]['analysis_question_id'],
                 'answer_id' => $conditions[$key]['answer_id'],
                 'rule' => $conditions[$key]['rule'],
                 'is_equal_to' => $conditions[$key]['is_equal_to'],
                 'created_at' => now(),
                 'updated_at' => now()
             ]);
             $data[] = $displayCondition;
         }
         $model->analysisQuestionCondition()->saveMany($data);

     }
//
//     public function updateDisplayCondition($conditions, $model)
//     {
//         foreach ($conditions as $condition) {
//             $displayCondition = new AnalysisQuestionCondition([
//                 'question_id' => $condition['analysis_question_id'],
//                 'answer_id' => $condition['answer_id'],
//                 'rule' => $condition['rule'],
//                 'is_equal_to' => $condition['is_equal_to'],
//                 'created_at' => now(),
//                 'updated_at' => now()
//             ]);
//             $model->analysisQuestionCondition()->update($displayCondition->toArray());
//         }



 }
