<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class EmailService
{
    /**
     * Send email message
     *
     * @param string $to Email address
     * @param string $subject Email subject
     * @param string $message Email body
     * @return array
     */
    public function sendEmail($to, $subject, $message)
    {
        try {
            // Validate email configuration
            if (!config('mail.default')) {
                throw new \Exception('Email belum dikonfigurasi. Silakan setup di .env file.');
            }

            // Send email menggunakan closure untuk plain text
            Mail::raw($message, function ($mail) use ($to, $subject) {
                $mail->to($to)
                     ->subject($subject)
                     ->from(
                         config('mail.from.address', 'noreply@mtsn2kotamalang.sch.id'),
                         config('mail.from.name', 'MTsN 2 Kota Malang')
                     );
            });

            Log::info('Email Sent Successfully:', [
                'to' => $to,
                'subject' => $subject,
            ]);

            return [
                'success' => true,
                'message' => 'Email sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send email:', [
                'to' => $to ?? 'unknown',
                'subject' => $subject ?? 'unknown',
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send HTML email message
     *
     * @param string $to Email address
     * @param string $subject Email subject
     * @param string $htmlContent HTML content
     * @return array
     */
    public function sendHtmlEmail($to, $subject, $htmlContent)
    {
        try {
            // Validate email configuration
            if (!config('mail.default')) {
                throw new \Exception('Email belum dikonfigurasi. Silakan setup di .env file.');
            }

            // Send email with HTML content
            Mail::send([], [], function ($mail) use ($to, $subject, $htmlContent) {
                $mail->to($to)
                     ->subject($subject)
                     ->from(
                         config('mail.from.address', 'noreply@mtsn2kotamalang.sch.id'),
                         config('mail.from.name', 'MTsN 2 Kota Malang')
                     )
                     ->html($htmlContent);
            });

            Log::info('HTML Email Sent Successfully:', [
                'to' => $to,
                'subject' => $subject,
            ]);

            return [
                'success' => true,
                'message' => 'Email sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send HTML email:', [
                'to' => $to ?? 'unknown',
                'subject' => $subject ?? 'unknown',
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
