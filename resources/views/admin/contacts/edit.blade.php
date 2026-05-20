<x-admin-layout>
    <div class="max-w-2xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Edit Contact Details</h2>
                <p class="text-gray-500 mt-1">Update information for {{ $contact->name }}.</p>
            </div>
            <a href="{{ route('contacts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Contacts
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

            <form class="p-6 space-y-6" method="POST">
                @csrf

               
                <div>
                    <label for="label" class="block text-sm font-medium text-gray-700 mb-1">Field Label</label>
                    <input id="label" type="text" name="label" value="{{ old('label') }}" required autofocus 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200"
                           placeholder="Field Label">
                    @error('label')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                    
               

                <div>
                    <label for="label" class="block text-sm font-medium text-gray-700 mb-1">Field Type</label>

                    <select id="type" name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200">

                        <option value="text">Text</option>

                        <option value="email">Email</option>

                        <option value="number">Number</option>

                        <option value="date">Date</option>

                        <option value="textarea">Textarea</option>

                    </select>
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                    <button type="button" onclick="appendField()" class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                         Add Field
                    </button>
                </div>

            </form>

            <form method="POST" action="{{ route('contacts.update', $contact) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- First name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $contact->first_name) }}" required autofocus 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200"
                           placeholder="John Doe">
                    @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- First name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $contact->last_name) }}" required autofocus 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200"
                           placeholder="John Doe">
                    @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $contact->email) }}" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200"
                           placeholder="john@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone', $contact->phone) }}" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200"
                           placeholder="john@example.com">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="dynamic-fields">

                    @php 
                       $custom_fields = $contact->custom_fields;
                       $fields = json_decode($contact->custom_fields, true) ?? [];

                       
                    @endphp

                    @if(!empty($custom_fields))

                        @foreach($fields as $key=>$field)

                                @php
                                    $type = $field['type'];
                                    $value = $field['value'];
                                @endphp

                                <input type="hidden" name="dynamic_fields[{{ $key }}][type]" value="{{ $type }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg"/>

                                <div class="mt-4 dynamic-field">

                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $key }}</label>
                                    
                                    @if($type == 'textarea')

                                        <textarea name="dynamic_fields[{{$key}}][value]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ $value }}</textarea>

                                    @else
                                        <input type="'+type+'" name="dynamic_fields[{{$key}}][value]" value="{{ $value }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" />

                                    @endif
                                </div> 

                        @endforeach
                    @endif
                </div>

                

                <div class="border-t border-gray-100 my-6"></div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        Update Contact Details
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

<script>

    function appendField() {

        var label = $("#label").val();

        var type = $("#type").val();

        var html = '';

        html += '<div class="mt-4 dynamic-field">';

        html += '<label class="block text-sm font-medium text-gray-700 mb-1">'
                    + label +
                '</label>';

        // TEXTAREA
        if(type == 'textarea') {

            html += '<textarea ' +
                        'name="dynamic_fields['+label+'][value]" ' +
                        'class="w-full px-4 py-2 border border-gray-300 rounded-lg">' +
                    '</textarea>';

        }

        // OTHER INPUT TYPES
        else {

            html += '<input ' +
                        'type="'+type+'" ' +
                        'name="dynamic_fields['+label+'][value]" ' +
                        'class="w-full px-4 py-2 border border-gray-300 rounded-lg" ' +
                    '/>';

        }

         html += '<input ' +
                        'type="hidden" ' +
                        'name="dynamic_fields['+label+'][type]" ' +
                        'value="'+type+'" ' +
                        'class="w-full px-4 py-2 border border-gray-300 rounded-lg" ' +
                    '/>';

        html += '</div>';

        $("#dynamic-fields").append(html);

        // Clear inputs
        $("#label").val('');
        $("#type").val('text');
    }

</script>