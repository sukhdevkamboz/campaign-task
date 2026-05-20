<?php

namespace App\Jobs;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public $campaign;

    public function __construct($campaign)
    {
        $this->campaign = $campaign;
    }

    public function handle()
    {
        $template = $this->campaign->template;
        $scheduled_at = $this->campaign->scheduled_at;

        foreach ($this->campaign->contacts as $contact) {

            $body = $template->body;
            
            $description = $this->campaign->name.' email sent on '.$contact->email;
            activityLog("Campaign", "EmailSent", $description);

            $body = str_replace('{date}', $scheduled_at, $body);
            $body = str_replace('{company_name}', "Laravel", $body);
            $body = str_replace('{name}', $contact->first_name, $body);
            $body = str_replace('{email}', $contact->email, $body);

            Mail::html($body, function ($message) use ($contact) {
                $message->to($contact->email)
                    ->subject($this->campaign->subject);
            });
        }

        $this->campaign->update([
            'status' => 'sent'
        ]);
    }
}
