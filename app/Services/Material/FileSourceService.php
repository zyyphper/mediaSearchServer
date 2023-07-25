<?php


namespace App\Services\Material;


use App\Helpers\Tools;
use App\Libraries\Base\BaseService;
use App\Models\Material\Enums\FileOriginType;
use App\Models\Material\Enums\FileType;
use App\Models\Material\FileSource;
use Encore\Admin\Facades\Admin;

class FileSourceService extends BaseService
{
    /**
     * @var FileSource
     */
    protected $model;

    public function importData(string $url,array $groupIds)
    {
        //根据路径获取文件类型和名称
        $info = Tools::getFileInfo($url);
        $model = $this->model::create([
            'id' => app('snowFlake')->id,
            'platform_id' => $this->platformId,
            'name' => $info['filename'],
            'original_url' => $url,
            'file_type' => FileType::$extensionMap[$info['extension']],
            'origin_type' => FileOriginType::IMPORT,
            'operator' => Admin::user()->id
        ]);
        $model->groups()->sync($groupIds);
    }
}
