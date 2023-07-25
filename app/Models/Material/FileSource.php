<?php


namespace App\Models\Material;


use App\Libraries\Base\BaseModel;
use App\Models\Admin\Platform;
use App\Models\Admin\PlatformUser;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileSource extends BaseModel
{
    use SoftDeletes;
    protected $connection = "business";

    protected $fillable = [
        'id',
        'platform_id',
        'name',
        'file_type',
        'origin_type',
        'origin_tpl_id',
        'operator',
        'original_url'
    ];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function operator()
    {
        return $this->belongsTo(PlatformUser::class);
    }

    public function groups()
    {
        return $this->morphMany(FileGroup::class,"groupable");
    }

    public function template()
    {
        return $this->belongsTo(FileTemplate::class,'origin_tpl_id');
    }

}
