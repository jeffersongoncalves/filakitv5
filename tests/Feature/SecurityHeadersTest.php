<?php

namespace Tests\Feature;

use Filament\Facades\Filament;
use JeffersonGoncalves\Filament\SecurityHeaders\Pages\ManageSecurityHeaders;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_panels_send_the_security_headers(): void
    {
        $this->withoutVite();

        $response = $this->get('/admin/login');

        $response->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

        $csp = (string) $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("script-src 'self' 'unsafe-inline' 'unsafe-eval'", $csp);
        $this->assertStringContainsString("frame-ancestors 'self'", $csp);
    }

    public function test_admins_can_edit_the_headers_from_the_admin_panel(): void
    {
        $this->assertContains(ManageSecurityHeaders::class, Filament::getPanel('admin')->getPages());
    }
}
