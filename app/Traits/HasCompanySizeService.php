<?php

namespace App\Traits;

use App\Models\CompanySize;

trait HasCompanySizeService
{

    public function insertCompanySize($items, $model)
    {
        $companySize=new CompanySize(attributes: [
            'company_size_from' => $items[0],
                'company_size_to' => $items[1],
                'created_at' => now(),
                'updated_at' => now()
        ]);
        $model->companySize()->save($companySize);
    }


    public function updateCompanySize($items, $model): void
    {
        $companySize= [
            'company_size_from' => $items[0],
            'company_size_to' => $items[1],
        ];
        $model->companySize()->update($companySize);
    }

    public function deleteCompanySize($model){
        $model->companySize()->delete();
    }
}
