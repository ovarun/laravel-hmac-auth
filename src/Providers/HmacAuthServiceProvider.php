<?php

namespace Ovarun\HmacAuth\Providers;

use Illuminate\Support\ServiceProvider;

class HmacAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/hmac.php' => config_path('hmac.php'),
            __DIR__ . '/../../database/migrations/create_hmac_clients_table.php.stub' =>
            database_path('migrations/' . date('Y_m_d_His') . '_create_hmac_clients_table.php'),
        ], 'hmac-auth');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        //$this->generateKeyAndSaltIfNotSet();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/hmac.php', 'hmac');

        $this->commands([
            \Ovarun\HmacAuth\Console\Commands\HmacSetup::class,
        ]);
    }

    protected function generateKeyAndSaltIfNotSet()
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            return;
        }

        $env = file_get_contents($envPath);

        $needsKey = !str_contains($env, 'HMAC_SECRET_GENERATOR_KEY=');
        $needsSalt = !str_contains($env, 'HMAC_SECRET_GENERATOR_SALT=');

        if ($needsKey || $needsSalt) {
            $key = bin2hex(random_bytes(16));  // 128-bit
            $salt = bin2hex(random_bytes(16)); // 128-bit

            $env .= PHP_EOL;
            if ($needsKey) {
                $env .= "HMAC_SECRET_GENERATOR_KEY='{$key}'" . PHP_EOL;
            }
            if ($needsSalt) {
                $env .= "HMAC_SECRET_GENERATOR_SALT='{$salt}'" . PHP_EOL;
            }

            file_put_contents($envPath, $env);
            info('🔐 HMAC key and salt generated and added to .env');
        }
    }
}
