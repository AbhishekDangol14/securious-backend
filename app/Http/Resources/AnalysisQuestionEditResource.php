<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnalysisQuestionEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $question=$this->threat->analysisQuestion->filter(function($value,$key) {
            return $value['order']<$this->order;
        });


        return [
            'id' => $this->id,
            'video_link' => $this->video_link,
            'question_type' => $this->question_type,
            'details_level' => $this->details_level,
            'show_if_industry' => $this->show_if_industry ? true : false,
            'show_if_company_size' => $this->show_if_company_size ? true : false,
            'show_if_using_asset' => $this->show_if_using_asset ? true : false,
            'question'=>$question->map(function($item){
             return [
               'id'=>$item['id'],
                 'friendlyTranslations' => $item->translations->mapWithKeys(fn($item) => [
                     $item->language . '.' . $item->attribute_name => [
                         'id' => $item['id'],
                         'name' => $item['attribute_name'],
                         'value' => $item['attribute_value'],
                     ]
                 ])
             ];
            }),


            'friendlyTranslations' => $this->translations->mapWithKeys(function($item) {
                return [
                    $item->language . '.' . $item->attribute_name => [
                        'id' => $item['id'],
                        'name' => $item['attribute_name'],
                        'value' => $item['attribute_value'],
                    ]
                ];
            }),
            'industries' => $this->industryRelation->map(function($item) {
                return $item['industry_id'];
            }),
            'assets' => $this->assetRelation->map(function($item) {
                return $item['asset_id'];
            }),
            'company_size' => [$this->companySize->company_size_from,$this->companySize->company_size_to],
            'answers' => $this->analysisQuestionAnswer->map(function($items) {
                $items->friendlyTranslations = $items['translations']->mapWithKeys(function($trans){
                    return [
                        $trans->language . '.' . $trans->attribute_name => [
                            'id' => $trans['id'],
                            'name' => $trans['attribute_name'],
                            'value' => $trans['attribute_value'],
                        ]
                    ];
                });

                return [
                   'id' => $items['id'],
                   'solution_partner_product_id' => $items['solution_partner_product_id'],
                   'friendlyTranslations' => $items->friendlyTranslations
               ];
            }),
            'recommendation' => $this->recommendation->map(function($items) {
                $title = $items['translations']->filter(function($value,$key){
                    return $value['attribute_name'] == 'title';
                });
                $items->friendlyTranslations = $title->mapWithKeys(function($trans){
                    return [
                        $trans->language . '.' . $trans->attribute_name => [
                            'id' => $trans['id'],
                            'name' => $trans['attribute_name'],
                            'value' => $trans['attribute_value'],
                        ]
                    ];
                });

                return [
                   'id' => $items['id'],
                   'friendlyTranslations' => $items->friendlyTranslations
               ];
            })
        ];
    }
}
