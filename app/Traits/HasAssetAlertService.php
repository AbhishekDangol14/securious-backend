<?php

namespace App\Traits;

use App\Models\AssetAlert;

trait HasAssetAlertService {
    public function insertAssetAlert($items,$model) {
        $data = [];
        foreach($items as $assets){
            $asset = new AssetAlert([
                'risk_level' => $assets['risk_level'],
                'date' => $assets['date'],
                'link' => $assets['link']
            ]);
            $data[] = $asset;
        } 
        $model->assetAlert()->saveMany($data);
    }

    public function updateAssetAlert($items,$model) {
        $model->assetAlert()->delete();
        $this->insertAssetAlert($items,$model);
    }
}
