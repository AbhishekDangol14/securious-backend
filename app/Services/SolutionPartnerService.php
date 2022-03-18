<?php

namespace App\Services;


use App\Repositories\SolutionPartnerRepository;
use App\Traits\HasTranslationService;
use App\Traits\HasIndustryService;
use App\Traits\HasCompanySizeService;
use App\Traits\HasAssetAlertService;
use App\Models\SolutionPartnerProduct;

class SolutionPartnerService extends BaseService
{
    use HasTranslationService,HasCompanySizeService,HasIndustryService,HasAssetAlertService;

    public function __construct(SolutionPartnerRepository $repository){
        $this->repository = $repository;
    }

    public function insertSolutionPartnerProduct($items, $model) {
        foreach($items as $item) {
            $data = new SolutionPartnerProduct([
                'show_if_industry' => $item['show_if_industry'],
                'show_if_company_size' => $item['show_if_company_size'],
                'is_solution_partner' => $item['is_solution_partner'],
                'is_company_asset' => $item['is_company_asset'],
                'product_link' => $item['product_link'],
                'is_active' => $item['is_active']
            ]);

            $solutionPartnerProduct = $model->solutionPartnerProducts()->save($data);

            if ($item['image'])
                $this->saveImage($item['image'],$solutionPartnerProduct);

            $this->insertTranslations($item['friendlyTranslations'],$solutionPartnerProduct);

            if($item['industries'])
                $this->insertIndustryRelation($item['industries'],$solutionPartnerProduct);
            if($item['company_size'])
                $this->insertCompanySize($item['company_size'],$solutionPartnerProduct);
            if($item['asset_alert'])
                $this->insertAssetAlert($item['asset_alert'],$solutionPartnerProduct);
        }
    }

    public function updateSolutionPartnerProduct($items, $model) {
        foreach($items as $item) {
            $data = new SolutionPartnerProduct([
                'show_if_industry' => $item['show_if_industry'],
                'show_if_company_size' => $item['show_if_company_size'],
                'is_solution_partner' => $item['is_solution_partner'],
                'is_company_asset' => $item['is_company_asset'],
                'product_link' => $item['product_link'],
                'is_active' => $item['is_active']
            ]);

            if($item['id']) { 
                $model->solutionPartnerProducts()->where('id',$item['id'])->update($data->toArray());
                $solutionPartnerProduct = SolutionPartnerProduct::find($item['id']);
                
                if ($item['image'] && $this->is_base64($item['image'])) {
                    $this->saveImage($item['image'],$solutionPartnerProduct);
                }
                
                $this->updateTranslations($item['friendlyTranslations']);
            }
            else {
                $solutionPartnerProduct = $model->solutionPartnerProducts()->save($data);
                
                if ($item['image'])
                    $this->saveImage($item['image'],$solutionPartnerProduct);
                
                $this->createUpdateTranslations($item['friendlyTranslations'],$solutionPartnerProduct);
            }

            if($item['industries'])
                $this->updateIndustryRelation($item['industries'],$solutionPartnerProduct);
            if($item['company_size'])
                $this->updateCompanySize($item['company_size'],$solutionPartnerProduct);
            if($item['asset_alert'])
                $this->updateAssetAlert($item['asset_alert'],$solutionPartnerProduct);
        }
    }

}
