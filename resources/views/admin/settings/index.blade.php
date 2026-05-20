<x-admin-layout>
    <div class="max-w-2xl mx-auto space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Site Settings</h2>
                <p class="text-gray-500 mt-1">Configure general application settings.</p>
            </div>
            
            @if(session('success'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Saved Successfully
                </span>
            @endif
        </div>

        <!-- Settings Form -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-medium text-gray-900">General Configuration</h3>
                <p class="mt-1 text-sm text-gray-500">Update your site's core information and preferences.</p>
            </div>

            <form method="POST" action="{{ route('settings.update') }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Site Name -->
                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                    <input id="site_name" type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200"
                           placeholder="My Laravel App">
                    @error('site_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Site Email -->
                <div>
                    <label for="site_email" class="block text-sm font-medium text-gray-700 mb-1">Support Email</label>
                    <input id="site_email" type="email" name="site_email" value="{{ old('site_email', $settings['site_email']) }}" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200"
                           placeholder="support@example.com">
                    @error('site_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Users Per Page -->
                    <div>
                        <label for="users_per_page" class="block text-sm font-medium text-gray-700 mb-1">Users Per Page</label>
                        <input id="users_per_page" type="number" name="users_per_page" value="{{ old('users_per_page', $settings['users_per_page']) }}" min="5" max="100" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-200">
                        @error('users_per_page')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Maintenance Mode -->
                <div class="pt-2">
                    <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                        <input type="checkbox" name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }} 
                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <div class="ml-3">
                            <span class="block text-sm font-medium text-gray-900">Maintenance Mode</span>
                            <span class="block text-sm text-gray-500">Put the site in maintenance mode. Only admins will be able to access.</span>
                        </div>
                    </label>
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
