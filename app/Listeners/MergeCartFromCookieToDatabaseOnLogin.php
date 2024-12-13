<?php

namespace App\Listeners;

use App\Helpers\CartManagementDatabase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;

class MergeCartFromCookieToDatabaseOnLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(object $event): void
    {
        $user = $event->user;
        CartManagementDatabase::mergeCartFromCookieToDatabase();
    }
}
