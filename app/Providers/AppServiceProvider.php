<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load email configuration from database
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $emailEnabled = \App\Models\Setting::get('email_enabled', false);

                if ($emailEnabled) {
                    // Set SMTP as default mailer if email is enabled
                    config(['mail.default' => 'smtp']);

                    // Load SMTP settings from database
                    $smtpHost = \App\Models\Setting::get('smtp_host');
                    $smtpPort = \App\Models\Setting::get('smtp_port', 587);
                    $smtpUsername = \App\Models\Setting::get('smtp_username');
                    $smtpPassword = \App\Models\Setting::get('smtp_password');
                    $smtpEncryption = \App\Models\Setting::get('smtp_encryption', 'tls');
                    $emailFromAddress = \App\Models\Setting::get('email_from_address', 'noreply@example.com');
                    $emailFromName = \App\Models\Setting::get('email_from_name', 'PPDB System');

                    if ($smtpHost && $smtpUsername) {
                        config([
                            'mail.mailers.smtp.transport' => 'smtp',
                            'mail.mailers.smtp.host' => $smtpHost,
                            'mail.mailers.smtp.port' => $smtpPort,
                            'mail.mailers.smtp.username' => $smtpUsername,
                            'mail.mailers.smtp.password' => $smtpPassword,
                            'mail.mailers.smtp.encryption' => $smtpEncryption ?: null,
                            'mail.from.address' => $emailFromAddress,
                            'mail.from.name' => $emailFromName,
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            // Ignore errors during boot (e.g., during migrations)
            \Log::warning('Could not load email settings from database: ' . $e->getMessage());
        }

        // Share common settings to all views
        view()->composer('*', function ($view) {
            $view->with('appAcronym', \App\Models\Setting::get('app_acronym', 'PMBM'));
            $view->with('appFullName', \App\Models\Setting::get('app_full_name', 'Penerimaan Murid Baru Madrasah'));
        });
    }
}
