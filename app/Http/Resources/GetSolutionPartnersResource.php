<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GetSolutionPartnersResource extends ResourceCollection
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = [];
        return [
            'items' => $this->collection->map(function ($items,$key){
                $data['id'] = $items->id;
                $data['is_active'] = $items->is_active ? true : false;
                $data['status'] = $items->status;
                $data['image'] = $items->firstMedia('uploads') ? $items->firstMedia('uploads')->getUrl() : '';
                $data['friendlyTranslations'] = $items->translations->mapWithKeys(function($item)
                {
                    return [
                        $item->language . '.' . $item->attribute_name => [
                            'id' => $item['id'],
                            'name' => $item['attribute_name'],
                            'value' => $item['attribute_value'],
                        ]
                    ];
                });
                $data['solutionPartnersProducts'] = $items->solutionPartnerProducts->map(function($item){
                    $title = $item['translations']->filter(function($value,$key){
                                return $value['attribute_name'] == 'title';
                            });
                    return [
                        'id' => $item->id,
                        'is_solution_partner' => $item->is_solution_partner,
                        'is_company_asset' => $item->is_company_asset,
                        'friendlyTranslations' => $title->mapWithKeys(function($trans){
                            return [
                                $trans->language . '.' . $trans->attribute_name => [
                                    'id' => $trans['id'],
                                    'name' => $trans['attribute_name'],
                                    'value' => $trans['attribute_value'],
                                ]
                            ];
                        })
                    ];
                });
                return $data;
            }),
            'pagination' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage()
            ]
        ];
    }
}

// 'items' => $this->collection->map(static function($item) {
//                 $item->translations = $item->translations ?? collect([]);
//                 $item->friendlyTranslations = $item->translations->mapWithKeys(function($item)
//                 {
//                     return [
//                         $item->language . '.' . $item->attribute_name => [
//                             'id' => $item['id'],
//                             'name' => $item['attribute_name'],
//                             'value' => $item['attribute_value'],
//                         ]
//                     ];
//                 });
//                 return $item;
//             }),
