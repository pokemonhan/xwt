<?php
namespace App\Http\Controllers\BackendApi\Casino;

use App\Http\Controllers\BackendApi\BackEndApiMainController;

/**
 * Class CasinoApiMainController
 * @package App\Http\Controllers\BackendApi\Casino
 */
class CasinoApiMainController extends BackEndApiMainController
{
    /**
     * @var string
     */
    public $secretkey   = '';
    /**
     * @var string
     */
    public $apiUrl      = '';
    /**
     * @var string
     */
    public $username    = '';

    /**
     * CasinoApiMainController constructor.
     */
    public function __construct()
    {
        $this->secretkey = config('casino.game_img_url');
        $this->secretkey = config('casino.secret_key');
        $this->secretkey = config('casino.username');
    }
}
