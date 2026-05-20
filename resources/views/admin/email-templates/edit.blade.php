<x-admin-layout>
    <div class="max-w-2xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Edit Email Template Details</h2>
                <p class="text-gray-500 mt-1">Update information for {{ $emailTemplate->name }}.</p>
            </div>
            <a href="{{ route('email-templates.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Email Template
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

            
            <form id="formTemplate" method="POST" action="{{ route('email-templates.update', $emailTemplate) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <input id="subject" type="text" name="subject" value="{{ old('subject', $emailTemplate->subject) }}" required autofocus 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200"
                           placeholder="Register email">
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Template Variable -->
                <div>
                    <label for="template_variable" class="block text-sm font-medium text-gray-700 mb-1">Template Variable</label>
                    <input id="template_variable" type="text" name="template_variable" value="{{ old('template_variable', $emailTemplate->template_variable) }}" required autofocus 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200"
                           placeholder="{name},{email}">
                    @error('template_variable')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Body</label>
                    <textarea id="editor" type="text" name="body" value="{{ old('body', $emailTemplate->body) }}" required autofocus 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200"
                           placeholder="John Doe">{{ $emailTemplate->body }}</textarea>
                    @error('body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

               

                <div class="border-t border-gray-100 my-6"></div>

                
                 <!-- Form Actions -->
                <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                    <button onclick="submitForm()" type="submit" class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        Update Email Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
<script>

        function submitForm(){
            $("#formTemplate").submit();
        }

        let editorInstance;

        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                editorInstance = editor;
            });

        document.querySelector('form').addEventListener('submit', function () {
            document.querySelector('#editor').value = editorInstance.getData();
        });


    </script>