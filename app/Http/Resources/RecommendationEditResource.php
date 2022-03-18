<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecommendationEditResource extends JsonResource
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
//            'company_size' => [$this->companySize->company_size_from, $this->companySize->company_size_to],
            'assets' => $this->assetRelation->map(function($item) {
                return $item['asset_id'];
            }),
            'industries' => $this->industryRelation->map(function($item) {
                return $item['industry_id'];
            }),
            'order'=>$this->order,
            'is_automated'=>$this->is_automated,
            'points'=>$this->points,
            'show_if_industry'=>$this->show_if_industry,
            'show_if_company_size'=>$this->show_if_company_size,
            'display_if_conditions'=>$this->display_if_conditions,
            'threat_id'=>$this->threat_id,
            'friendlyTranslations' => $this->translations->mapWithKeys(function($item) {
                return [
                    $item->language . '.' . $item->attribute_name => [
                        'id' => $item['id'],
                        'name' => $item['attribute_name'],
                        'value' => $item['attribute_value'],
                    ]
                ];
            }),
            'question' => $this->threat->analysisQuestion->mapWithKeys(function($items){
              return [
                  'id' => $items['id'],
                  'friendlyTranslations' => $items['translations']->mapWithKeys(function($item) {
                      return [
                          $item->language . '.' . $item->attribute_name => [
                              'id' => $item['id'],
                              'name' => $item['attribute_name'],
                              'value' => $item['attribute_value'],
                          ]
                      ];
                  })
              ];
            })
        ];
    }
}
