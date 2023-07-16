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

    $router->group(['prefix' => 'admin','namespace' => 'Admin'], function () use ($router) {
        $router->resource('auth/users', 'UserController')->names('admin.auth.users');
    });

    // 媒资信息模块
    $router->group(['prefix' => 'material','namespace' => 'Material'], function () use ($router) {
//        $router->group(['prefix' => 'source'] ,function ($router) {
//            // 媒资模板文件导出
//            $router->get('tpl_export', 'SourceController@tplExport');
//            // 媒资文件导入页面
//            $router->match(['get', 'post'], 'import_page', 'SourceController@importPage')->name('material_sources_import_page');
//            // 媒资数据导入
//            $router->post('import', 'SourceController@import')->name('material_sources_import');
//            // 媒资文件导出
//            $router->get('export', 'SourceController@export')->name('material_sources_export');
//            // 全量媒资文件导出 TODO 暂时使用，后期优化
//            $router->get('all_export', 'SourceController@allExport');
//        });
        // 媒资
        $router->group(['prefix' => 'file','namespace' => 'File'], function () use ($router) {
            $router->resource('templates', 'TemplatesController');
        });

//        // 节目集
//        $router->resource('album', 'AlbumController');
//        // 标签
//        $router->resource('label', 'LabelController');
//        // 版权
//        $router->resource('copyright', 'CopyrightController');
    });

});
