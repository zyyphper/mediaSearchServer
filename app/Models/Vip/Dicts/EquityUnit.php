<?php


namespace App\Models\Vip\Dicts;



use App\Libraries\Base\BaseDict;
use App\Models\Vip\VipDict;

class EquityUnit extends BaseDict
{
    protected string $model = VipDict::class;

    const SPACE_CAPACITY = 1010;
    const FILE_SEARCH = 1011;
    const TYPE_CONVERT = 1011;
}
