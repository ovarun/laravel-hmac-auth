<?php

namespace Ovarun\HmacAuth\Console\Commands;

use Illuminate\Console\Command;
use Ovarun\HmacAuth\Models\HmacClient;

class CreateHmacClient extends Command
{
    protected $signature = 'hmac:create-client';
    protected $description = 'Creates a new HMAC-authenticated API client';

    public function handle()
    {
        $name = $this->ask('Enter client name (e.g., My Partner App) : ');
        $rawClientId = $this->ask('Enter client ID (optional — leave blank to auto-generate):');

        if ($rawClientId) {
            $rawClientId = trim($rawClientId);
            $clientId    = preg_replace('/[^a-z0-9\s]/', '', $rawClientId);
            $clientId    = preg_replace('/\s+/', '-', trim($clientId));
            $clientId    = strtolower($clientId);
        } else {
            $clientId = strtolower($name);
            $clientId = preg_replace('/[^a-z0-9\s]/', '', $clientId);
            $clientId = preg_replace('/\s+/', '-', trim($clientId));
        }

        $salt = hex2bin(config('hmac.secret_salt'));
        $baseKey = config('hmac.secret_key');
        $derived = hash_pbkdf2('sha256', $baseKey . $clientId, $salt, 1000, 64, true);

        $secret =  bin2hex(substr($derived, 0, 32));

        HmacClient::create([
            'client_id' => $clientId,
            'name' => $name,
            'secret' => $secret,
            'active' => true,
        ]);

        $this->info('✅ HMAC Client created successfully.');
        $this->line("Client ID: $clientId");
        $this->line("Secret: $secret (store this securely!)");
    }

    protected function deriveClientSecret(string $clientId): string
    {
    }
}
