<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Contact;
use App\Jobs\SendCampaignJob;

class CampaignController extends Controller
{
    /**
     * Display a listing of Campaign.
     */
    public function index(Request $request)
    {
        //$campaign = Campaign::where( 'id', 4 )->with(['contacts','template'])->first();

        //SendCampaignJob::dispatch($campaign);

        //die("okokoko here");

        $query = Campaign::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by admin status
        if ($request->has('filter') && $request->filter !== '') {
            if ($request->filter === 'admin') {
                $query->admins();
            } elseif ($request->filter === 'regular') {
                $query->regular();
            }
        }

        $campaigns = $query->latest()->paginate(10);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $emailTemplates = EmailTemplate::all();
        $contacts = Contact::all();

        return view('admin.campaigns.create', compact('emailTemplates', 'contacts'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // dd( $request->all() );
        $requestData = $request->all();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subject' => ['required'],
            'email_template_id' => ['required'],
            'scheduled_at' => ['required'],
            'status' => ['nullable']
        ]);


        $templateId = $request->email_template_id;

        //Dispatch email
        $campaign = [
                        'name' => $validated['name'],
                        'subject' => $validated['subject'],
                        'email_template_id' => $validated['email_template_id'],
                        'scheduled_at' => $validated['scheduled_at'],
                        'status' => 'scheduled'
                    ];

        //Activity log
        $description = "New campaign created ".$validated['name'];
        activityLog("Campaign", "Created", $description);

        // Contact::chunk(100, function ($contacts) use ($templateId) {

        //     foreach ($contacts as $contact) {

        //         SendBulkEmailJob::dispatch($contact, $templateId);
        //     }
        // });

        //die("okokokokoko");

        $campaign = Campaign::create($campaign);
        $campaign->contacts()->attach($request->contact_ids);

        $campaign = Campaign::where( 'id', $campaign->id )->with(['contacts','template'])->first();

        SendCampaignJob::dispatch($campaign);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(Campaign $campaign)
    {
        // dd($campaign);
        return view('admin.campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified contact in storage.
     */
    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            //'email' => ['required', 'string', 'email', 'max:255', 'unique:contacts,email,' . $contact->id],
            'phone' => ['required']

        ]);

        $contact->first_name = $validated['first_name'];
        $contact->last_name = $validated['last_name'];
        $contact->email = $validated['email'];
        $contact->phone = $validated['phone'];
        $contact->custom_fields = isset($requestData['dynamic_fields']) ? json_encode($requestData['dynamic_fields']) : [];
        
        $contact->save();

        //Activity log
        $description = "Campaign updated ".$validated['name'];
        activityLog("Campaign", "Updated", $description);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Campaign $campaign)
    {
        // Prevent deleting yourself
        // if ($contact->user_id === auth()->id()) {
        //     return redirect()->route('contacts.index')
        //         ->with('error', 'You cannot delete your own account.');
        // }

        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }
}
