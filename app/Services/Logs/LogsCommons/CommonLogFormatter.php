<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 3/27/19
 * Time: 9:59 AM
 */

namespace App\Services\Logs\LogsCommons;

use Illuminate\Support\Str;
use Monolog\Formatter\NormalizerFormatter;

class CommonLogFormatter extends NormalizerFormatter
{
    /**
     * type
     */
    public const LOG = 'log';
    public const STORE = 'store';
    public const CHANGE = 'change';
    public const DELETE = 'delete';
    /**
     * result
     */
    public const SUCCESS = 'success';
    public const NEUTRAL = 'neutral';
    public const FAILURE = 'failure';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        $record = parent::format($record);
        return $this->getDocument($record);
    }

    /**
     * Convert a log message into an MariaDB Log entity
     * @param array $record
     * @return array
     */
    protected function getDocument(array $record)
    {
        $fills = $record['extra'];
        $fills['level'] = Str::lower($record['level_name']);
        $fills['description'] = $record['message'];
        $fills['token'] = str_random(30);
        $context = $record['context'];
        if (!empty($context)) {
            $fills['type'] = array_has($context, 'type') ? $context['type'] : self::LOG;
            $fills['result'] = array_has($context, 'result') ? $context['result'] : self::NEUTRAL;
            $fills = array_merge($record['context'], $fills);
        }
        return $fills;
    }
}
