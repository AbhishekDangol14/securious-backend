<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThreatEditResource extends JsonResource
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
            'image' => $this->firstMedia('uploads') ? $this->firstMedia('uploads')->getUrl() : '',
            'estimated_time_in_minutes' => $this->estimated_time_in_minutes,
            'video_link' => $this->video_link,
            'is_always_important' => $this->is_always_important ? true : false,
            'important_if_industry_id' => $this->important_if_industry_id ? true : false,
            'important_if_company_size' => $this->important_if_company_size ? true : false,
            'is_display_active_always' => $this->is_display_active_always ? true : false,
            'show_if_industry' => $this->show_if_industry ? true : false,
            'show_if_company_size' => $this->show_if_company_size ? true : false,
            'show_if_using_asset' => $this->show_if_using_asset ? true : false,
            'status' => $this->status,
            'is_display_active_always' => $this->is_display_active_always,
            'friendlyTranslations' => $this->translations->mapWithKeys(function($item) {
                return [
                    $item->language . '.' . $item->attribute_name => [
                        'id' => $item['id'],
                        'name' => $item['attribute_name'],
                        'value' => $item['attribute_value'],
                    ]
                ];
            }),
            'industry_id' => $this->industryRelation->map(function($item) {
                return $item['industry_id'];
            }),
            'category_id' => $this->categoryRelation->map(function($item) {
                return $item['category_id'];
            }),
            'asset_relation' => $this->assetRelation->map(function($item) {
                return $item['asset_id'];
            }),
            'important_industry_id' => $this->importantIndustry->map(function($item) {
                return $item['pivot']->industry_id;
            }),
            'company_size' => [$this->companySize->company_size_from,$this->companySize->company_size_to],
            'important_company_size' => [$this->importantCompanySize->company_size_from,$this->importantCompanySize->company_size_to],
            'analysisQuestion' => $this->analysisQuestion->map(function($items) {
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
