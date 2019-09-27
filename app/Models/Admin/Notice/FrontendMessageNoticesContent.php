<?php

namespace App\Models\Admin\Notice;

use App\Models\BaseModel;

/**
 * Class FrontendMessageNoticesContent
 * @package App\Models\Admin\Notice
 */
class FrontendMessageNoticesContent extends BaseModel
{
    public const TYPE_NOTICE = 1;
    public const TYPE_MESSAGE = 2;
    public const STATUS_OPEN = 1;
    public const STATUS_CLOSE = 0;
    public const TOP_OPEN = 1; //开启置顶
    public const TOP_CLOSE = 0; //关闭置顶
    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];

    // public function getPicPathAttribute()
    // {
    //     return Request::server("HTTP_HOST") . $this->attributes['pic_path'];
    // }

    /**
     * @return mixed
     */
    public function receiveUserList()
    {
        return $this->hasMany(FrontendMessageNotice::class, 'notices_content_id', 'id');
    }

    /**
     * @return mixed
     */
    public function delete()
    {
        $this->receiveUserList()->delete();
        return parent::delete();
    }
}
