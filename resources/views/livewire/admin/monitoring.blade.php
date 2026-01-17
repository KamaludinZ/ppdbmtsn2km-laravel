<div>
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
            </div>
        </div>
    </div>
</div>