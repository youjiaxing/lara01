<?php

namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  QueryExecuted  $event
     * @return void
     */
    public function handle(QueryExecuted $event)
    {
        $sql = vsprintf(str_replace('?', '%s', $event->sql), $event->bindings);
        \Log::info(sprintf("[%s][%s] %s", $event->connectionName, $this->getTimeStr($event->time), $sql));
    }

    protected function getTimeStr($milliseconds)
    {
        $milliseconds /= 1000;
        return number_format($milliseconds, 3, '.', '') . "s";
    }
}
