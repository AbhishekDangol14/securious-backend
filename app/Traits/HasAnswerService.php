<?php

namespace App\Traits;

use App\Models\AnalysisQuestionAnswer;
use App\Models\Translation;

trait HasAnswerService
{
    public function insertAnswers($items, $model) {
        foreach($items as $key => $item){

            $answer = new AnalysisQuestionAnswer([
                'solution_partner_product_id' => $items[$key]['solution_partner_product_id'],
                'analysis_question_id' => $model->id,
                'order' => $key
            ]);

            
            $answer_id = $model->analysisQuestionAnswer()->save($answer);


            $this->insertTranslations($item['friendlyTranslations'], $answer_id);

        }

    }
    public function updateAnswers($items, $model) {

        foreach($items as $key => $item){
            $answer = new AnalysisQuestionAnswer([
                'solution_partner_product_id' => $items[$key]['solution_partner_product_id'],
                'analysis_question_id' => $model->id,
                'order' => $key
            ]);

        if (!$item['id']) {
           $ansModel = $model->analysisQuestionAnswer()->save($answer);
           $this->createUpdateTranslations($item['friendlyTranslations'],$ansModel);
           return;  
        }

        $model->analysisQuestionAnswer()->update($answer->toArray());
        $this->updateTranslations($item['friendlyTranslations']);

        }

    }





}
