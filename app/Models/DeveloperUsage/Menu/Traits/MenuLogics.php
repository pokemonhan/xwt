<?php

namespace App\Models\DeveloperUsage\Menu\Traits;

use App\Models\Admin\BackendAdminAccessGroup;
use Illuminate\Support\Facades\Cache;

/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/23/2019
 * Time: 10:02 PM
 */
trait MenuLogics
{
    /**
     * @param  BackendAdminAccessGroup  $accessGroupEloq
     * @return array
     * TODO : 由于快速开发 后续需要弄缓存与异常处理
     */
    public function menuLists(BackendAdminAccessGroup $accessGroupEloq): array
    {
        $parent_menu = [];
        $role = $accessGroupEloq->role;
        if ($role == '*') {
            $parent_menu = $this->forStar();
        } else {
            $parent_menu = $this->getUserMenuDatas($accessGroupEloq);
        }
        return $parent_menu;
    }

    /**
     * @return array
     */
    public function forStar(): array
    {
        $redisKey = '*';
        if (Cache::tags([$this->redisFirstTag])->has($redisKey)) {
            $parent_menu = Cache::tags([$this->redisFirstTag])->get($redisKey);
        } else {
            $parent_menu = self::createMenuDatas();
        }
        return $parent_menu;
    }

    /**
     * @param  BackendAdminAccessGroup  $accessGroupEloq
     * @return array|mixed
     */
    public function getUserMenuDatas(BackendAdminAccessGroup $accessGroupEloq)
    {
        $redisKey = $accessGroupEloq->id;
        if (Cache::tags([$this->redisFirstTag])->has($redisKey)) {
            $parent_menu = Cache::tags([$this->redisFirstTag])->get($redisKey);
        } else {
            $role = json_decode($accessGroupEloq->role); //[1,2,3,4,5]
            $menuLists = self::whereIn('id', $role)->get();
            $parent_menu = self::createMenuDatas($accessGroupEloq->id, $role);
        }
        return $parent_menu;
    }

    /**
     * @param  string  $redisKey
     * @param  mixed   $role
     * @return array
     */
    public function createMenuDatas($redisKey = '*', $role = '*'): array
    {
        $menuForFE = [];
        $menuLists = self::getFirstLevelList($role);
        foreach ($menuLists as $key => $firstMenu) {
            if ($firstMenu->pid === 0) {
                $menuForFE[$firstMenu->id] = $firstMenu->toArray();
                if ($firstMenu->childs()->exists()) {
                    $firstChilds = $role == '*' ?
                    $firstMenu->childs->sortBy('sort') :
                    $firstMenu->childs->whereIn('id', $role)->sortBy('sort');
                    foreach ($firstChilds as $secondMenu) {
                        $menuForFE[$firstMenu->id]['child'][$secondMenu->id] = $secondMenu->toArray();
                        if ($secondMenu->childs()->exists()) {
                            $secondChilds = $role == '*' ?
                            $secondMenu->childs->sortBy('sort') :
                            $secondMenu->childs->whereIn('id', $role)->sortBy('sort');
                            foreach ($secondChilds as $thirdMenu) {
                                $menuForFE[$firstMenu->id]['child']
                                [$secondMenu->id]['child'][$thirdMenu->id] = $thirdMenu->toArray();
                            }
                        }
                    }
                }
            }
        }
        //            $hourToStore = 24;
        //            $expiresAt = Carbon::now()->addHours($hourToStore)->diffInMinutes();
        //            Cache::put('ms_menus', $parent_menu, $expiresAt);
        Cache::tags([$this->redisFirstTag])->forever($redisKey, $menuForFE);
//        Cache::forever($redisKey, $menuForFE);
        return $menuForFE;
    }

    /**
     * @return bool
     */
    public function refreshStar(): bool
    {
        Cache::tags([$this->redisFirstTag])->flush();
        return true;
    }

    /**
     * @param  string  $role
     * @return mixed
     */
    public static function getFirstLevelList($role = '*')
    {
        if ($role == '*') {
            return self::where('pid', 0)->orderBy('sort')->get();
        } else {
            return self::where('pid', 0)
                ->whereIn('id', $role)
                ->orderBy('sort')->get();
        }
    }

    /**
     * [changeParent description]
     * @param  array $parseDatas
     * @return array $itemProcess
     */
    public function changeParent($parseDatas): array
    {
        $atLeastOne = false;
        $itemProcess = [];
        foreach ($parseDatas as $key => $value) {
            $menuEloq = self::find($value['currentId']);
            $menuEloq->pid = $value['currentParent'] === '#' ? 0 : (int) $value['currentParent'];
            $menuEloq->sort = $value['currentSort'];
            if ($menuEloq->save()) {
                $pass['pass'] = $value['currentText'];
                $itemProcess[] = $pass;
                $atLeastOne = true;
            } else {
                $fail['fail'] = $value['currentText'];
                $itemProcess[] = $fail;
            }
        }
        if ($atLeastOne === true && isset($menuEloq)) {
            $menuEloq->refreshStar();
        }
        return $itemProcess;
    }
}
