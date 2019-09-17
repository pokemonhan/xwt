<?php

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\Game\Lottery\Logics\MethodsLogics;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class LotteryMethod extends BaseModel
{
    use MethodsLogics;

    public const STATUS_OPEN = 1;
    public const STATUS_CLOSE = 0;
    
    protected $guarded = ['id'];

    public function methodRows()
    {
        return $this->hasMany(__CLASS__, 'method_group', 'method_group')->select([
            'method_row',
            'status',
            'lottery_id'
        ])->groupBy('method_row');
    }

    public function methodDetails()
    {
        return $this->hasMany(__CLASS__, 'lottery_id', 'lottery_id')->select([
            'id',
            'lottery_name',
            'lottery_id',
            'method_group',
            'method_id',
            'method_row',
            'method_name',
            'status',
            'created_at',
            'updated_at',
        ]);
    }

    public function methoudValidationRule()
    {
        return $this->hasOne(LotteryMethodsValidation::class, 'method_id', 'method_id');
    }

    public function numberButtonRule(): HasOneThrough
    {
        return $this->hasOneThrough(
            LotteryMethodsNumberButtonRule::class,
            LotteryMethodsValidation::class,
            'method_id',
            'id',
            'method_id',
            'button_id'
        );
    }

    public function methodLayout()
    {
        return $this->hasManyThrough(
            LotteryMethodsLayout::class,
            LotteryMethodsValidation::class,
            'method_id',
            'validation_id',
            'method_id',
            'id'
        );
    }
}
