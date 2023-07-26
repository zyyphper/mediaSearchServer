<?php

namespace App\Admin\Actions;

use App\Helpers\Tools;
use App\Libraries\Base\Platform;
use App\Models\Material\FileGroup;
use Encore\Admin\Actions\Action;
use Encore\Admin\Facades\Admin;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialImport extends Action
{
    use Platform;

    public function __construct($service)
    {
        $this->service = $service;
        parent::__construct();
    }

    protected $service = null;
    protected $selector = '.material-import';

    public function handle(Request $request)
    {
        $file = $request->file('file');
        return $this->response()->error(var_dump($file))->refresh();
        $platformId = $request->get('platform_id') ?? Admin::user()->platform_id;
        $groupIds = $request->get('group_ids');
        $this->service->setPlatform($platformId);
        DB::beginTransaction();
        try {
            $this->service->importData($path,$groupIds);
            DB::commit();
        }catch (QueryException $exception) {
            DB::rollBack();
            Tools::logError($exception,"媒资导入");
            return $this->response()->error("导入失败!")->refresh();
        }
        return $this->response()->success("导入成功！")->refresh();
    }

    public function form()
    {
        if ($this->isRootPlatform()) {
            $this->select('platform_id', '平台')->options(function () {
                return $this->getUserPlatform();
            })->required();
        }
        $this->multipleSelect('group_ids', '分组')->options(function () {
            return FileGroup::pluck('name', 'id');
        })->required();
        $this->file("file");
//        $this->multipleFile('files'); //不给用
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default material-import">导入数据</a>
HTML;
    }
}
