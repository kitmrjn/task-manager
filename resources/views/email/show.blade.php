<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('email.index') }}" class="text-gray-500 hover:text-gray-700">
                &larr; Back to Inbox
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ (string) $message->getSubject() }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="mb-4">
                        <p class="text-sm text-gray-600"><strong>From:</strong> {{ $message->getFrom()[0]->mail ?? 'Unknown' }}</p>
                        <p class="text-sm text-gray-600"><strong>Date:</strong> {{ \Carbon\Carbon::parse((string) $message->getDate())->format('F j, Y, g:i a') }}</p>
                    </div>
                    
                    <div class="mt-6 prose max-w-none bg-gray-50 p-4 rounded-md">
                        {!! $message->hasHTMLBody() ? $message->getHTMLBody() : nl2br(e((string) $message->getTextBody())) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>