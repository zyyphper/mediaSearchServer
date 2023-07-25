<?php

namespace App\Admin\Actions;

use App\Libraries\Base\Platform;
use App\Models\Admin\Platform as PlatformModel;
use App\Models\Material\FileGroup;
use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;

class MaterialImport extends Action
{
    use Platform;
    protected $selector = '.material-import';

    public function handle(Request $request)
    {
        // $request ...

        return $this->response()->success('Success message...')->refresh();
    }

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
        $this->date('start_time', '开始时间');
        $this->date('end_time', '结束时间');
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default material-import">导入数据</a>
HTML;
    }
}
