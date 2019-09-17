<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 3/27/19
 * Time: 9:51 AM
 */

namespace App\Services\Logs\FrontendLogs;

use App\Models\Admin\BackendSystemLog;
use App\Models\DeveloperUsage\Backend\BackendAdminRoute;
use Jenssegers\Agent\Agent;

class FrontendLogProcessor
{

    public function __invoke(array $record)
    {
        $agent = new Agent();
        $os = $agent->platform();
        $osVersion = $agent->version($os);
        $browser = $agent->browser();
        $bsVersion = $agent->version($browser);
        $robot = $agent->robot();
        if ($agent->isRobot()) {
            $type = BackendSystemLog::ROBOT;
        } elseif ($agent->isDesktop()) {
            $type = BackendSystemLog::DESKSTOP;
        } elseif ($agent->isTablet()) {
            $type = BackendSystemLog::TABLET;
        } elseif ($agent->isMobile()) {
            $type = BackendSystemLog::MOBILE;
        } elseif ($agent->isPhone()) {
            $type = BackendSystemLog::PHONE;
        } else {
            $type = BackendSystemLog::OTHER;
        }
        $messageArr = json_decode($record['message'], true);
        $userId = auth()->user() ? auth()->user()->id : null;
        $record['extra'] = [
            'user_id' => $userId,
            'username' => $userId !== null ? auth()->user()->username : null,
            'origin' => request()->headers->get('origin'),
            'ip' => request()->ip(),
            'ips' => json_encode(request()->ips()),
            'user_agent' => request()->server('HTTP_USER_AGENT'),
            'lang' => json_encode($agent->languages()),
            'device' => $agent->device(),
            'os' => $os,
            'browser' => $browser,
            'bs_version' => $bsVersion,
            'device_type' => $type,
            'log_uuid' => $messageArr['log_uuid'],
        ];
        if ($osVersion) {
            $record['extra']['os_version'] = $osVersion;
        }
        if ($robot) {
            $record['extra']['robot'] = $robot;
        }
        if (isset($messageArr['input'])) {
            $record['extra']['inputs'] = json_encode($messageArr['input']);
        }
        /*if (isset($messageArr['route'])) {
        $record['extra']['route'] = json_encode($messageArr['route']);
        $routeEloq = BackendAdminRoute::where('route_name', $messageArr['route']['action']['as'])->first();
        if ($routeEloq !== null) {
        $record['extra']['route_id'] = $routeEloq->id;
        $record['extra']['menu_id'] = $routeEloq->menu->id ?? null;
        $record['extra']['menu_label'] = $routeEloq->menu->label ?? null;
        $record['extra']['menu_path'] = $routeEloq->menu->route ?? null;
        }
        $record['message'] = '网络操作信息';
        }*/
        return $record;
    }
}
