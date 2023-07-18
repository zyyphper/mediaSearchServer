<?php


namespace App\Models\Admin;


use App\Models\Admin\Enum\IsAdmin;
use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class PlatformMenu extends Menu
{
    protected $fillable = ['platform_id','parent_id', 'order', 'title', 'icon', 'uri', 'permission','is_admin'];

    /**
     * @return BelongsTo
     */
    public function platform() : BelongsTo
    {
        $platformModel = config('admin.database.platforms_model');
        return $this->belongsTo($platformModel,'platform_id','id');
    }

    /**
     * @return array
     */
    public function allNodes(): array
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        $orderColumn = DB::connection($connection)->getQueryGrammar()->wrap($this->orderColumn);

        $byOrder = 'ROOT ASC,'.$orderColumn;

        $query = static::query();

        if (config('admin.check_menu_roles') !== false) {
            $query->with('roles');
        }
        if (Admin::user()->platform_id === 0) {
            return $query->where('platform_id',Admin::user()->platform_id)->selectRaw('*, '.$orderColumn.' ROOT')->orderByRaw($byOrder)->get()->toArray();
        }

        return $query->where('platform_id',Admin::user()->platform_id)->orWhere('is_admin',IsAdmin::YES)
            ->selectRaw('*, '.$orderColumn.' ROOT')
            ->orderByRaw($byOrder)
            ->get()->toArray();
    }
}
