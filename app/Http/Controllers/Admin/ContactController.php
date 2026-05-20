<?php

// https://chatgpt.com/share/6a0c99b9-11e8-8323-a91d-48c4974c3b3f

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ContactController extends Controller
{
    /**
     * Display a listing of contacts.
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
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

        $contacts = $query->latest()->paginate(10);

        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.contacts.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->all();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:contacts'],
            'phone' => ['required']
        ]);

        $custom_fields = isset($requestData['dynamic_fields']) ? ($requestData['dynamic_fields']) : [];

        Contact::create([
            'user_id' => auth()->id(),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'custom_fields' => json_encode($custom_fields)
        ]);

        //Activity log
        $description = "New contact created ".$validated['first_name'].'('.$validated['email'].')';
        activityLog("Contact", "Created", $description);

        return redirect()->route('contacts.index')
            ->with('success', 'Contact created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(Contact $contact)
    {
        // dd($contact);
        return view('admin.contacts.edit', compact('contact'));
    }

    /**
     * Update the specified contact in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        $requestData = $request->all();

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
        $custom_fields = isset($requestData['dynamic_fields']) ? ($requestData['dynamic_fields']) : [];
        $contact->custom_fields = json_encode($custom_fields);
        
        $contact->save();

        //Activity log
        $description = "Contact updated ".$validated['first_name'].'('.$validated['email'].')';
        activityLog("Contact", "Updated", $description);

        return redirect()->route('contacts.index')
            ->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Contact $contact)
    {
        // Prevent deleting yourself
        // if ($contact->user_id === auth()->id()) {
        //     return redirect()->route('contacts.index')
        //         ->with('error', 'You cannot delete your own account.');
        // }

        $contact->delete();

        return redirect()->route('contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }

    public function downloadSampleCsv()
    {
        $fileName = 'sample_contacts.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
        ];

        $columns = [
            'first_name',
            'last_name',
            'email',
            'phone'
        ];

        $callback = function () use ($columns) {

            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, $columns);

            // Sample rows
            fputcsv($file, [
                'John',
                'Doe',
                'john@example.com',
                '9876543210'
            ]);

            fputcsv($file, [
                'Jane',
                'Smith',
                'jane@example.com',
                '9876543211'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        if ($request->isMethod('get')) {
            
            return view('admin.contacts.import');

        }else{

            $request->validate([
                'file' => 'required|mimes:csv,txt'
            ]);

            $file = fopen($request->file('file')->getRealPath(), 'r');

            // Skip header row
            fgetcsv($file);

            while (($row = fgetcsv($file, 1000, ',')) !== FALSE) {

                Contact::create([
                    'user_id'    => auth()->id(),
                    'first_name' => $row[0],
                    'last_name'  => $row[1],
                    'email'      => $row[2],
                    'phone'      => $row[3],
                ]);
            }

            fclose($file);

            // return back()->with('success', 'CSV Imported Successfully');
              return redirect()->route('contacts.index')
            ->with('success', 'CSV Imported Successfully
                .');

        }
    }
}
