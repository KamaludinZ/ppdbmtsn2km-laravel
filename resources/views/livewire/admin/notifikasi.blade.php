<div>
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Notifikasi</h2>
        <p class="text-gray-600">Kelola dan lihat semua notifikasi Anda</p>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition class="mb-6 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Notifikasi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $notifications->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Belum Dibaca</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $unreadCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Sudah Dibaca</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $notifications->count() - $unreadCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Semua Notifikasi</h3>
            </div>
        </div>

        <!-- Filters & Actions -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex flex-wrap gap-2">
                    <!-- Filter Buttons -->
                    <button wire:click="$set('filter', 'all')"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition {{ $filter === 'all' ? 'bg-emerald-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                        Semua ({{ $notifications->count() }})
                    </button>
                    <button wire:click="$set('filter', 'unread')"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition {{ $filter === 'unread' ? 'bg-emerald-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                        Belum Dibaca ({{ $unreadCount }})
                    </button>
                    <button wire:click="$set('filter', 'read')"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition {{ $filter === 'read' ? 'bg-emerald-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                        Sudah Dibaca ({{ $notifications->count() - $unreadCount }})
                    </button>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if(count($selectedNotifications) > 0)
                        <button wire:click="markSelectedAsRead"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Tandai Dibaca ({{ count($selectedNotifications) }})
                        </button>
                        <button wire:click="deleteSelected"
                                wire:confirm="Apakah Anda yakin ingin menghapus {{ count($selectedNotifications) }} notifikasi?"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus ({{ count($selectedNotifications) }})
                        </button>
                    @else
                        @if($unreadCount > 0)
                        <button wire:click="markAllAsRead"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Tandai Semua Dibaca
                        </button>
                        @endif
                        @if($notifications->count() > 0)
                        <button wire:click="deleteAll"
                                wire:confirm="Apakah Anda yakin ingin menghapus SEMUA notifikasi? Tindakan ini tidak dapat dibatalkan."
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-lg hover:bg-red-50 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Semua
                        </button>
                        @endif
                    @endif
                </div>
            </div>

            @if($notifications->count() > 0)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <label class="flex items-center">
                    <input type="checkbox"
                           wire:model.live="selectAll"
                           class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                    <span class="ml-2 text-sm text-gray-600">
                        {{ count($selectedNotifications) > 0 ? count($selectedNotifications) . ' notifikasi dipilih' : 'Pilih semua notifikasi' }}
                    </span>
                </label>
            </div>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);

                    // Determine icon and color based on notification type
                    $icon = '🔔';
                    $iconBg = 'bg-blue-100';

                    if (isset($data['type'])) {
                        switch($data['type']) {
                            case 'pendaftar':
                            case 'new_registration':
                                $icon = '👤';
                                $iconBg = 'bg-emerald-100';
                                break;
                            case 'verifikasi':
                            case 'verification':
                                $icon = '✅';
                                $iconBg = 'bg-green-100';
                                break;
                            case 'daftar_ulang':
                            case 're_registration':
                                $icon = '📝';
                                $iconBg = 'bg-purple-100';
                                break;
                            case 'pengumuman':
                            case 'announcement':
                                $icon = '📢';
                                $iconBg = 'bg-yellow-100';
                                break;
                            case 'system':
                            case 'backup':
                                $icon = '💾';
                                $iconBg = 'bg-gray-100';
                                break;
                            case 'warning':
                                $icon = '⚠️';
                                $iconBg = 'bg-orange-100';
                                break;
                            case 'success':
                                $icon = '🎉';
                                $iconBg = 'bg-teal-100';
                                break;
                        }
                    }

                    $title = $data['title'] ?? 'Notifikasi';
                    $message = $data['message'] ?? '';
                    $link = $data['link'] ?? null;
                @endphp

                <div class="p-6 hover:bg-gray-50 transition {{ $isUnread ? 'bg-emerald-50/30' : 'bg-white' }}">
                    <div class="flex items-start gap-4">
                        <!-- Checkbox -->
                        <div class="flex-shrink-0 pt-1">
                            <input type="checkbox"
                                   wire:model.live="selectedNotifications"
                                   value="{{ $notification->id }}"
                                   class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                        </div>

                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 {{ $iconBg }} rounded-lg flex items-center justify-center text-2xl">
                                {{ $icon }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="text-base font-semibold text-gray-900 {{ $isUnread ? 'font-bold' : '' }}">
                                        {{ $title }}
                                        @if($isUnread)
                                            <span class="inline-flex items-center px-2 py-0.5 ml-2 text-xs font-medium bg-emerald-600 text-white rounded-full">
                                                Baru
                                            </span>
                                        @endif
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $message }}</p>
                                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                        @if($notification->read_at)
                                        <span class="flex items-center gap-1 text-green-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Dibaca {{ $notification->read_at->diffForHumans() }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Unread indicator -->
                                @if($isUnread)
                                <div class="flex-shrink-0">
                                    <span class="inline-block w-3 h-3 bg-emerald-500 rounded-full"></span>
                                </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-wrap gap-2 mt-4">
                                @if($link)
                                <a href="{{ $link }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Lihat Detail
                                </a>
                                @endif

                                @if($isUnread)
                                <button wire:click="markAsRead('{{ $notification->id }}')"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Tandai Dibaca
                                </button>
                                @else
                                <button wire:click="markAsUnread('{{ $notification->id }}')"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Tandai Belum Dibaca
                                </button>
                                @endif

                                <button wire:click="deleteNotification('{{ $notification->id }}')"
                                        wire:confirm="Apakah Anda yakin ingin menghapus notifikasi ini?"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada notifikasi</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        @if($filter === 'unread')
                            Semua notifikasi sudah dibaca
                        @elseif($filter === 'read')
                            Belum ada notifikasi yang dibaca
                        @else
                            Anda belum memiliki notifikasi
                        @endif
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>
