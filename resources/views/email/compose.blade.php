<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('email.index') }}" class="text-gray-500 hover:text-gray-700">
                &larr; Back to Inbox
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Compose Email') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('email.send') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <x-input-label for="to" :value="__('To')" />
                            <x-text-input id="to" class="block mt-1 w-full" type="email" name="to" :value="old('to')" required autofocus />
                            <x-input-error :messages="$errors->get('to')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="subject" :value="__('Subject')" />
                            <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject" :value="old('subject')" required />
                            <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="body" :value="__('Message')" />
                            <textarea id="body" name="body" rows="10" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('body') }}</textarea>
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Send Email') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>