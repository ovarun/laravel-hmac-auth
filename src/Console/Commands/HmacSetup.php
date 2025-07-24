<?php
namespace Ovarun\HmacAuth\Console\Commands;

use Illuminate\Console\Command;

class HmacSetup extends Command
{
    protected $signature = 'hmac:setup';
    protected $description = 'Set up HMAC key and salt in .env';

    public function handle()
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            $this->error('.env file not found.');
            return;
        }

        $env = file_get_contents($envPath);

        $needsKey = !str_contains($env, 'HMAC_SECRET_GENERATOR_KEY=');
        $needsSalt = !str_contains($env, 'HMAC_SECRET_GENERATOR_SALT=');

        if (!$needsKey && !$needsSalt) {
            $this->info('✔ HMAC key and salt already exist.');
            return;
        }

        if ($this->confirm('Generate and add HMAC key and salt to .env?', true)) {
            $key = bin2hex(random_bytes(16));
            $salt = bin2hex(random_bytes(16));

            $env .= PHP_EOL;
            if ($needsKey) {
                $env .= "HMAC_SECRET_GENERATOR_KEY='{$key}'" . PHP_EOL;
            }
            if ($needsSalt) {
                $env .= "HMAC_SECRET_GENERATOR_SALT='{$salt}'" . PHP_EOL;
            }

            file_put_contents($envPath, $env);

            $this->info('✅ HMAC key and salt generated and added to .env');
        } else {
            $this->warn('Skipped HMAC key generation.');
        }
    }
}