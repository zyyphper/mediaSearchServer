<?php


namespace App\Admin\Middleware;


use App\Models\Admin\PlatformOperationLog;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Middleware\LogOperation;
use Illuminate\Http\Request;

class PlatformLogOperation extends LogOperation
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if ($this->shouldLogOperation($request)) {
            $log = [
                'platform_id' => Admin::user()->platform_id,
                'user_id' => Admin::user()->id,
                'path'    => substr($request->path(), 0, 255),
                'method'  => $request->method(),
                'ip'      => $request->getClientIp(),
                'input'   => json_encode($request->input()),
            ];

            try {
                PlatformOperationLog::create($log);
            } catch (\Exception $exception) {
                // pass
            }
        }

        return $next($request);
    }
}
