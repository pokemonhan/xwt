<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 3/20/19
 * Time: 6:15 PM
 */

namespace App\Services;

use Gelf\Publisher;
use Gelf\Transport\UdpTransport;

class GraylogSetup
{
    public static function getGelfPublisher(): Publisher
    {
        $transport = new UdpTransport(config('graylog.host'), config('graylog.port'), UdpTransport::CHUNK_SIZE_LAN);
        $publisher = new Publisher();
        $publisher->addTransport($transport);
        return $publisher;
    }
}
