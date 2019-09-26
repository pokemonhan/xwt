<?php
namespace App\Http\Controllers\CasinoApi;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;

/**
 * Class CasinoApiMainController
 * @package App\Http\Controllers\CasinoApi
 */
class CasinoApiMainController extends FrontendApiMainController
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
     * AdminMainController constructor.
     */
    public function __construct()
    {
        $this->secretkey = config('casino.game_img_url');
        $this->secretkey = config('casino.secret_key');
        $this->secretkey = config('casino.username');

        parent::__construct();
    }
}
