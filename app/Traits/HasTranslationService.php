<?php

namespace App\Traits;

use App\Models\Translation;

trait HasTranslationService
{
    /**
     *
     */
    public function insertTranslations($items, $model): void
    {

        $data = [];
        foreach ($items as $lang => $languages) {
            foreach ($languages as $key=>$language) {
                $translation = new Translation([
                    'language' => $lang,
                    'attribute_name' => $key,
                    'attribute_value' => $language,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $data[] = $translation;
            }
        }
        $model->translations()->saveMany($data);
    }

    /**
     * @param $items
     */
    public function updateTranslations($items): void
    {
        foreach ($items as $language) {

            $translation = Translation::find($language['id']);

            $translation->attribute_value = $language['value'];
            $translation->save();
        }

    }

    public function createUpdateTranslations($items,$model): void
    {
        $data = [];
        foreach($items as $language => $item){
            $lang = explode('.',$language);
            $translation = new Translation([
                'language' => $lang[0],
                'attribute_name' => $lang[1],
                'attribute_value' => $item['value'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $data[] = $translation;
        }
        $model->translations()->saveMany($data);
    }

}
