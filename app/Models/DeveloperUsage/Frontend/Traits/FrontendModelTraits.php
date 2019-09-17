<?php

namespace App\Models\DeveloperUsage\Frontend\Traits;

use App\Lib\BaseCache;
use Illuminate\Support\Facades\Cache;

trait FrontendModelTraits
{
    use BaseCache;

    /**
     * @param  int    $type
     * @return array
     */
    public function allFrontendModel($type): array
    {
        $typeArr = [];
        if ($type === 2) {
            $typeArr = [1, 2];
        } elseif ($type === 3) {
            $typeArr = [1, 3];
        }
        $parentFrontendModel = self::parentModel($typeArr);
        $frontendModelList = [];
        foreach ($parentFrontendModel as $id => $frontendModel) {
            $frontendModelList[$id] = $frontendModel;
            $frontendModelList[$id]['childs'] = $frontendModel->childs;
            foreach ($frontendModelList[$id]['childs'] as $grandsonId => $grandsonFrontendModel) {
                $frontendModelList[$id]['childs'][$grandsonId] = $grandsonFrontendModel;
                $frontendModelList[$id]['childs'][$grandsonId]['childs'] = $grandsonFrontendModel->childs;
            }
        }
        return $frontendModelList;
    }

    //获取首页基本内容
    public static function getWebBasicContent($update = false)
    {
        if ($update === false) {
            $data = self::getTagsCacheData('web_basic_content');
        } else {
            $data = [];
        }
        if (empty($data)) {
            $data['ico'] = self::getModuleValue('frontend.ico', 'value');
            $data['logo'] = self::getModuleValue('logo', 'value');
            $data['qrcode'] = self::getModuleValue('qr.code', 'value');
            $data['customer_service'] = self::getModuleValue('customer.service', 'value');
            self::saveTagsCacheData('web_basic_content', $data);
        }
        return $data;
    }

    /**
     * 获取一个模块指定的字段值
     * @param  string $en_name 模块英文名
     * @return mixed
     */
    public static function getModuleValue($en_name, $field)
    {
        return self::where('en_name', $en_name)->value($field);
    }

    /**
     * @param  array    $typeArr
     */
    public function parentModel($typeArr)
    {
        return self::where('level', 1)->whereIn('type', $typeArr)->get();
    }

    /**
     * 获取一个模块信息
     * @param  string $en_name 模块英文名
     * @return mixed
     */
    public function getModuleEloq($en_name)
    {
        return self::where('en_name', $en_name)->first();
    }

    //首页需要展示的模块缓存
    public static function showModelCache()
    {
        $homepageModel = self::select('en_name', 'status')->where('is_homepage_display', 1)->get();
        $data = [];
        foreach ($homepageModel as $value) {
            $data[$value->en_name] = $value->status;
        }
        Cache::forever('showModel', $data);
        return $data;
    }

    //生成 开奖公告 前台模块
    public static function createLotteryNotice()
    {
        $parentEloq = self::where('en_name', 'page.model')->first();
        if ($parentEloq === null) {
            $parentEloq = self::createPageModel();
        }
        $frontendModuleEloq = new self();
        $addData = [
            'label' => '开奖公告',
            'en_name' => 'lottery.notice',
            'pid' => $parentEloq->id,
            'type' => 1,
            'show_num' => 4,
            'status' => 1,
            'level' => ++$parentEloq->level,
            'is_homepage_display' => 1,
        ];
        $frontendModuleEloq->fill($addData);
        $frontendModuleEloq->save();
        return $frontendModuleEloq;
    }

    //生成 手机端开奖公告 前台模块
    public static function createMobileLotteryNotice()
    {
        $parentEloq = self::where('en_name', 'page.model')->first();
        if ($parentEloq === null) {
            $parentEloq = self::createPageModel();
        }
        $frontendModuleEloq = new self();
        $addData = [
            'label' => '手机端开奖公告',
            'en_name' => 'mobile.lottery.notice',
            'pid' => $parentEloq->id,
            'type' => 1,
            'show_num' => 4,
            'status' => 1,
            'level' => ++$parentEloq->level,
            'is_homepage_display' => 1,
        ];
        $frontendModuleEloq->fill($addData);
        $frontendModuleEloq->save();
        return $frontendModuleEloq;
    }

    //生成 主题板块 前台模块
    public static function createPageModel()
    {
        $parentEloq = self::where('en_name', 'homepage')->first();
        if ($parentEloq === null) {
            $parentEloq = self::createHomepage();
        }
        $frontendModuleEloq = new self();
        $addData = [
            'label' => '主题板块',
            'en_name' => 'page.model',
            'pid' => $parentEloq->id,
            'type' => 1,
            'status' => 1,
            'level' => ++$parentEloq->level,
        ];
        $frontendModuleEloq->fill($addData);
        $frontendModuleEloq->save();
        return $frontendModuleEloq;
    }

    //生成 首页 前台模块
    public static function createHomepage()
    {
        $frontendModuleEloq = new self();
        $addData = [
            'label' => '首页',
            'en_name' => 'homepage',
            'pid' => 0,
            'type' => 1,
            'status' => 1,
            'level' => 1,
        ];
        $frontendModuleEloq->fill($addData);
        $frontendModuleEloq->save();
        return $frontendModuleEloq;
    }

    //生成 app端热门彩票 前台模块
    public static function createMobilePopularLotteries()
    {
        $parentEloq = self::where('en_name', 'page.model')->first();
        if ($parentEloq === null) {
            $parentEloq = self::createPageModel();
        }
        $frontendModuleEloq = new self();
        $addData = [
            'label' => 'app端热门彩种一',
            'en_name' => 'mobile.popular.lotteries.one',
            'pid' => $parentEloq->id,
            'type' => 1,
            'show_num' => 10,
            'status' => 1,
            'level' => ++$parentEloq->level,
            'is_homepage_display' => 1,
        ];
        $frontendModuleEloq->fill($addData);
        $frontendModuleEloq->save();
        return $frontendModuleEloq;
    }

    //生成 web端热门彩票 前台模块
    public static function createPopularLotteries()
    {
        $parentEloq = self::where('en_name', 'page.model')->first();
        if ($parentEloq === null) {
            $parentEloq = self::createPageModel();
        }
        $frontendModuleEloq = new self();
        $addData = [
            'label' => 'web端热门彩种一',
            'en_name' => 'popular.lotteries.one',
            'pid' => $parentEloq->id,
            'type' => 1,
            'show_num' => 10,
            'status' => 1,
            'level' => ++$parentEloq->level,
            'is_homepage_display' => 1,
        ];
        $frontendModuleEloq->fill($addData);
        $frontendModuleEloq->save();
        return $frontendModuleEloq;
    }
}
