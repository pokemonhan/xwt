<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class AdminMainController extends Controller
{
    protected $inputs;
    protected $partnerAdmin;
    protected $currentOptRoute;

    /**
     * AdminMainController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->partnerAdmin = Auth::guard('web')->user();
            $this->inputs = Input::all();
            $this->currentOptRoute = Route::getCurrentRoute();
            $this->adminOperateLog();
            $menuObj = new BackendSystemMenu();
            $this->currentPartnerAccessGroup = $this->partnerAdmin->accessGroup;
            $menulists = $menuObj->menuLists($this->currentPartnerAccessGroup);
            View::share('menulists', $menulists);
            return $next($request);
        });
    }

    /**
     *记录后台管理员操作日志
     */
    private function adminOperateLog(): void
    {
        $datas['input'] = $this->inputs;
        $datas['route'] = $this->currentOptRoute;
        $log = json_encode($datas, JSON_UNESCAPED_UNICODE);
        Log::channel('byqueue')->info($log);
//        Log::channel('operate')->debug($log);
        //        Log::stack(['operate','graylog'])->debug($log);
    }
}
