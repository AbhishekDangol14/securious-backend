<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShowIndustryResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [];
        return [
           'industries' => $this->collection->map(static function($item) {
                $data['id'] = $item->id;
                $data['friendlyTranslations'] = $item->translations->mapWithKeys(function($item)
                {
                    return [
                        $item->language . '.' . $item->attribute_name => [
                            'id' => $item['id'],
                            'name' => $item['attribute_name'],
                            'value' => $item['attribute_value'],
                        ]
                    ];
                });
               return $data;
           }),
        ];
    }
}
