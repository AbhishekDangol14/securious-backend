<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EditSolutionPartner extends JsonResource
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
            'status' => $this->status,
            'friendlyTranslations' => $this->translations->mapWithKeys(function($item) {
                return [
                    $item->language . '.' . $item->attribute_name => [
                        'id' => $item['id'],
                        'name' => $item['attribute_name'],
                        'value' => $item['attribute_value'],
                    ]
                ];
            }),
            'solutionPartnersProducts' => $this->solutionPartnerProducts->map(function($item) {
                return [
                    'id' => $item['id'],
                    'image' => $item->firstMedia('uploads') ? $item->firstMedia('uploads')->getUrl() : '',
                    'is_active' => $item['is_active'] ? true : false,
                    'show_if_industry' => $item['show_if_industry'] ? true : false,
                    'show_if_company_size' => $item['show_if_company_size'] ? true : false,
                    'is_solution_partner' => $item['is_solution_partner'] ? true : false,
                    'is_company_asset' => $item['is_company_asset'] ? true : false,
                    'product_link' => $item['product_link'],
                    'industries' => $item['industryRelation']->map(function($item) {
                        return $item['industry_id'];
                    }),
                    'company_size' => [ $item['companySize']->company_size_from, $item['companySize']->company_size_to],
                    'asset_alert' => $item['assetAlert'],
                    'friendlyTranslations' => $item['translations']->mapWithKeys(function($item) {
                        return [
                            $item->language . '.' . $item->attribute_name => [
                                'id' => $item['id'],
                                'name' => $item['attribute_name'],
                                'value' => $item['attribute_value'],
                            ]
                        ];
                    }),
                ];
            }),
        ];
    }
}
