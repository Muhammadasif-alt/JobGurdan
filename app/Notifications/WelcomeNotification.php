<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Welcome email sent to users immediately after registration.
 * Different content based on role (company vs job seeker).
 */
class WelcomeNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isCompany = ($notifiable->role ?? null) === 'company';
        $firstName = explode(' ', (string) ($notifiable->name ?? 'there'))[0];
        $dashUrl = method_exists($notifiable, 'dashboardRouteName')
            ? route($notifiable->dashboardRouteName())
            : url('/dashboard');

        $mail = (new MailMessage)
            ->subject('Welcome to Jobs in USA — your account is ready!')
            ->greeting("Hi {$firstName}!")
            ->line("Thanks for joining **Jobs in USA** — America's trusted job search platform connecting verified employers with millions of job seekers across all 50 U.S. states.");

        if ($isCompany) {
            $mail->line('As an employer, you can:')
                ->line('• Post unlimited verified job openings')
                ->line('• Browse 10M+ candidate profiles')
                ->line('• Manage applications from a single dashboard')
                ->action('Go to your Company Dashboard', $dashUrl);
        } else {
            $mail->line('As a job seeker, you can:')
                ->line('• Browse 14,000+ verified U.S. jobs')
                ->line('• Save jobs and apply with one click — 100% free')
                ->line('• Get matched with roles based on your skills')
                ->action('Find Your Next Job', route('jobs.index'));
        }

        return $mail
            ->line('If you have any questions, just reply to this email — we are here to help.')
            ->salutation('Best regards, The Jobs in USA Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'welcome',
            'role' => $notifiable->role ?? null,
            'message' => 'Welcome to Jobs in USA!',
        ];
    }
}
