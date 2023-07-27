<?php


namespace App\Services\Common;


use App\Libraries\Base\BaseService;
use App\Models\Material\FileGroup;

class PlatformService extends BaseService
{
    public function getFileGroups()
    {
        return FileGroup::where('platform_id',$this->platformId)->pluck('name','id');
    }
}
