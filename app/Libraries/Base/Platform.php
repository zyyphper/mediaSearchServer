<?php


namespace App\Libraries\Base;

use App\Models\Admin\Enums\IsAdmin;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use App\Models\Admin\Platform AS model;

trait Platform
{
    public static function __callStatic($name, $arguments)
    {
        return (new self())->$name($arguments);
    }

    public function platformAuth(Grid &$grid)
    {
        $platformId = Admin::user()->platform_id;
        if ($this->isRootPlatform()) {
            //超平台系统管理员 无需限制
            $grid->platform()->name("平台");
            return;
        }
        $grid->model()->where('platform_id',$platformId);
    }

    public function isRootPlatform()
    {
        $platformModel = model::find(Admin::user()->platform_id);
        return $platformModel->is_admin !== 0;
    }

    public function getUserPlatform()
    {
        return model::where('is_admin',IsAdmin::NO)->pluck('name','id');
    }
}
