<?php

namespace App\Listeners;

use App\Events\UserActivityLogged;
use App\Services\ActivityAnalyzer;

class AnalyzeUserActivity
{
    public function __construct(
        protected ActivityAnalyzer $analyzer
    ) {}

    public function handle(UserActivityLogged $event): void
    {
        $this->analyzer->analyze($event->activity);
    }
}
