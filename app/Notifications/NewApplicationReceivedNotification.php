<?php

namespace App\Notifications;

use App\Models\Job;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to the EMPLOYER / company when a new application is received.
 * Includes a summary of the applicant + link to review.
 */
class NewApplicationReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Job $job,
        public User $applicant,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $jobTitle = $this->job->position ?? 'your job posting';
        $applicantName = $this->applicant->name ?? 'A candidate';
        $jobUrl = route('jobs.show', $this->job->slug ?? $this->job->id);
        $dashUrl = route('company.dashboard');

        return (new MailMessage)
            ->subject("New application: {$jobTitle}")
            ->greeting('Hi there!')
            ->line("You have received a new application for **{$jobTitle}**.")
            ->line("**Applicant:** {$applicantName}")
            ->line('Review the candidate, download their resume, and respond directly from your dashboard.')
            ->action('Review Application', $dashUrl)
            ->line("[View the job posting]({$jobUrl})")
            ->line('Faster responses lead to higher hire rates — most top candidates accept offers within 10 days of applying.')
            ->salutation('The Jobs in USA Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'application_received',
            'job_id' => $this->job->id,
            'job' => $this->job->position,
            'applicant_id' => $this->applicant->id,
            'applicant_name' => $this->applicant->name,
        ];
    }
}
