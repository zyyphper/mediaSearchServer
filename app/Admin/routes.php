<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->group(['prefix' => 'auth','namespace' => 'Admin'], function () use ($router) {
        $router->resource('platforms', 'PlatformController');
        $router->resource('users', 'PlatformUserController');
        $router->resource('roles', 'PlatformRoleController');
        $router->resource('menu', 'PlatformMenuController');
        $router->resource('logs', 'PlatformLogController');
    });

    // 媒资信息模块
    $router->group(['prefix' => 'material','namespace' => 'Material'], function () use ($router) {
        //文件
        $router->group(['prefix' => 'file','namespace' => 'File'], function () use ($router) {
            //文件分组
            $router->resource('groups', 'GroupController');
            //文件资源
            $router->resource('sources','SourceController');
            //文件模板
            $router->group(['prefix' => 'templates'],function() use ($router) {
                $router->resource('/', 'TemplateController');
                $router->match(['get', 'post'], 'import_page', 'TemplateController@importPage')->name('material_file_templates_import_page');
                // 模板导入
                $router->post('import', 'TemplateController@import')->name('material_file_templates_import');

            });

        });
    });
    //会员服务
    $router->group(['prefix' => 'vip','namespace' => 'Vip'],function () use ($router) {
        //等级
        $router->resource('levels','LevelController');
    });

});
