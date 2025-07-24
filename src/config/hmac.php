<?php

return [
    'timestamp_tolerance_seconds' => env('HMAC_TIMESTAMP_TOLERANCE_SECONDS',300),
    'secret_generator_key' => env('HMAC_SECRET_GENERATOR_KEY', null),
    'secret_generator_salt' => env('HMAC_SECRET_GENERATOR_SALT', null),
];