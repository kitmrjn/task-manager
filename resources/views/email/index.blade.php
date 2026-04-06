<x-app-layout>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.25); }
        .email-body a { color: #3b82f6; text-decoration: underline; }
        .email-body ul { list-style-type: disc; margin-left: 1.5rem; margin-top: 0.5rem; margin-bottom: 0.5rem; }
        .email-body ol { list-style-type: decimal; margin-left: 1.5rem; }
        .email-body img { max-width: 100%; height: auto; border-radius: 4px; }
    </style>

    <div x-data="{ composeOpen: {{ $errors->any() ? 'true' : 'false' }} }" class="relative">

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="fixed bottom-6 right-6 bg-blue-600 text-white px-6 py-3 rounded-lg shadow-2xl flex items-center gap-3 z-[100]">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)" x-transition class="fixed bottom-6 right-6 bg-red-600 text-white px-6 py-3 rounded-lg shadow-2xl flex items-center gap-3 z-[100]">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span class="font-medium text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <div class="flex h-[calc(100vh-2rem)] rounded-xl overflow-hidden shadow-2xl m-4" style="background-color: #1a1a1a; border: 1px solid #2d2d2d;">
            
            {{-- PANE 1: SIDEBAR --}}
            <div class="hidden lg:flex w-[240px] flex-col border-r flex-shrink-0" style="border-color: #2d2d2d; background-color: #141414;">
                <div class="p-5 flex-1 overflow-y-auto custom-scrollbar">
                    <button @click="composeOpen = true" class="w-full mb-8 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-sm font-semibold transition flex items-center justify-center gap-2 shadow-lg">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                        New Message
                    </button>

                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 px-3">Mailboxes</h3>
                    <div class="space-y-1 mb-8">
                        @php $isInbox = str_contains(strtoupper($currentFolder), 'INBOX') && !str_contains(strtoupper($currentFolder), 'SENT') && !str_contains(strtoupper($currentFolder), 'TRASH') && !str_contains(strtoupper($currentFolder), 'ARCHIVE'); @endphp
                        <a href="{{ route('email.index', ['folder' => 'INBOX']) }}" class="flex items-center justify-between px-3 py-2 rounded-lg font-medium text-sm transition {{ $isInbox ? 'bg-blue-600/10 text-blue-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                            <div class="flex items-center gap-3"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> Inbox</div>
                            @if($inboxUnreadCount > 0) <span class="bg-blue-600 text-white text-[10px] px-2 py-0.5 rounded-full">{{ $inboxUnreadCount }}</span> @endif
                        </a>
                        
                        @php $isDrafts = str_contains(strtoupper($currentFolder), 'DRAFTS'); @endphp
                        <a href="{{ route('email.index', ['folder' => 'Drafts']) }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-sm transition {{ $isDrafts ? 'bg-blue-600/10 text-blue-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Drafts
                        </a>

                        @php $isSent = str_contains(strtoupper($currentFolder), 'SENT'); @endphp
                        <a href="{{ route('email.index', ['folder' => 'Sent']) }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-sm transition {{ $isSent ? 'bg-blue-600/10 text-blue-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg> Sent
                        </a>

                        @php $isArchive = str_contains(strtoupper($currentFolder), 'ARCHIVE'); @endphp
                        <a href="{{ route('email.index', ['folder' => 'Archive']) }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium text-sm transition {{ $isArchive ? 'bg-blue-600/10 text-blue-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg> Archive
                        </a>

                        @php $isTrash = str_contains(strtoupper($currentFolder), 'TRASH'); @endphp
                        <a href="{{ route('email.index', ['folder' => 'Trash']) }}" class="flex items-center justify-between px-3 py-2 rounded-lg font-medium text-sm transition {{ $isTrash ? 'bg-blue-600/10 text-blue-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                            <div class="flex items-center gap-3"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Trash</div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- PANE 2: LIST WITH SEARCH & PAGINATION --}}
            <div class="w-full md:w-[340px] lg:w-[360px] flex flex-col border-r flex-shrink-0 relative" style="border-color: #2d2d2d; background-color: #1e1e1e;">
                <div class="p-5 border-b shrink-0" style="border-color: #2d2d2d;">
                    <h2 class="text-xl font-bold text-white mb-5 capitalize">{{ str_replace('INBOX.', '', $currentFolder) }}</h2>
                    
                    {{-- REAL SEARCH FORM --}}
                    <form method="GET" action="{{ route('email.index') }}" class="relative mb-5">
                        <input type="hidden" name="folder" value="{{ $currentFolder }}">
                        <input type="hidden" name="filter" value="{{ $filter }}">
                        
                        <svg class="absolute left-3 top-2.5 text-gray-500" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search emails..." class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-700 text-sm transition focus:border-blue-500 focus:ring-1 focus:ring-blue-500" style="background-color: #141414; color: #e5e5e5; outline: none;">
                    </form>

                    <div class="flex gap-2 text-sm font-medium">
                        <a href="{{ route('email.index', ['folder' => $currentFolder, 'filter' => 'all', 'search' => $search]) }}" class="px-4 py-1.5 rounded-md transition {{ $filter !== 'unread' ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white border border-gray-700' }}">All</a>
                        <a href="{{ route('email.index', ['folder' => $currentFolder, 'filter' => 'unread', 'search' => $search]) }}" class="px-4 py-1.5 rounded-md transition {{ $filter === 'unread' ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white border border-gray-700' }}">Unread</a>
                    </div>
                </div>

                {{-- SCROLLABLE EMAIL LIST --}}
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($messages as $message)
                        @php 
                            $isUnread = !$message->hasFlag('Seen');
                            $senderStr = $message->getFrom()[0]->mail ?? 'Unknown';
                            $senderName = $message->getFrom()[0]->personal ?? explode('@', $senderStr)[0];
                            $isActive = request('uid') == $message->getUid();
                        @endphp

                        <a href="{{ route('email.index', ['folder' => $currentFolder, 'uid' => $message->getUid(), 'filter' => $filter, 'search' => $search, 'page' => $page]) }}" 
                           class="block p-4 border-b cursor-pointer transition relative" 
                           style="border-color: #2d2d2d; {{ $isActive ? 'background-color: #2a2a2a;' : 'hover:background-color: #242424;' }}">
                            @if($isActive) <div class="absolute left-0 top-0 bottom-0 w-1 rounded-r-md bg-blue-500"></div> @endif
                            <div class="flex justify-between items-start mb-1">
                                <div class="flex items-center gap-2">
                                    @if($isUnread) <div class="w-2 h-2 rounded-full bg-blue-500"></div> @endif
                                    <h4 class="font-bold text-sm {{ $isUnread || $isActive ? 'text-white' : 'text-gray-300' }}">{{ Str::limit($senderName, 22) }}</h4>
                                </div>
                                <span class="text-xs text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse((string) $message->getDate())->format('M d, g:i A') }}</span>
                            </div>
                            
                            <h5 class="text-sm font-semibold mb-1 truncate {{ $isUnread || $isActive ? 'text-gray-100' : 'text-gray-400' }}">
                                @if($message->hasAttachments()) <svg class="inline-block w-3 h-3 text-gray-500 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>@endif
                                {{ (string) $message->getSubject() }}
                            </h5>
                            
                            <p class="text-xs truncate text-gray-500 mb-2">{{ Str::limit(strip_tags((string) $message->getTextBody()), 55) }}</p>
                        </a>
                    @empty
                        <div class="p-8 text-center text-sm text-gray-500">
                            @if(!empty($search)) No emails found matching "{{ $search }}".
                            @else No emails found.
                            @endif
                        </div>
                    @endforelse
                </div>

                {{-- PAGINATION BAR --}}
                <div class="p-4 border-t flex justify-between items-center shrink-0" style="border-color: #2d2d2d; background-color: #1a1a1a;">
                    <a href="{{ $page > 1 ? route('email.index', ['folder' => $currentFolder, 'filter' => $filter, 'search' => $search, 'page' => $page - 1]) : '#' }}" 
                       class="text-xs font-medium {{ $page > 1 ? 'text-blue-400 hover:text-blue-300' : 'text-gray-600 cursor-not-allowed' }} flex items-center gap-1 transition">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg> Newer
                    </a>
                    
                    <span class="text-xs text-gray-500">Page {{ $page }}</span>
                    
                    {{-- If we fetched 15 valid messages, there are likely more to load --}}
                    <a href="{{ count($messages) >= 15 ? route('email.index', ['folder' => $currentFolder, 'filter' => $filter, 'search' => $search, 'page' => $page + 1]) : '#' }}" 
                       class="text-xs font-medium {{ count($messages) >= 15 ? 'text-blue-400 hover:text-blue-300' : 'text-gray-600 cursor-not-allowed' }} flex items-center gap-1 transition">
                        Older <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                </div>
            </div>

            {{-- PANE 3: READING AREA & ACTIONS --}}
            <div class="hidden md:flex flex-1 flex-col relative" style="background-color: #141414;">
                @if($selectedMessage)
                    @php
                        $senderStr = $selectedMessage->getFrom()[0]->mail ?? 'Unknown';
                        $senderName = $selectedMessage->getFrom()[0]->personal ?? explode('@', $senderStr)[0];
                        $subject = (string) $selectedMessage->getSubject();
                        $words = explode(' ', $senderName);
                        $initials = count($words) > 1 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) : strtoupper(substr($senderName, 0, 2));
                        
                        $realAttachments = collect();
                        if($selectedMessage->hasAttachments()) {
                            $realAttachments = collect($selectedMessage->getAttachments())->filter(function ($attachment) {
                                $isInline = strtolower($attachment->disposition ?? '') === 'inline';
                                $hasCid = !empty($attachment->id) || !empty($attachment->content_id);
                                return !$isInline && !$hasCid;
                            });
                        }
                    @endphp

                    <div class="flex-1 flex flex-col h-full">
                        <div class="px-8 py-4 border-b flex justify-between items-center shrink-0" style="border-color: #2d2d2d;">
                            <div class="flex gap-2">
                                <button onclick="document.getElementById('reply-textarea').focus()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-md text-sm font-medium flex items-center gap-2 transition">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 17 4 12 9 7"></polyline><path d="M20 18v-2a4 4 0 0 0-4-4H4"></path></svg> Reply
                                </button>
                                <form method="POST" action="{{ route('email.archive', ['uid' => $selectedMessage->getUid()]) }}">
                                    @csrf
                                    <input type="hidden" name="current_folder" value="{{ $currentFolder }}">
                                    <button type="submit" class="border text-gray-300 hover:text-white px-4 py-1.5 rounded-md text-sm font-medium transition" style="border-color: #333; background-color: #1a1a1a;">Archive</button>
                                </form>
                            </div>
                            <form method="POST" action="{{ route('email.destroy', ['uid' => $selectedMessage->getUid()]) }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="current_folder" value="{{ $currentFolder }}">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this email?')" class="text-red-400 hover:text-red-300 text-sm font-medium transition flex items-center gap-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Delete
                                </button>
                            </form>
                        </div>

                        <div class="p-8 flex-1 overflow-y-auto custom-scrollbar">
                            <h1 class="text-3xl font-bold mb-8 text-white leading-tight pr-12">{{ $subject }}</h1>
                            <div class="flex items-center gap-4 mb-10">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg" style="background-color: #1e3a8a; color: #93c5fd;">{{ $initials }}</div>
                                <div class="flex-1">
                                    <div class="flex items-baseline gap-3">
                                        <span class="font-bold text-white text-base">{{ $senderName }}</span>
                                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse((string) $selectedMessage->getDate())->format('M d, Y, g:i A') }}</span>
                                    </div>
                                    <div class="text-sm text-gray-500 mt-0.5 flex items-center gap-1">to me <span class="text-xs border px-1 rounded ml-2" style="border-color: #333;">{{ $senderStr }}</span></div>
                                </div>
                            </div>

                            <div class="email-body text-gray-300 text-sm md:text-base leading-relaxed mb-12">
                                {!! $emailBody !!}
                            </div>
                            
                            @if($realAttachments->count() > 0)
                                <div class="mb-8">
                                    <h4 class="text-sm font-bold text-gray-400 mb-3 flex items-center gap-2">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                                        Attachments ({{ $realAttachments->count() }})
                                    </h4>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($realAttachments as $attachment)
                                            <a href="{{ route('email.attachment.download', ['folder' => $currentFolder, 'uid' => $selectedMessage->getUid(), 'filename' => base64_encode($attachment->name)]) }}" 
                                               class="flex items-center gap-3 p-3 rounded-lg border transition hover:bg-white/5" style="border-color: #333; background-color: #1a1a1a;">
                                                <div class="w-10 h-10 rounded flex items-center justify-center text-blue-400" style="background-color: #1e3a8a;">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                                </div>
                                                <div class="max-w-[150px]">
                                                    <p class="text-sm font-medium text-gray-200 truncate">{{ $attachment->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ number_format(strlen($attachment->content) / 1024, 1) }} KB</p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <hr style="border-color: #2d2d2d;" class="my-8">

                            <form method="POST" action="{{ route('email.send') }}" enctype="multipart/form-data" class="rounded-xl border p-4 transition-all focus-within:border-blue-500 flex flex-col" style="border-color: #333; background-color: #1a1a1a;">
                                @csrf
                                <input type="hidden" name="to" value="{{ $senderStr }}">
                                <input type="hidden" name="subject" value="Re: {{ str_starts_with(strtolower($subject), 're:') ? substr($subject, 4) : $subject }}">
                                <textarea id="reply-textarea" name="body" class="w-full bg-transparent border-none text-white focus:ring-0 resize-none text-sm p-0 placeholder-gray-500" rows="5" placeholder="Write your reply to {{ $senderName }}..." required></textarea>
                                <div id="inline-file-list" class="mt-3 space-y-1 text-xs text-blue-400 font-medium empty:hidden"></div>
                                <div class="flex justify-between items-center mt-4 pt-4 border-t" style="border-color: #2d2d2d;">
                                    <input type="file" id="inline-attachments" name="attachments[]" multiple class="hidden" onchange="updateFileList('inline-file-list', this)">
                                    <button type="button" onclick="document.getElementById('inline-attachments').click()" class="flex items-center gap-2 text-gray-400 hover:text-white px-3 py-1.5 rounded transition text-sm font-medium"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg> Attach files</button>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium transition flex items-center gap-2">Send Reply <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg></button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center opacity-50">
                        <svg class="mb-4 text-gray-500" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <p class="text-base font-medium text-gray-500">Select an email to read</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- COMPOSE EMAIL MODAL WITH ATTACHMENTS --}}
        <div x-show="composeOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm px-4">
            <div @click.away="composeOpen = false" class="w-full max-w-3xl rounded-xl shadow-2xl flex flex-col" style="background-color: #1a1a1a; border: 1px solid #333; max-height: 90vh;">
                <div class="px-6 py-4 border-b flex justify-between items-center bg-[#141414] rounded-t-xl" style="border-color: #333;">
                    <h3 class="text-base font-bold text-white flex items-center gap-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> New Message</h3>
                    <button @click="composeOpen = false" class="text-gray-400 hover:text-white transition p-1"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                </div>
                
                <form method="POST" action="{{ route('email.send') }}" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    <div class="px-6 py-3 border-b flex items-center" style="border-color: #2d2d2d;">
                        <span class="text-gray-500 text-sm w-16">To:</span>
                        <input type="email" name="to" value="{{ old('to') }}" required class="flex-1 bg-transparent border-none text-white focus:ring-0 placeholder-gray-600 text-sm p-0">
                    </div>
                    <div class="px-6 py-3 border-b flex items-center" style="border-color: #2d2d2d;">
                        <span class="text-gray-500 text-sm w-16">Subject:</span>
                        <input type="text" name="subject" value="{{ old('subject') }}" required class="flex-1 bg-transparent border-none text-white focus:ring-0 placeholder-gray-600 text-sm p-0 font-medium">
                    </div>
                    <div class="p-6 flex-1 overflow-y-auto">
                        <textarea name="body" rows="12" placeholder="Write your message..." required class="w-full h-full bg-transparent border-none text-white focus:ring-0 placeholder-gray-600 text-sm p-0 resize-none">{{ old('body') }}</textarea>
                    </div>

                    <div id="compose-file-list" class="px-6 pb-4 space-y-1 text-xs text-blue-400 font-medium empty:hidden"></div>

                    <div class="px-6 py-4 border-t bg-[#141414] rounded-b-xl flex justify-between items-center" style="border-color: #333;">
                        <input type="file" id="compose-attachments" name="attachments[]" multiple class="hidden" onchange="updateFileList('compose-file-list', this)">
                        <button type="button" onclick="document.getElementById('compose-attachments').click()" class="text-gray-400 hover:text-white transition p-2 rounded hover:bg-white/5 flex items-center gap-2">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-md text-sm font-medium transition flex items-center gap-2 shadow-lg">
                            Send Email <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateFileList(containerId, inputElement) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            if (inputElement.files.length > 0) {
                Array.from(inputElement.files).forEach(file => {
                    const item = document.createElement('div');
                    item.className = "flex items-center gap-1 bg-blue-900/20 px-2 py-1 rounded inline-block mr-2 mt-2 border border-blue-800/50";
                    item.innerHTML = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg> ${file.name}`;
                    container.appendChild(item);
                });
            }
        }
    </script>
</x-app-layout>