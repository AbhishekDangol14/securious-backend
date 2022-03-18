<?php

namespace App\Traits;

use App\Models\RecommendationQuestionAnswer;

trait HasQuestionAnswer
{
    public function insertIntoRecommendationQuestionAnswer($question_answers, $recommendation)
    {
        $answers = $question_answers['answers'];

        foreach($answers as $answer)
        {
            $recommendation_question_answer= new RecommendationQuestionAnswer([
                 'answer_id'=>$answer,
                 'question_id'=>$question_answers['question_id'],
                 'recommendation_id'=>$recommendation->id
            ]);
            
            $recommendation_question_answer->save();
        }

        $recommendation->analysisQuestion()->sync($question_answers['question_id']);
    }

    public function updateIntoRecommendationQuestionAnswer($question_answers, $recommendation_id){

        $answers = $question_answers['answers'];

        foreach($answers as $answer)
        {
            $recommendation_question_answer= new RecommendationQuestionAnswer([
                'answer_id'=>$answer,
                'question_id'=>$question_answers['question_id'],
                'recommendation_id'=>$recommendation_id
            ]);

            $recommendation_question_answer->update();

            //I dont think this will work should we use Model::update
        }
    }
}
