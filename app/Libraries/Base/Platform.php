<?php


namespace App\Libraries\Base;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;

trait Platform
{
    public function platformAuth(Grid &$grid)
    {
        $platformId = Admin::user()->platform_id;
        if ($platformId === 0) {
            //超平台系统管理员 无需限制
            return;
        }
        $grid->model()->where('platform_id',$platformId);
    }
}
