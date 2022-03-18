<?php

namespace App\Services;

use App\Models\Recommendation;
use App\Repositories\ThreatRepository;
use App\Traits\HasAssetService;
use App\Traits\HasCategoryService;
use App\Traits\HasCompanySizeService;
use App\Traits\HasIndustryService;
use App\Traits\HasSortOrder;
use App\Traits\HasTranslationService;
use Illuminate\Support\Facades\DB;


class ThreatService extends BaseService
{
    use HasTranslationService, HasAssetService, HasCompanySizeService, HasIndustryService, HasCategoryService, HasSortOrder;

    public function __construct(ThreatRepository $repository){
        $this->repository = $repository;
    }

    public function getPoints(){
        $threat_ids = Recommendation::select('threat_id')->groupBy('threat_id')->get()->toArray() ;

      foreach($threat_ids as $threat_id){
          $point=DB::table('recommendations')->select('threat_id')->where('threat_id', $threat_id )->sum('points');
      }
//   return "hello";
    }
}
