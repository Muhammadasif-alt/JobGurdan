<?php

namespace App\Notifications;

use App\Models\JobAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

/**
 * Periodic email sent by the SendJobAlerts command containing new jobs
 * that match a user's saved JobAlert criteria.
 */
class JobAlertMatchesNotification extends Notification
{
    use Queueable;

    /**
     * @param  \Illuminate\Support\Collection<\App\Models\Job>  $jobs
     */
    public function __construct(public JobAlert $alert, public Collection $jobs) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $firstName = explode(' ', (string) ($notifiable->name ?? 'there'))[0];
        $count = $this->jobs->count();
        $label = $this->summaryLabel();

        $mail = (new MailMessage)
            ->subject("{$count} new ".($count === 1 ? 'job matches' : 'jobs match').' your alert'.($label ? " — {$label}" : ''))
            ->greeting("Hi {$firstName}!")
            ->line("We found **{$count} new ".($count === 1 ? 'job' : 'jobs').'** matching your alert'.($label ? " for {$label}" : '').' since we last checked.')
            ->line('---');

        // Show top 5 inline (rest behind a link). Slug derived from position + location.
        foreach ($this->jobs->take(5) as $job) {
            $slug = \Illuminate\Support\Str::slug($job->position.'-'.($job->location->name ?? ''));
            $url = $slug !== '' ? route('jobs.show', $slug) : route('jobs.index');
            $where = $job->location->name ?? 'USA';
            $company = $job->advertiser->name ?? 'Verified employer';
            $mail->line("**[{$job->position}]({$url})** — {$company} · {$where}");
        }

        if ($this->jobs->count() > 5) {
            $mail->line('...and '.($this->jobs->count() - 5).' more matching jobs.');
        }

        return $mail
            ->action('View all matching jobs', route('jobs.index').'?'.http_build_query(array_filter([
                'position' => $this->alert->keywords,
                'location' => $this->alert->location->name ?? null,
            ])))
            ->line('You can [pause or edit this alert]('.route('seeker.job-alerts.index').') anytime.')
            ->salutation('Happy hunting, The JobGader Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'job_alert_matches',
            'alert_id' => $this->alert->id,
            'job_count' => $this->jobs->count(),
        ];
    }

    private function summaryLabel(): string
    {
        $parts = array_filter([
            $this->alert->keywords,
            $this->alert->location?->name,
            $this->alert->category?->name,
        ]);

        return implode(' · ', $parts);
    }
}
