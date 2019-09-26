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
        $this->secretkey = configure('secretkey');
        $this->apiUrl    = configure('apiUrl');
        $this->username  = configure('username');
    }
}
