<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">📊 Log Monitoring</h4>
                    <p class="text-muted mb-0">Monitor dan analisis log aplikasi</p>
                </div>
                <div>
                    <button wire:click="refresh" class="btn btn-sm btn-primary">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary rounded-circle p-3">
                                <i class="fas fa-file-alt text-primary fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Log Files</h6>
                            <h4 class="mb-0">{{ $logStats['total_files'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success rounded-circle p-3">
                                <i class="fas fa-database text-success fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Size</h6>
                            <h4 class="mb-0">{{ $logStats['total_size'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info rounded-circle p-3">
                                <i class="fas fa-hdd text-info fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Disk Free</h6>
                            <h4 class="mb-0">{{ $logStats['disk_free'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-{{ $logStats['disk_used_percentage'] > 90 ? 'danger' : 'warning' }} rounded-circle p-3">
                                <i class="fas fa-chart-pie text-{{ $logStats['disk_used_percentage'] > 90 ? 'danger' : 'warning' }} fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Disk Usage</h6>
                            <h4 class="mb-0">{{ $logStats['disk_used_percentage'] }}%</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($logStats['disk_used_percentage'] > 90)
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Warning!</strong> Disk space is running low. Consider running: <code>php artisan log:cleanup</code>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Recent Errors -->
    @if(count($recentErrors) > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm border-start border-danger border-4">
                    <div class="card-header bg-transparent">
                        <h6 class="mb-0">🚨 Recent Errors (Last 10)</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <tbody>
                                    @foreach($recentErrors as $error)
                                        <tr>
                                            <td class="font-monospace small text-danger">{{ Str::limit($error, 150) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Log Viewer -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label class="form-label mb-1">Log Type</label>
                            <select wire:model.live="selectedLog" class="form-select form-select-sm">
                                @foreach($availableLogs as $log)
                                    <option value="{{ $log }}">{{ ucfirst($log) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label mb-1">Level</label>
                            <select wire:model.live="selectedLevel" class="form-select form-select-sm">
                                <option value="all">All Levels</option>
                                <option value="debug">Debug</option>
                                <option value="info">Info</option>
                                <option value="warning">Warning</option>
                                <option value="error">Error</option>
                                <option value="critical">Critical</option>
                                <option value="emergency">Emergency</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label mb-1">Date</label>
                            <input type="date" wire:model.live="selectedDate" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label mb-1">Search</label>
                            <input type="text" wire:model.live.debounce.500ms="searchTerm"
                                   class="form-control form-control-sm"
                                   placeholder="Search logs...">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label mb-1">&nbsp;</label>
                            <div class="btn-group d-flex" role="group">
                                <button wire:click="downloadLog" class="btn btn-sm btn-success" title="Download Log">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button wire:click="clearLog"
                                        onclick="return confirm('Are you sure you want to clear this log file?')"
                                        class="btn btn-sm btn-warning"
                                        title="Clear Log">
                                    <i class="fas fa-eraser"></i>
                                </button>
                                <button wire:click="deleteLog"
                                        onclick="return confirm('Are you sure you want to delete this log file?')"
                                        class="btn btn-sm btn-danger"
                                        title="Delete Log">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="log-viewer" style="max-height: 600px; overflow-y: auto;">
                        <pre class="p-3 mb-0 bg-dark text-light font-monospace small">{{ $logContent ?: 'No log content available' }}</pre>
                    </div>
                </div>

                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   wire:model.live="autoRefresh"
                                   id="autoRefresh">
                            <label class="form-check-label" for="autoRefresh">
                                Auto-refresh every {{ $refreshInterval }}s
                            </label>
                        </div>
                        <small class="text-muted">
                            Showing last 500 lines
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-refresh script -->
    @if($autoRefresh)
        <script>
            setInterval(() => {
                @this.call('refresh');
            }, {{ $refreshInterval * 1000 }});
        </script>
    @endif

    <style>
        .log-viewer pre {
            line-height: 1.4;
        }

        .log-viewer pre::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .log-viewer pre::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .log-viewer pre::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .log-viewer pre::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</div>
