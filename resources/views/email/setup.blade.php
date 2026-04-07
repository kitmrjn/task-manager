<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Connect Your Mailbox') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 border-b border-gray-200">
                    
                    <div class="text-center mb-8">
                        <svg class="mx-auto h-12 w-12 text-indigo-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-900">Welcome to your Inbox</h3>
                        <p class="text-gray-500 mt-2">Connect your work email to send, receive, and manage messages without leaving the Task Manager. Enter your A2 Hosting or cPanel details below.</p>
                    </div>

                    @if(isset($error))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 text-sm text-red-700">
                                    <p>{{ $error }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('email.setup.store') }}" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 uppercase mb-4">Receiving (IMAP)</h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="email_imap_host" value="IMAP Server Host" />
                                        <x-text-input id="email_imap_host" name="email_imap_host" type="text" class="mt-1 block w-full" placeholder="mail.yourdomain.com" required />
                                        <x-input-error class="mt-2" :messages="$errors->get('email_imap_host')" />
                                    </div>
                                    <div>
                                        <x-input-label for="email_imap_port" value="IMAP Port" />
                                        <x-text-input id="email_imap_port" name="email_imap_port" type="number" class="mt-1 block w-full" value="993" required />
                                        <p class="text-xs text-gray-500 mt-1">Usually 993 for SSL</p>
                                        <x-input-error class="mt-2" :messages="$errors->get('email_imap_port')" />
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 uppercase mb-4">Sending (SMTP)</h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="email_smtp_host" value="SMTP Server Host" />
                                        <x-text-input id="email_smtp_host" name="email_smtp_host" type="text" class="mt-1 block w-full" placeholder="mail.yourdomain.com" required />
                                        <x-input-error class="mt-2" :messages="$errors->get('email_smtp_host')" />
                                    </div>
                                    <div>
                                        <x-input-label for="email_smtp_port" value="SMTP Port" />
                                        <x-text-input id="email_smtp_port" name="email_smtp_port" type="number" class="mt-1 block w-full" value="465" required />
                                        <p class="text-xs text-gray-500 mt-1">Usually 465 for SSL</p>
                                        <x-input-error class="mt-2" :messages="$errors->get('email_smtp_port')" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-6">
                            <h4 class="text-sm font-semibold text-gray-700 uppercase mb-4">Authentication</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="email_username" value="Email Address" />
                                    <x-text-input id="email_username" name="email_username" type="email" class="mt-1 block w-full" placeholder="you@yourdomain.com" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('email_username')" />
                                </div>
                                <div>
                                    <x-input-label for="email_password" value="Email Password" />
                                    <x-text-input id="email_password" name="email_password" type="password" class="mt-1 block w-full" required />
                                    <p class="text-xs text-gray-500 mt-1">Passwords are securely encrypted.</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('email_password')" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-4">
                                {{ __('Connect Mailbox') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>