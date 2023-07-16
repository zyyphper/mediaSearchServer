<?php


namespace App\Models\Material;


use App\Libraries\Base\BaseModel;
use App\Models\System\Platform;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileTemplates extends BaseModel
{

    protected $connection = "business";

    protected $fillable = [
        'platform_id',
        'name',
        'file_type',
        'status',
    ];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }


}
