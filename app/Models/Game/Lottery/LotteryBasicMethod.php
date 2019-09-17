<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/19/2019
 * Time: 5:52 PM
 */

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel;
use App\Models\Game\Lottery\Logics\LotteryBasicMethodLogics;
use App\models\Game\Lottery\Logics\SeriesLogic\Prizes\K3Prize;
use App\models\Game\Lottery\Logics\SeriesLogic\Prizes\LottoPrize;
use App\models\Game\Lottery\Logics\SeriesLogic\Prizes\P3p5Prize;
use App\models\Game\Lottery\Logics\SeriesLogic\Prizes\Pk10Prize;
use App\models\Game\Lottery\Logics\SeriesLogic\Prizes\SdPrize;
use App\models\Game\Lottery\Logics\SeriesLogic\Prizes\SscPrize;
use App\models\Game\Lottery\Logics\SeriesLogic\Prizes\SslPrize;
use App\models\Game\Lottery\Logics\SeriesLogic\WinningNumber\K3BM;
use App\models\Game\Lottery\Logics\SeriesLogic\WinningNumber\LottoBM;
use App\models\Game\Lottery\Logics\SeriesLogic\WinningNumber\P3p5BM;
use App\models\Game\Lottery\Logics\SeriesLogic\WinningNumber\Pk10BM;
use App\models\Game\Lottery\Logics\SeriesLogic\WinningNumber\SdBM;
use App\models\Game\Lottery\Logics\SeriesLogic\WinningNumber\SscBM;
use App\models\Game\Lottery\Logics\SeriesLogic\WinningNumber\SslBM;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LotteryBasicMethod extends BaseModel
{
    use LotteryBasicMethodLogics;
    use SscBM,LottoBM,K3BM,SdBM,P3p5BM,SslBM,Pk10BM;
    use SscPrize,LottoPrize,K3Prize,SdPrize,P3p5Prize,SslPrize,Pk10Prize;

    protected $guarded = ['id'];

    public function prizeLevel(): HasMany
    {
        return $this->hasMany(LotteryMethodsWaysLevel::class, 'basic_method_id', 'id');
    }
}
