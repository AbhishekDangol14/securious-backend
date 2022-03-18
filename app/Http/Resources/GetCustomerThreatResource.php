<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GetCustomerThreatResource extends ResourceCollection
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
            $this->collection->map(static function($item) {
                    $data['id'] = $item->id;
                    $data['estimated_time_in_minutes'] = $item->estimated_time_in_minutes;
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
                })
        ];
    }
}
