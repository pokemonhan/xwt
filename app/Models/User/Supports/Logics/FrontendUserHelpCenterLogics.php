<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 7/3/2019
 * Time: 8:56 PM
 */

namespace App\Models\User\Supports\Logics;

trait FrontendUserHelpCenterLogics
{
    /**
     * 获取用户帮助中心数据
     * @return  array
     */
    public function getHelpCenterData(): array
    {
        $helpELoqs = $this->where('pid', 0)->get();
        foreach ($helpELoqs as $key => $item) {
            $helpELoqs[$key]['children'] = $item->children;
        }
        return $helpELoqs->toArray();
    }
}
