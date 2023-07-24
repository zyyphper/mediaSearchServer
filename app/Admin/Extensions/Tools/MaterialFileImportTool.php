<?php

namespace App\Admin\Extensions\Tools;

use App\Libraries\Base\Platform;
use App\Models\Admin\Platform as PlatformModel;
use App\Models\Material\FileGroup;
use Encore\Admin\Actions\Action;

class MaterialFileImportTool extends Action
{
    use Platform;
    public $name = '导入媒资数据';

    protected $selector = '.import-post';

    public function form()
    {
        if ($this->isRootPlatform()) {
            $this->select('platform_id', '平台')->options(function () {
                return PlatformModel::pluck('name', 'id');
            })->required();
        }
        $this->multipleSelect('group_id', '分组')->options(function () {
            return FileGroup::pluck('name', 'id');
        })->required();
    }

    public function render()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default import-post" style="margin-right: 10px"><i class="fa fa-upload"></i>文件导入</a>
HTML;
    }
}
