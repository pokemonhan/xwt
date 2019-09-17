<?php namespace App\Console\Commands;


abstract class Command extends \Illuminate\Console\Command
{
    public function __construct()
    {
        parent::__construct();
    }
}
