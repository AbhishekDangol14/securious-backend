<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowCustomerThreatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'friendlyTranslations' => $this->translations->mapWithKeys(function($item) {
                return [
                    $item->language . '.' . $item->attribute_name => [
                        'id' => $item['id'],
                        'name' => $item['attribute_name'],
                        'value' => $item['attribute_value'],
                    ]
                ];
            }),
            'points' => $this->getPoints(),
            'image' => $this->firstMedia('uploads') ? $this->firstMedia('uploads')->getUrl() : '',
            'video_link' => $this->video_link,
            'is_completed' => $this->customerThreat ? $threatData->customerThreat->is_completed : false,
            'estimated_time_in_minutes' => $this->estimated_time_in_minutes ? $this->estimated_time_in_minutes : 0,
            'last_answered_question' => $this->customerThreat ? $this->customerThreat->last_answered_question : 0,
            'questions' => $this->question->map(function($item){
                return [
                    'id' => $item->id,
                    'friendlyTranslations' => $item->translations->mapWithKeys(fn($item) => [
                         $item->language . '.' . $item->attribute_name => [
                             'id' => $item['id'],
                             'name' => $item['attribute_name'],
                             'value' => $item['attribute_value'],
                         ]
                     ]),
                    'video_link' => $item->video_link,
                    'company_size' => $item->companySize ? [$item->companySize->company_size_from,$item->companySize->company_size_to] : [],
                    'question_type' => $item->question_type,
                    'answers' => $item->analysisQuestionAnswer->map(fn($answer) => [
                        'id' => $answer->id,
                        'image' => $answer->firstMedia('uploads') ? $answer->firstMedia('uploads')->getUrl() : '',
                        'friendlyTranslations' => $item->translations->mapWithKeys(fn($item) => [
                            $item->language . '.' . $item->attribute_name => [
                                'id' => $item['id'],
                                'name' => $item['attribute_name'],
                                'value' => $item['attribute_value'],
                            ]
                        ]),
                    ]),
                    'customer_answer' => $item->customerAnswers->map(fn($customerAnswer) use($item){
                         return [
                            if ($item->question_type == AnalysisQuestion::ANSWER_RADIO || $item->question_type == AnalysisQuestion::ANSWER_SLIDER || $item->question_type == AnalysisQuestion::ANSWER_DROPDOWN) {
                                return [
                                    'risk_analysis_id' => $item->id,
                                    'type' => $item->question_type,
                                    'data' => optional($customerAnswer)->answer_id ?? null,
                                ];
                            } elseif ($item->question_type == AnalysisQuestion::ANSWER_MULTI_SELECT || $item->question_type == AnalysisQuestion::ANSWER_MULTIPLE_CHOICE) {
                                return [
                                    'type' => $item->question_type,
                                    'risk_analysis_id' => $risk_analysis->id,
                                    'data' => $customerAnswer ? $risk_analysis->customerAnswers()->get()->pluck('answer_id')->toArray() : [],
                                ];
                            } elseif ($item->question_type == AnalysisQuestion::ANSWER_TEXT) {
                                return [
                                    'type' => $item->question_type,
                                    'risk_analysis_id' => $risk_analysis->id,
                                    'data' => optional($customerAnswer)->text ?? "",
                                ];
                            }
                        ]
                    }),
                ];
            })
        ];
    }
}
