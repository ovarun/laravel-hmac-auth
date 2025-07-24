<?php 

namespace Ovarun\HmacAuth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Ovarun\HmacAuth\Models\HmacClient;

class VerifyHmacSignature
{
    public function handle(Request $request, Closure $next)
    {
        $clientId = $request->header('X-CLIENT-ID');
        $timestamp = $request->header('X-TIMESTAMP');
        $signature = $request->header('X-SIGNATURE');

        if (!$clientId || !$timestamp || !$signature) {
            return response()->json(['message' => 'Missing authentication headers.'], 401);
        }

        if (abs(now()->timestamp - strtotime($timestamp)) > config('hmac.timestamp_tolerance_seconds', 300)) {
            return response()->json(['message' => 'Invalid timestamp.'], 401);
        }

        $client = HmacClient::where('client_id', $clientId)->where('active', true)->first();
        if (!$client) {
            return response()->json(['message' => 'Invalid client.'], 401);
        }

        $method = strtoupper($request->getMethod());
        $path = $request->getPathInfo();
        $body = $request->getContent();
        $message = $timestamp . $method . $path . $body;
        $expected = hash_hmac('sha256', $message, $client->secret);

        if (!hash_equals($expected, $signature)) {
            return response()->json(['message' => 'Signature mismatch.'], 401);
        }

        return $next($request);
    }
}