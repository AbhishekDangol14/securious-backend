<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GetNewsResource extends ResourceCollection
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
            'items' => $this->collection->map(static function($item) {
                $data['id'] = $item->id;
                $data['image'] = $item->firstMedia('uploads') ? $item->firstMedia('uploads')->getUrl() : '';
                $data['is_active'] = $item->is_active ? true : false;
                $data['news_category_id'] = $item->news_category_id;
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

