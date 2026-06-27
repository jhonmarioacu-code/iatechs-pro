<?php

declare(strict_types=1);

it('adds security headers on web responses', function (): void {
    $response = $this->get('/login');

    $response->assertOk();
    $response->assertHeader('X-Frame-Options', 'DENY');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('Permissions-Policy');
    $response->assertHeader('Content-Security-Policy');
    $response->assertHeader('Cross-Origin-Opener-Policy', 'same-origin');
    $response->assertHeader('Cross-Origin-Resource-Policy', 'same-origin');
});

it('adds hsts when request is served over https', function (): void {
    $response = $this->withHeaders([
        'X-Forwarded-Proto' => 'https',
    ])->get('/login');

    $response->assertOk();
    $response->assertHeader('Strict-Transport-Security');
});
