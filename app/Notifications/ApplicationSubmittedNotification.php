<?php

namespace App\Notifications;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to the JOB SEEKER after they successfully submit an application.
 * Confirms receipt + sets expectations + offers next steps.
 */
class ApplicationSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(public Job $job) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $firstName = explode(' ', (string) ($notifiable->name ?? 'there'))[0];
        $jobTitle = $this->job->position ?? 'this role';
        $company = $this->job->advertiser->name ?? 'the employer';
        $jobUrl = route('jobs.show', $this->job->slug ?? $this->job->id);

        return (new MailMessage)
            ->subject("Application submitted for {$jobTitle}")
            ->greeting("Hi {$firstName}!")
            ->line("Your application for **{$jobTitle}** at **{$company}** has been submitted successfully.")
            ->line('What happens next:')
            ->line('• The employer will review your profile and resume')
            ->line('• You will receive a response directly from the company if shortlisted')
            ->line('• Typical response time: 5–14 days')
            ->action('View Job Posting', $jobUrl)
            ->line('Keep exploring more roles while you wait — your perfect match might be just a click away.')
            ->salutation('Good luck, The JobGader Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'application_submitted',
            'job_id' => $this->job->id,
            'job' => $this->job->position,
            'company' => $this->job->advertiser->name ?? null,
        ];
    }
}
