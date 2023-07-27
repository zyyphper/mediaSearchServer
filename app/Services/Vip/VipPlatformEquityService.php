<?php


namespace App\Services\Vip;


use App\Helpers\Tools;
use App\Libraries\Base\BaseService;
use App\Models\Vip\VipPlatformEquity;

class VipPlatformEquityService extends BaseService
{
    /**
     * 获取剩余权益
     * @param $equity
     * @param $unit
     * @return float
     */
    public function getEquityById($equity,$unit)
    {
        $data = VipPlatformEquity::where('equity_id',$equity)
            ->where('platform_id',$this->platformId)
            ->select('total_num','total_unit')
            ->first();
        //单位转换
        return Tools::spaceCapacityUnitConvert($data['total_num'],$data['total_unit'],$unit);
    }

}
