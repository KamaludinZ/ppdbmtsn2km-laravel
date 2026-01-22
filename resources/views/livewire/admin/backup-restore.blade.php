<div>
    <!-- Loading Overlay - Only for createBackup and restoreBackup -->
    <div wire:loading.delay.long wire:target="createBackup,restoreBackup" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 shadow-xl flex items-center space-x-3">
            <svg class="animate-spin h-8 w-8 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 font-medium">Memproses...</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Backup & Restore
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Cadangkan dan pulihkan database serta file aplikasi
                </p>
            </div>

            <div class="px-4 py-5 sm:p-6">
                <!-- Messages -->
                @if(session()->has('message'))
                    <div class="mb-4 rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('message') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(session()->has('error'))
                    <div class="mb-4 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Create Backup Section -->
                <div class="mb-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Buat Backup</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Backup</label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input 
                                        id="backup_full" 
                                        name="backupType" 
                                        type="radio" 
                                        value="full" 
                                        wire:model="backupType"
                                        class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300"
                                    >
                                    <label for="backup_full" class="ml-3 block text-sm font-medium text-gray-700">
                                        Full (Database & File)
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input 
                                        id="backup_database" 
                                        name="backupType" 
                                        type="radio" 
                                        value="database" 
                                        wire:model="backupType"
                                        class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300"
                                    >
                                    <label for="backup_database" class="ml-3 block text-sm font-medium text-gray-700">
                                        Database Saja
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input 
                                        id="backup_files" 
                                        name="backupType" 
                                        type="radio" 
                                        value="files" 
                                        wire:model="backupType"
                                        class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300"
                                    >
                                    <label for="backup_files" class="ml-3 block text-sm font-medium text-gray-700">
                                        File Saja
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-end">
                            <button
                                wire:click="createBackup"
                                wire:loading.attr="disabled"
                                wire:target="createBackup"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Buat Backup
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Available Backups -->
                <div class="mb-8" wire:key="backup-list-{{ count($backups) }}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-medium text-gray-900">Backup Tersedia ({{ count($backups) }})</h4>
                        <button
                            wire:click="refreshBackups"
                            class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                        >
                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                            Refresh
                        </button>
                    </div>
                    
                    @if(count($backups) > 0)
                        <div class="bg-white shadow overflow-hidden sm:rounded-md">
                            <ul class="divide-y divide-gray-200">
                                @foreach($backups as $backup)
                                    <li>
                                        <div class="px-4 py-4 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <div class="text-sm font-medium text-emerald-600 truncate">{{ $backup['filename'] }}</div>
                                                <div class="flex space-x-2">
                                                    <button
                                                        wire:click="downloadBackup('{{ $backup['filename'] }}')"
                                                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                    >
                                                        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                        Unduh
                                                    </button>
                                                    <button
                                                        wire:click="confirmDelete('{{ $backup['filename'] }}')"
                                                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                    >
                                                        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mt-2 sm:flex sm:justify-between">
                                                <div class="sm:flex">
                                                    <div class="mr-6 flex items-center text-sm text-gray-500">
                                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $backup['size'] }}
                                                    </div>
                                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $backup['created_at'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada backup</h3>
                            <p class="mt-1 text-sm text-gray-500">Buat backup pertama Anda menggunakan tombol di atas.</p>
                        </div>
                    @endif
                </div>
                
                <!-- Restore Section -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-4">Restore Data</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sumber File Backup</label>
                            <div class="space-y-3">
                                <!-- Tab Buttons -->
                                <div class="flex space-x-2 border-b border-gray-200">
                                    <button
                                        type="button"
                                        wire:click="$set('restoreSource', 'server')"
                                        class="px-4 py-2 text-sm font-medium border-b-2 transition-colors {{ $restoreSource === 'server' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                                    >
                                        Dari Server
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="$set('restoreSource', 'upload')"
                                        class="px-4 py-2 text-sm font-medium border-b-2 transition-colors {{ $restoreSource === 'upload' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                                    >
                                        Upload File
                                    </button>
                                </div>

                                <!-- Server Backup Selection -->
                                @if($restoreSource === 'server')
                                    <select
                                        wire:model="selectedBackup"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md"
                                    >
                                        <option value="">Pilih file backup...</option>
                                        @foreach($backups as $backup)
                                            <option value="{{ $backup['filename'] }}">{{ $backup['filename'] }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Pilih dari backup yang tersedia di server</p>
                                @else
                                    <!-- File Upload -->
                                    <div>
                                        <input
                                            type="file"
                                            wire:model="uploadedBackupFile"
                                            accept=".zip"
                                            class="block w-full text-sm text-gray-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-md file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-emerald-50 file:text-emerald-700
                                                hover:file:bg-emerald-100
                                                cursor-pointer"
                                        >
                                        <p class="text-xs text-gray-500 mt-1">Upload file backup (.zip) dari komputer Anda</p>

                                        @if($uploadedBackupFile)
                                            <div class="mt-2 p-2 bg-emerald-50 border border-emerald-200 rounded-md flex items-center">
                                                <svg class="w-4 h-4 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span class="text-sm text-emerald-700">File terpilih: {{ $uploadedBackupFile->getClientOriginalName() }}</span>
                                            </div>
                                        @endif

                                        <div wire:loading wire:target="uploadedBackupFile" class="mt-2">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Mengupload file...
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Restore</label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input 
                                        id="restore_full" 
                                        name="restoreType" 
                                        type="radio" 
                                        value="full" 
                                        wire:model="restoreType"
                                        class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300"
                                    >
                                    <label for="restore_full" class="ml-3 block text-sm font-medium text-gray-700">
                                        Full (Database & File)
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input 
                                        id="restore_database" 
                                        name="restoreType" 
                                        type="radio" 
                                        value="database" 
                                        wire:model="restoreType"
                                        class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300"
                                    >
                                    <label for="restore_database" class="ml-3 block text-sm font-medium text-gray-700">
                                        Database Saja
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input 
                                        id="restore_files" 
                                        name="restoreType" 
                                        type="radio" 
                                        value="files" 
                                        wire:model="restoreType"
                                        class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300"
                                    >
                                    <label for="restore_files" class="ml-3 block text-sm font-medium text-gray-700">
                                        File Saja
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex">
                        <button
                            wire:click="confirmRestore"
                            wire:loading.attr="disabled"
                            wire:target="restoreBackup"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                            Restore Data
                        </button>
                    </div>
                    
                    <div class="mt-4 p-4 bg-yellow-50 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Peringatan Penting</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Proses restore akan menggantikan data saat ini. Pastikan Anda telah membuat backup terbaru sebelum melanjutkan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteConfirm)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
            <div class="fixed inset-0 bg-black/10 backdrop-blur-sm transition-opacity z-[9998]" aria-hidden="true" wire:click="cancelDelete"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[10000]">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Hapus Backup
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menghapus backup <strong>{{ $backupToDelete }}</strong>? Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            wire:click="deleteBackup"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Ya, Hapus
                        </button>
                        <button
                            wire:click="cancelDelete"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Restore Confirmation Modal -->
    @if($showRestoreConfirm)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
            <div class="fixed inset-0 bg-black/10 backdrop-blur-sm transition-opacity z-[9998]" aria-hidden="true" wire:click="cancelRestore"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[10000]">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Konfirmasi Restore
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-3">
                                        Anda akan melakukan restore dari backup <strong>{{ $selectedBackup }}</strong>.
                                    </p>
                                    <div class="bg-red-50 border-l-4 border-red-400 p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-red-700">
                                                    <strong>PERINGATAN:</strong> Proses restore akan menggantikan data yang ada saat ini. Pastikan Anda telah membuat backup terbaru sebelum melanjutkan!
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            wire:click="restoreBackup"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Ya, Restore Sekarang
                        </button>
                        <button
                            wire:click="cancelRestore"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>