<div x-data="{
    showNotification: false,
    notificationType: 'success',
    notificationMessage: ''
}"
@notify.window="
    showNotification = true;
    notificationType = $event.detail.type || 'success';
    notificationMessage = $event.detail.message || 'Success';
    setTimeout(() => { showNotification = false }, 5000);
">
    <!-- Notification Toast -->
    <div x-show="showNotification"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         style="display: none;"
         class="fixed top-4 right-4 z-50 max-w-md">
        <div :class="{
            'bg-green-50 border-green-500': notificationType === 'success',
            'bg-red-50 border-red-500': notificationType === 'error',
            'bg-blue-50 border-blue-500': notificationType === 'info',
            'bg-yellow-50 border-yellow-500': notificationType === 'warning'
        }" class="border-l-4 p-4 rounded-lg shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <template x-if="notificationType === 'success'">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </template>
                    <template x-if="notificationType === 'error'">
                        <i class="fas fa-times-circle text-red-500 text-xl"></i>
                    </template>
                    <template x-if="notificationType === 'info'">
                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                    </template>
                    <template x-if="notificationType === 'warning'">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                    </template>
                </div>
                <div class="ml-3 flex-1">
                    <p :class="{
                        'text-green-800': notificationType === 'success',
                        'text-red-800': notificationType === 'error',
                        'text-blue-800': notificationType === 'info',
                        'text-yellow-800': notificationType === 'warning'
                    }" class="text-sm font-medium" x-text="notificationMessage"></p>
                </div>
                <div class="ml-3">
                    <button @click="showNotification = false" class="inline-flex rounded-md p-1.5 focus:outline-none">
                        <i class="fas fa-times text-gray-400 hover:text-gray-600"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Monitoring Aplikasi & Server
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Informasi kondisi sistem dan server aplikasi
                </p>
            </div>

            <div class="px-4 py-5 sm:p-6">
                <!-- Refresh Button -->
                <div class="mb-6">
                    <button
                        wire:click="refreshData"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                    >
                        Refresh Data
                    </button>

                    @if(session()->has('message'))
                        <span class="ml-4 text-sm text-green-600">{{ session('message') }}</span>
                    @endif
                </div>
                
                <!-- System Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Server Information -->
                    <div class="bg-gray-50 shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 bg-white sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Server</h3>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">OS</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $serverInfo['os'] ?? '-' }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Versi PHP</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $serverInfo['php_version'] ?? '-' }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Hostname</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $serverInfo['hostname'] ?? '-' }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Alamat IP</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $serverInfo['ip_address'] ?? '-' }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Memory Usage</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $serverInfo['memory_usage'] ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <!-- Database Information -->
                    <div class="bg-gray-50 shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 bg-white sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Database</h3>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Connection</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $databaseInfo['connection'] ?? ($databaseInfo['error'] ?? '-') }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Database</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $databaseInfo['database'] ?? '-' }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Host</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $databaseInfo['host'] ?? '-' }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Port</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $databaseInfo['port'] ?? '-' }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500"># Tables</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $databaseInfo['tables_count'] ?? '-' }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500"># Active Connections</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $databaseInfo['connections_active'] ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <!-- Application Information -->
                    <div class="bg-gray-50 shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 bg-white sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Aplikasi</h3>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Nama Aplikasi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $applicationInfo['app_name'] ?? '-' }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Environment</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $applicationInfo['app_env'] ?? '-' }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Debug Mode</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $applicationInfo['app_debug'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $applicationInfo['app_debug'] ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Laravel Version</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $applicationInfo['laravel_version'] ?? '-' }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Timezone</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $applicationInfo['timezone'] ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <!-- Disk Usage -->
                <div class="bg-gray-50 shadow overflow-hidden sm:rounded-lg mb-8">
                    <div class="px-4 py-5 bg-white sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Penggunaan Penyimpanan</h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-900">{{ $diskUsage['total'] ?? '-' }}</p>
                                    <p class="text-sm text-gray-500">Total</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-900">{{ $diskUsage['used'] ?? '-' }}</p>
                                    <p class="text-sm text-gray-500">Digunakan</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-900">{{ $diskUsage['free'] ?? '-' }}</p>
                                    <p class="text-sm text-gray-500">Tersedia</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-900">{{ $diskUsage['percent_used'] ?? '-' }}%</p>
                                    <p class="text-sm text-gray-500">Persentase Digunakan</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 w-full bg-gray-200 rounded-full h-2.5">
                                <div 
                                    class="bg-{{ $diskUsage['percent_used'] > 80 ? 'red' : ($diskUsage['percent_used'] > 60 ? 'yellow' : 'emerald') }}-600 h-2.5 rounded-full" 
                                    style="width: {{ $diskUsage['percent_used'] ?? 0 }}%"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional System Metrics -->
                <div class="bg-gray-50 shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 bg-white sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Metrik Tambahan</h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Max Execution Time</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $serverInfo['max_execution_time'] ?? '-' }} detik</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Max Input Vars</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $serverInfo['max_input_vars'] ?? '-' }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Upload Max Filesize</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $serverInfo['upload_max_filesize'] ?? '-' }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Post Max Size</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $serverInfo['post_max_size'] ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Log Monitoring Section -->
                <div class="bg-gray-50 shadow overflow-hidden sm:rounded-lg mt-8">
                    <div class="px-4 py-5 bg-white sm:px-6 flex flex-wrap justify-between items-center gap-3">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">📊 Log Monitoring</h3>
                            <p class="mt-1 text-sm text-gray-500">Statistik dan monitoring log aplikasi</p>
                        </div>
                        <div class="flex gap-2">
                            <button
                                wire:click="toggleLogViewer"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                            >
                                <i class="fas fa-{{ $showLogViewer ? 'eye-slash' : 'eye' }} mr-2"></i>
                                {{ $showLogViewer ? 'Sembunyikan' : 'Tampilkan' }} Log
                            </button>
                            <button
                                wire:click="viewFullLogs"
                                class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                            >
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Dashboard Lengkap
                            </button>
                        </div>
                    </div>

                    <div class="border-t border-gray-200">
                        <!-- Log Statistics -->
                        <div class="px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                                <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                    <div class="text-3xl font-bold text-emerald-600">{{ $logStats['total_files'] ?? 0 }}</div>
                                    <div class="text-sm text-gray-500 mt-1">Total Log Files</div>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                    <div class="text-3xl font-bold text-blue-600">{{ $logStats['total_size'] ?? '0 B' }}</div>
                                    <div class="text-sm text-gray-500 mt-1">Total Size</div>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                    <div class="text-3xl font-bold text-purple-600">{{ $logStats['newest_log'] ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500 mt-1">Latest Log</div>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                    <div class="text-3xl font-bold {{ ($logStats['disk_used_percentage'] ?? 0) > 90 ? 'text-red-600' : 'text-gray-600' }}">
                                        {{ $logStats['disk_used_percentage'] ?? 0 }}%
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1">Disk Usage</div>
                                </div>
                            </div>

                            @if(($logStats['disk_used_percentage'] ?? 0) > 90)
                                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-red-700">
                                                <strong>Warning!</strong> Disk space is running low ({{ $logStats['disk_used_percentage'] }}%).
                                                <button wire:click="runLogCleanup" class="underline font-medium">Run cleanup now</button>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Recent Errors -->
                            @if(count($recentErrors) > 0)
                                <div class="bg-white border border-red-200 rounded-lg p-4 mb-6">
                                    <h4 class="text-md font-semibold text-gray-900 mb-3">
                                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                                        Recent Errors (Last 5)
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($recentErrors as $error)
                                            <div class="bg-red-50 border-l-2 border-red-500 p-3 rounded">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1 font-mono text-xs text-gray-700 break-all">
                                                        {{ $error['line'] }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 ml-2 flex-shrink-0">
                                                        {{ $error['time'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 text-center">
                                    <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                                    <p class="text-sm text-green-700 font-medium">No recent errors found! System is running smoothly.</p>
                                </div>
                            @endif

                            <!-- Log Viewer (Collapsible) -->
                            @if($showLogViewer)
                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center gap-3 mb-4">
                                        <label class="text-sm font-medium text-gray-700">Select Log Type:</label>
                                        <select wire:model.live="selectedLogType" class="rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                            @foreach($availableLogs as $log)
                                                <option value="{{ $log }}">{{ ucfirst($log) }}</option>
                                            @endforeach
                                        </select>
                                        <button
                                            wire:click="loadLogContent"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none"
                                        >
                                            <i class="fas fa-sync-alt mr-2"></i>
                                            Refresh
                                        </button>
                                    </div>

                                    <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto" style="max-height: 400px;">
                                        <pre class="text-green-400 text-xs font-mono whitespace-pre-wrap">{{ $logContent ?: 'No log content available or log file not found.' }}</pre>
                                    </div>

                                    <div class="mt-3 text-sm text-gray-500 text-center">
                                        Showing last 100 lines of {{ $selectedLogType }}.log
                                    </div>
                                </div>
                            @endif

                            <!-- Quick Actions -->
                            <div class="mt-6 flex gap-3">
                                <button
                                    wire:click="viewFullLogs"
                                    class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                                >
                                    <i class="fas fa-chart-line mr-2"></i>
                                    View Full Dashboard
                                </button>
                                <button
                                    wire:click="runLogCleanup"
                                    wire:confirm="Are you sure you want to cleanup logs older than 30 days?"
                                    class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
                                >
                                    <i class="fas fa-broom mr-2"></i>
                                    Run Log Cleanup (30 days)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>