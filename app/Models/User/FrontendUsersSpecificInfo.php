<?php

namespace App\Models\User;

use App\Models\BaseModel;

/**
 * Class FrontendUsersSpecificInfo
 * @package App\Models\User
 */
class FrontendUsersSpecificInfo extends BaseModel
{
    /**
     * 注册类型：0.普通注册1.人工开户注册2.链接开户注册3.扫码开户注册4.后台创建
     */
    public const REGISTER_TYPE_BACKEND_CREATE = 4;
    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];
}
