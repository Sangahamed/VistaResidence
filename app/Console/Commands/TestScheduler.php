<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestScheduler extends Command
{
    protected $signature = 'test:scheduler';

    protected $description = 'Test si le scheduler fonctionne';

    public function handle()
    {
        Log::info("✅ Le scheduler fonctionne ! " . now());
    }
}
