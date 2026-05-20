<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = EmailTemplate::query();

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

        $emailTemplates = $query->latest()->paginate(10);

        return view('admin.email-templates.index', compact('emailTemplates'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.email-templates.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // dd( $request->all() );

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subject' => ['required'],
            'template_variable' => ['required'],
            'body' => ['required']
        ]);

        EmailTemplate::create([
            'name' => $validated['name'],
            'body' => $validated['body'],
            'subject' => $validated['subject'],
            'template_variable' => $validated['template_variable'],
        ]);

        //Activity log
        $description = "New email template created ".$validated['name'];
        activityLog("EmailTemplate", "Created", $description);

        return redirect()->route('email-templates.index')
            ->with('success', 'Email-template created successfully.');
    }

    /**
     * Show the form for editing the specified emailTemplate.
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        return view('admin.email-templates.edit', compact('emailTemplate'));
    }

    /**
     * Update the specified emailTemplate in storage.
     */
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        // dd($emailTemplate);

        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required'],
            'template_variable' => ['nullable'],
        ]);

        $emailTemplate->subject = $validated['subject'];
        $emailTemplate->body = $validated['body'];
        $emailTemplate->template_variable = $validated['template_variable'];
        
        $emailTemplate->save();

        //Activity log
        $description = "Email template updated ".$emailTemplate->name;
        activityLog("EmailTemplate", "Updated", $description);

        return redirect()->route('email-templates.index')
            ->with('success', 'Email template updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        // Prevent deleting yourself
        // if ($emailTemplate->id === auth()->id()) {
        //     return redirect()->route('email-templates.index')
        //         ->with('error', 'You cannot delete your own account.');
        // }

        $emailTemplate->delete();

        return redirect()->route('email-templates.index')
            ->with('success', 'Email template deleted successfully.');
    }
}
