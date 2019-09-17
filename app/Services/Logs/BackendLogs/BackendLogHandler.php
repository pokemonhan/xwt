<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 3/27/19
 * Time: 9:48 AM
 */

namespace App\Services\Logs\BackendLogs;

use App\Services\Logs\LogsCommons\CommonLogFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class BackendLogHandler extends AbstractProcessingHandler
{
    public function __construct($level = Logger::DEBUG)
    {
        parent::__construct($level);
    }

    protected function write(array $record)
    {
        // Queue implementation
        event(new BackendLogMonologEvent($record));
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new CommonLogFormatter();
    }
}
