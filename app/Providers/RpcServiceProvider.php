<?php

namespace App\Providers;

use App\Services\Rpc\RpcService;
use App\Services\File\PdfService;
use Illuminate\Support\ServiceProvider;

/**
 * 远程调用服务提供者
 * Class RpcServiceProvider
 * @package App\Providers
 */
class RpcServiceProvider extends ServiceProvider
{
    public $registerGroup = [
        PdfService::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->registerGroup as $class) {
            //非注册过的服务
            if (!app('config')->has('comm.'.$class)) {
                $this->app->bind($class,function() use ($class) {
                   return new $class;
                });
                continue;
            }
            //注册过的服务
            $config = config('comm.'.$class);
            $this->app->bind($class,function() use ($class,$config) {
                return (new RpcService())->getClient($class,$config);
            });
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
