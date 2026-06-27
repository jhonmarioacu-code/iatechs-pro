<?php

declare(strict_types=1);

return [
    'hsts_enabled' => (bool) env('SECURITY_HSTS_ENABLED', true),

    'headers' => [
        'x_frame_options' => env('SECURITY_X_FRAME_OPTIONS', 'DENY'),
        'x_content_type_options' => env('SECURITY_X_CONTENT_TYPE_OPTIONS', 'nosniff'),
        'referrer_policy' => env('SECURITY_REFERRER_POLICY', 'strict-origin-when-cross-origin'),
        'permissions_policy' => env(
            'SECURITY_PERMISSIONS_POLICY',
            'camera=(), microphone=(), geolocation=(), payment=(), usb=()'
        ),
        'x_xss_protection' => env('SECURITY_X_XSS_PROTECTION', '0'),
        'cross_origin_opener_policy' => env('SECURITY_COOP', 'same-origin'),
        'cross_origin_resource_policy' => env('SECURITY_CORP', 'same-origin'),
        'strict_transport_security' => env(
            'SECURITY_STRICT_TRANSPORT_SECURITY',
            'max-age=31536000; includeSubDomains; preload'
        ),
        'content_security_policy' => env(
            'SECURITY_CONTENT_SECURITY_POLICY',
            "default-src 'self'; base-uri 'self'; frame-ancestors 'none'; form-action 'self'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self' https: wss: ws:; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline'"
        ),
    ],
];
