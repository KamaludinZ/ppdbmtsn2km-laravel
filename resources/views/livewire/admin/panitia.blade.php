<div>
    <div class="space-y-6">
        <!-- Flash Message -->
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition class="bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('message') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm font-medium text-gray-600">Total Panitia</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm font-medium text-gray-600">Admin</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['admin'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm font-medium text-gray-600">Panitia Pendaftaran</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['panitia_pendaftaran'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm font-medium text-gray-600">Panitia Daftar Ulang</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['panitia_daftarulang'] }}</p>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Daftar Panitia</h3>
                </div>
                <button wire:click="create" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="inline-flex items-center px-4 py-2 bg-white text-emerald-600 rounded-lg font-bold uppercase tracking-wider text-xs hover:bg-emerald-50 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="create">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <svg class="animate-spin w-4 h-4 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24" wire:loading wire:target="create">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="create">Tambah Panitia</span>
                    <span wire:loading wire:target="create">Loading...</span>
                </button>
            </div>
            <div class="p-6 bg-gray-50 border-b border-gray-200">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" wire:model.live="search" placeholder="Cari nama atau email..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                    </div>
                    <div class="w-full md:w-64">
                        <select wire:model.live="filterRole" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                            <option value="">Semua Role</option>
                            <option value="admin">Admin</option>
                            <option value="panitia_pendaftaran">Panitia Pendaftaran</option>
                            <option value="panitia_daftarulang">Panitia Daftar Ulang</option>
                        </select>
                    </div>
                </div>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->aktif ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit({{ $user->id }})" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <span wire:loading.remove wire:target="edit({{ $user->id }})">Edit</span>
                                    <span wire:loading wire:target="edit({{ $user->id }})" class="inline-flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Loading...
                                    </span>
                                </button>
                                <button wire:click="delete({{ $user->id }})" wire:confirm="Yakin ingin menghapus akun ini?" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="text-red-600 hover:text-red-900">
                                    <span wire:loading.remove wire:target="delete({{ $user->id }})">Hapus</span>
                                    <span wire:loading wire:target="delete({{ $user->id }})" class="inline-flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Loading...
                                    </span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data panitia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
        <div class="fixed inset-0 overflow-y-auto" aria-labelledby="panitia-modal" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/10 backdrop-blur-sm transition-opacity z-[9998]" aria-hidden="true" wire:click="closeModal"></div>
            <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0">
                <div class="inline-block w-full max-w-2xl overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl sm:my-8 sm:align-middle z-[10000]" role="dialog">
                    <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="panitia-modal">
                                {{ $isEdit ? 'Edit Akun Panitia' : 'Tambah Akun Panitia Baru' }}
                            </h3>
                            <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                                <input
                                    type="text"
                                    wire:model="name"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                    placeholder="Masukkan nama lengkap..."
                                >
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input
                                    type="email"
                                    wire:model="email"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                    placeholder="email@example.com"
                                >
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password {{ $isEdit ? '' : '*' }}</label>
                                <input
                                    type="password"
                                    wire:model="password"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                    placeholder="{{ $isEdit ? 'Kosongkan jika tidak ingin mengubah password' : 'Masukkan password...' }}"
                                >
                                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                @if($isEdit)
                                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                                @endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                                <select
                                    wire:model="role"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                >
                                    <option value="">Pilih Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="panitia_pendaftaran">Panitia Pendaftaran</option>
                                    <option value="panitia_daftarulang">Panitia Daftar Ulang</option>
                                </select>
                                @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex items-center">
                                <input
                                    type="checkbox"
                                    wire:model="aktif"
                                    id="aktif-checkbox"
                                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                                >
                                <label for="aktif-checkbox" class="ml-2 block text-sm text-gray-900">
                                    Akun Aktif
                                </label>
                            </div>
                        </form>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-6">
                        <button
                            wire:click="{{ $isEdit ? 'update' : 'store' }}"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            <span wire:loading.remove wire:target="{{ $isEdit ? 'update' : 'store' }}">{{ $isEdit ? 'Update' : 'Simpan' }}</span>
                            <span wire:loading wire:target="{{ $isEdit ? 'update' : 'store' }}">Menyimpan...</span>
                        </button>
                        <button
                            wire:click="closeModal"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
