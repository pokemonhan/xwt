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

        $this->secretkey = configure('secretkey');
        $this->apiUrl    = configure('apiUrl');
        $this->username  = configure('username');

        parent::__construct();
    }
}
