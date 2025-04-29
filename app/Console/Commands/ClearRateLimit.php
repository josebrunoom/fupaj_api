<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearRateLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rate-limit:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all rate limiting cache';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Clearing rate limiting cache...');
        
        // Limpar cache do Laravel
        Cache::flush();
        $this->info('Laravel cache cleared.');
        
        // Limpar cache específico de rate limiting
        $keys = Cache::get('throttle:*');
        if ($keys) {
            foreach ($keys as $key) {
                Cache::forget($key);
            }
        }
        
        $this->info('Rate limiting cache cleared successfully.');
        
        return 0;
    }
} 