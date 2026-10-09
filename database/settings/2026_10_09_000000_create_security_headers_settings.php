<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Nothing here is used until the page is saved (customized = true): the app config applies until then.
        $this->migrator->add('security_headers.customized', false);
        $this->migrator->add('security_headers.headers', []);
        $this->migrator->add('security_headers.csp_enabled', true);
        $this->migrator->add('security_headers.csp_report_only', false);
        $this->migrator->add('security_headers.csp_directives', []);
        $this->migrator->add('security_headers.csp_report_uri', null);
        $this->migrator->add('security_headers.hsts_enabled', true);
        $this->migrator->add('security_headers.hsts_max_age', 31536000);
        $this->migrator->add('security_headers.hsts_include_subdomains', true);
        $this->migrator->add('security_headers.hsts_preload', false);
    }
};
