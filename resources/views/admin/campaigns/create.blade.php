<x-admin-layout>
    <div class="max-w-2xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Create New Campaign</h2>
                <p class="text-gray-500 mt-1">Add a new campaign to the system.</p>
            </div>
            <a href="{{ route('contacts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Campaigns
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

            <form method="POST" action="{{ route('campaigns.store') }}" class="p-6 space-y-6">
                @csrf

                <!--Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200"
                           placeholder="New Campaign">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!--Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <input id="subject" type="text" name="subject" value="{{ old('subject') }}" required autofocus 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200"
                           placeholder="New campaign">
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!--scheduled_at -->
                <div>
                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-1">Scheduled At</label>
                    <input id="scheduled_at" type="date" name="scheduled_at" value="{{ old('scheduled_at') }}" required autofocus 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200"
                           placeholder="New campaign">
                    @error('scheduled_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="label" class="block text-sm font-medium text-gray-700 mb-1">Email Template</label>

                    <select id="email_template_id" name="email_template_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200">

                        @foreach($emailTemplates as $emailTemplate)
                           <option value="{{ $emailTemplate->id }}">{{ $emailTemplate->name }}</option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label for="label" class="block text-sm font-medium text-gray-700 mb-1">Contacts</label>

                    <select id="contact_ids" name="contact_ids[]" multiple class="">

                        @foreach($contacts as $contact)
                           <option value="{{ $contact->id }}">{{ $contact->first_name.' '.$contact->last_name.' ('.$contact->email.')' }}</option>
                        @endforeach

                    </select>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        Create Campaign
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

<script>
    $(document).ready(function() {

        $('#contact_ids').select2({
            placeholder: 'Select Contacts',
            allowClear: true,
            width: '100%'
        });

    });
</script>