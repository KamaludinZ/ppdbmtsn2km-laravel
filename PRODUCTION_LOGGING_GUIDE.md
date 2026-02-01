# 📊 Professional Logging & Monitoring Guide

## PPDB MTsN 2 Kota Malang - Production Logging System

**Date**: 30 Januari 2026
**Status**: ✅ Production Ready

---

## 📋 Table of Contents

1. [Overview](#overview)
2. [Log Channels](#log-channels)
3. [LoggerService Usage](#loggerservice-usage)
4. [Admin Log Monitoring](#admin-log-monitoring)
5. [Artisan Commands](#artisan-commands)
6. [Production Configuration](#production-configuration)
7. [Best Practices](#best-practices)
8. [Troubleshooting](#troubleshooting)

---

## Overview

Sistem logging profesional yang telah diimplementasikan dengan fitur:

✅ **Multiple Log Channels** - Pisahkan log berdasarkan kategori
✅ **Structured Logging** - Log dengan context lengkap
✅ **Auto Log Rotation** - Log otomatis ter-rotate berdasarkan tanggal
✅ **Admin Dashboard** - Monitor log via web interface
✅ **Log Cleanup Commands** - Artisan commands untuk maintenance
✅ **Smart Exception Handling** - Exception ter-route ke channel yang tepat
✅ **Performance Monitoring** - Deteksi slow queries dan responses

---

## Log Channels

### 1. **default** (laravel.log)
- **Purpose**: General application logs
- **Level**: Debug (development), Warning (production)
- **Retention**: 14 days (configurable)
- **Usage**: Default Laravel logging

```php
Log::info('User logged in', ['user_id' => $userId]);
```

### 2. **production** (production.log)
- **Purpose**: Production-specific logs
- **Level**: Warning and above only
- **Retention**: 30 days
- **Usage**: Production environment issues

```php
Log::channel('production')->warning('High memory usage detected');
```

### 3. **auth** (auth.log)
- **Purpose**: Authentication and authorization events
- **Level**: Info
- **Retention**: 90 days (security audit)
- **Usage**: Login, logout, failed auth attempts

```php
use App\Services\LoggerService;

LoggerService::logAuth('User login successful', [
    'email' => $user->email,
    'role' => $user->role
]);
```

### 4. **security** (security.log)
- **Purpose**: Security-related events
- **Level**: Warning
- **Retention**: 90 days
- **Usage**: Suspicious activities, failed access attempts

```php
LoggerService::logSecurity('Unauthorized access attempt', 'warning', [
    'attempted_route' => '/admin/dashboard',
    'user_role' => 'pendaftar'
]);
```

### 5. **activity** (activity.log)
- **Purpose**: User activity tracking
- **Level**: Info
- **Retention**: 30 days
- **Usage**: CRUD operations, user actions

```php
LoggerService::logActivity('create', 'Pendaftar', $pendaftar->id, [
    'nama' => $pendaftar->nama_lengkap,
    'jalur' => $pendaftar->jalur_pendaftaran->nama
]);
```

### 6. **payment** (payment.log)
- **Purpose**: Payment and transaction logs
- **Level**: Info
- **Retention**: 365 days (1 year for audit)
- **Usage**: Payment uploads, status changes

```php
LoggerService::logPayment('Payment uploaded', [
    'pendaftar_id' => $pendaftar->id,
    'amount' => $amount,
    'payment_type' => 'daftar_ulang'
]);
```

### 7. **database** (database.log)
- **Purpose**: Database queries and slow queries
- **Level**: Debug
- **Retention**: 7 days
- **Usage**: Automatic for slow queries (> 1 second)

```php
LoggerService::logQuery($query, $time, $bindings);
```

### 8. **critical** (critical.log)
- **Purpose**: Critical errors requiring immediate attention
- **Level**: Critical
- **Retention**: 90 days
- **Usage**: System failures, critical exceptions

```php
try {
    // Critical operation
} catch (\Throwable $e) {
    LoggerService::logCritical($e, [
        'operation' => 'process_payment',
        'pendaftar_id' => $id
    ]);
}
```

---

## LoggerService Usage

### Basic Logging Methods

#### 1. Log Authentication Events

```php
use App\Services\LoggerService;

// Successful login
LoggerService::logAuth('User login successful', [
    'email' => $user->email,
    'role' => $user->role
]);

// Failed login
LoggerService::logAuth('Login failed - invalid credentials', [
    'email' => $request->email
]);

// Logout
LoggerService::logAuth('User logout', [
    'session_duration' => $sessionDuration
]);
```

#### 2. Log Security Events

```php
// Unauthorized access
LoggerService::logSecurity('Unauthorized access attempt', 'warning', [
    'attempted_route' => $route,
    'required_role' => $requiredRole,
    'user_role' => $userRole
]);

// Suspicious activity
LoggerService::logSecurity('Multiple failed login attempts', 'warning', [
    'attempts' => $attempts,
    'email' => $email
]);

// Role escalation attempt
LoggerService::logSecurity('Role escalation attempt detected', 'error', [
    'user_id' => $userId,
    'attempted_role' => $attemptedRole
]);
```

#### 3. Log User Activity

```php
// Create
LoggerService::logActivity('create', 'Pendaftar', $pendaftar->id, [
    'nama' => $pendaftar->nama_lengkap,
    'jalur' => $pendaftar->jalur_pendaftaran->nama
]);

// Update
LoggerService::logActivity('update', 'Pendaftar', $pendaftar->id, [
    'changed_fields' => $changedFields,
    'old_values' => $oldValues,
    'new_values' => $newValues
]);

// Delete
LoggerService::logActivity('delete', 'User', $user->id, [
    'email' => $user->email,
    'role' => $user->role
]);

// View
LoggerService::logActivity('view', 'DetailPendaftar', $pendaftar->id);
```

#### 4. Log Payment Events

```php
// Payment upload
LoggerService::logPayment('Payment proof uploaded', [
    'pendaftar_id' => $pendaftar->id,
    'payment_type' => 'daftar_ulang_madrasah',
    'file_name' => $fileName,
    'file_size' => $fileSize
]);

// Payment verification
LoggerService::logPayment('Payment verified', [
    'pendaftar_id' => $pendaftar->id,
    'verified_by' => auth()->id(),
    'status' => 'approved'
]);

// Payment rejection
LoggerService::logPayment('Payment rejected', [
    'pendaftar_id' => $pendaftar->id,
    'rejected_by' => auth()->id(),
    'reason' => $reason
]);
```

#### 5. Log Critical Errors

```php
try {
    // Critical operation
    $result = $this->processImportantData();
} catch (\Throwable $e) {
    LoggerService::logCritical($e, [
        'operation' => 'process_important_data',
        'input_data' => $inputData,
        'affected_users' => $affectedUsers
    ]);

    // Handle error gracefully
    return back()->with('error', 'An error occurred. The team has been notified.');
}
```

#### 6. Custom Channel Logging

```php
// Log to custom channel with custom context
LoggerService::log('custom-channel', 'info', 'Custom event', [
    'event_type' => 'custom_event',
    'data' => $customData
]);
```

---

## Admin Log Monitoring

### Access Log Monitoring Dashboard

**URL**: `/admin/logs`
**Role Required**: Admin only

### Features:

✅ **Real-time Log Viewer** - View logs in real-time
✅ **Multiple Log Types** - Switch between different log channels
✅ **Filter by Level** - Filter by DEBUG, INFO, WARNING, ERROR, CRITICAL
✅ **Date Filter** - View logs for specific dates
✅ **Search** - Search log content
✅ **Download Logs** - Download log files
✅ **Clear/Delete Logs** - Manage log files
✅ **Auto-refresh** - Optional auto-refresh every 5 seconds
✅ **Statistics** - View log file statistics and disk usage
✅ **Recent Errors** - Quick view of last 10 errors

### Screenshots:

#### Dashboard Overview
```
┌─────────────────────────────────────────────────────┐
│ 📊 Log Monitoring                                   │
├─────────────────────────────────────────────────────┤
│                                                      │
│  [Total Files: 15]  [Size: 45 MB]  [Disk: 85%]     │
│                                                      │
│  🚨 Recent Errors: 3 errors in last 100 lines       │
│                                                      │
│  [Log Type ▼] [Level ▼] [Date] [Search] [Actions]  │
│                                                      │
│  Log Content:                                        │
│  ┌──────────────────────────────────────────────┐  │
│  │ [2026-01-30 14:30:15] local.INFO: User...   │  │
│  │ [2026-01-30 14:31:22] local.WARNING: Slow...│  │
│  │ ...                                           │  │
│  └──────────────────────────────────────────────┘  │
│                                                      │
│  ☑ Auto-refresh every 5s      Showing last 500 lines│
└─────────────────────────────────────────────────────┘
```

---

## Artisan Commands

### 1. Log Statistics

View log file statistics, sizes, and disk usage:

```bash
php artisan log:stats
```

**Output**:
```
Log Files Statistics:
┌─────────────────────┬─────────┬─────────────────────┬──────────────┐
│ File                │ Size    │ Modified            │ Age          │
├─────────────────────┼─────────┼─────────────────────┼──────────────┤
│ laravel.log         │ 15.5 MB │ 2026-01-30 14:30:15 │ 2 hours ago  │
│ auth.log            │ 5.2 MB  │ 2026-01-30 12:15:30 │ 4 hours ago  │
│ security.log        │ 2.1 MB  │ 2026-01-30 10:00:00 │ 6 hours ago  │
└─────────────────────┴─────────┴─────────────────────┴──────────────┘

Total Files: 15
Total Size: 45.8 MB

Disk Space:
  Free: 125.5 GB
  Used: 85.2%
```

### 2. Log Cleanup

Clean up old log files:

```bash
# Delete logs older than 30 days (default)
php artisan log:cleanup

# Delete logs older than 7 days
php artisan log:cleanup --days=7

# Dry run (show what would be deleted)
php artisan log:cleanup --dry-run

# Combination
php artisan log:cleanup --days=14 --dry-run
```

**Output**:
```
Cleaning up log files older than 30 days...

  - laravel-2025-12-15.log (2.5 MB)
  - laravel-2025-12-14.log (1.8 MB)
  - auth-2025-12-10.log (500 KB)

Total files to delete: 3
Total size to free: 4.8 MB

✓ Log cleanup completed successfully!
```

### 3. Schedule Log Cleanup (Recommended)

Add to `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule): void
{
    // Clean up logs older than 30 days, every day at 2 AM
    $schedule->command('log:cleanup --days=30')
             ->daily()
             ->at('02:00');

    // Or weekly cleanup
    $schedule->command('log:cleanup --days=30')
             ->weekly()
             ->sundays()
             ->at('02:00');
}
```

---

## Production Configuration

### .env Configuration for Production

```env
# Application Settings
APP_ENV=production
APP_DEBUG=false  # IMPORTANT: Never set to true in production!

# Logging Configuration
LOG_CHANNEL=stack
LOG_STACK=daily,critical  # Log to daily.log and critical.log
LOG_LEVEL=warning  # Only log warnings and above
LOG_DAILY_DAYS=30  # Keep logs for 30 days

# Optional: Slack Notifications
LOG_SLACK_WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL
LOG_SLACK_USERNAME="PPDB MTsN 2 Alerts"
LOG_SLACK_EMOJI=":rotating_light:"
```

### Recommended Production Stack

```env
# Option 1: Daily logs only
LOG_STACK=daily

# Option 2: Daily logs + Critical alerts
LOG_STACK=daily,critical

# Option 3: Multiple channels (advanced)
LOG_STACK=daily,auth,security,critical
```

### Log Levels Explained

| Level | Description | When to Use |
|-------|-------------|-------------|
| **DEBUG** | Detailed debug information | Development only |
| **INFO** | Informational messages | Normal operations |
| **WARNING** | Warning messages | Potential issues |
| **ERROR** | Error conditions | Errors that need attention |
| **CRITICAL** | Critical conditions | Critical errors |
| **ALERT** | Action must be taken immediately | Serious issues |
| **EMERGENCY** | System is unusable | System down |

---

## Best Practices

### 1. What to Log

✅ **DO LOG**:
- Authentication events (login, logout, failures)
- Authorization failures
- Payment transactions
- Data modifications (create, update, delete)
- File uploads
- Critical errors and exceptions
- Security events
- Slow queries (> 1 second)
- API requests and responses

❌ **DON'T LOG**:
- Passwords or sensitive credentials
- Personal identification numbers (use masked version)
- Credit card numbers
- Full stack traces in production (only in development)
- Validation errors (too noisy)
- Successful GET requests (unless debugging)

### 2. Log Context Best Practices

**Good Context**:
```php
LoggerService::logActivity('update', 'Pendaftar', $pendaftar->id, [
    'changed_fields' => ['status' => 'approved'],
    'previous_status' => 'pending',
    'updated_by' => auth()->id(),
    'ip_address' => request()->ip()
]);
```

**Bad Context**:
```php
Log::info('Updated'); // No context!
```

### 3. Sensitive Data Handling

```php
// ❌ BAD - Logs password
LoggerService::logAuth('Login attempt', [
    'email' => $email,
    'password' => $password  // NEVER DO THIS!
]);

// ✅ GOOD - No sensitive data
LoggerService::logAuth('Login attempt', [
    'email' => $email,
    'password_length' => strlen($password)  // OK to log length
]);

// ✅ GOOD - Masked sensitive data
LoggerService::logPayment('Payment processed', [
    'card_number' => '****' . substr($cardNumber, -4)  // Only last 4 digits
]);
```

### 4. Performance Considerations

```php
// ❌ BAD - Logs inside tight loop
foreach ($users as $user) {
    Log::info('Processing user', ['user_id' => $user->id]);
    // ... process user
}

// ✅ GOOD - Batch logging
$processedIds = [];
foreach ($users as $user) {
    // ... process user
    $processedIds[] = $user->id;
}
Log::info('Processed users', [
    'count' => count($processedIds),
    'user_ids' => $processedIds
]);
```

### 5. Error Handling Pattern

```php
public function updatePendaftar($id, $data)
{
    try {
        $pendaftar = Pendaftar::findOrFail($id);
        $oldData = $pendaftar->toArray();

        $pendaftar->update($data);

        LoggerService::logActivity('update', 'Pendaftar', $id, [
            'changed_fields' => array_keys($data),
            'old_values' => array_intersect_key($oldData, $data),
            'new_values' => $data
        ]);

        return $pendaftar;

    } catch (\Throwable $e) {
        LoggerService::logCritical($e, [
            'operation' => 'update_pendaftar',
            'pendaftar_id' => $id,
            'attempted_data' => $data
        ]);

        throw $e; // Re-throw for higher level handling
    }
}
```

---

## Monitoring & Alerting

### 1. Monitor Disk Usage

```bash
# Check disk usage daily
php artisan log:stats
```

**Set up alerts**:
- If disk usage > 90%: Run `php artisan log:cleanup`
- If disk usage > 95%: Emergency cleanup

### 2. Monitor Error Rates

Check critical.log regularly:

```bash
# View recent critical errors
tail -n 50 storage/logs/critical.log

# Count errors per day
grep -c "CRITICAL" storage/logs/critical-$(date +%Y-%m-%d).log
```

### 3. Slack Notifications (Optional)

Set up Slack webhook for critical errors:

```env
LOG_SLACK_WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL
LOG_STACK=daily,slack
```

Critical errors will be sent to Slack automatically.

### 4. Email Notifications (Optional)

Create a custom channel in `config/logging.php`:

```php
'mail' => [
    'driver' => 'monolog',
    'handler' => Monolog\Handler\NativeMailerHandler::class,
    'handler_with' => [
        'to' => 'admin@mtsn2kotamalang.sch.id',
        'subject' => 'Application Error',
    ],
    'level' => 'critical',
],
```

---

## Troubleshooting

### Issue: Log files too large

**Solution**:
```bash
# Clean up old logs
php artisan log:cleanup --days=7

# Or manually delete
rm storage/logs/*.log
```

### Issue: Disk space full

**Solution**:
```bash
# Emergency cleanup - delete all logs
rm storage/logs/*.log

# Or clean up old logs
php artisan log:cleanup --days=1
```

### Issue: Can't write to log file

**Solution**:
```bash
# Fix permissions (Linux/Mac)
chmod -R 775 storage/logs
chown -R www-data:www-data storage/logs

# Windows - Run as Administrator
icacls "C:\path\to\storage\logs" /grant Users:(OI)(CI)F /T
```

### Issue: Log monitoring page not loading

**Solution**:
```bash
# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Check if LogMonitoring component exists
php artisan about
```

### Issue: Logs not being written

**Check**:
1. Verify `.env` configuration
2. Check file permissions
3. Check disk space
4. Test logging:

```php
// Add to a test route
Log::info('Test log entry', ['test' => true]);
```

---

## Security Considerations

### 1. Log Access Control

- Only admins can access `/admin/logs`
- Log files should NOT be publicly accessible
- Add `.htaccess` protection if needed

### 2. Log Rotation

- Automatic rotation by date
- Old logs deleted after retention period
- Prevents disk space issues

### 3. Sensitive Data

- Never log passwords
- Mask credit card numbers
- Hash or encrypt if necessary

### 4. Production Debug Mode

```env
# ALWAYS false in production
APP_DEBUG=false
```

If `APP_DEBUG=true`, detailed errors will be shown to users (security risk!).

---

## Quick Reference

### Common Log Commands

```bash
# View stats
php artisan log:stats

# Clean up old logs
php artisan log:cleanup

# View recent logs
tail -f storage/logs/laravel.log

# Search logs
grep "ERROR" storage/logs/laravel.log

# Count errors
grep -c "ERROR" storage/logs/laravel-2026-01-30.log
```

### Common Log Channels

```php
// General logging
Log::info('Message');

// Authentication
LoggerService::logAuth('Event', $context);

// Security
LoggerService::logSecurity('Event', 'warning', $context);

// Activity
LoggerService::logActivity('action', 'Model', $id, $changes);

// Payment
LoggerService::logPayment('Event', $context);

// Critical
LoggerService::logCritical($exception, $context);
```

---

## Checklist: Production Deployment

Before deploying to production:

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Set `LOG_LEVEL=warning`
- [ ] Set `LOG_STACK=daily` or `daily,critical`
- [ ] Set `LOG_DAILY_DAYS=30` (or desired retention)
- [ ] Configure log cleanup schedule
- [ ] Test log monitoring dashboard
- [ ] Verify log file permissions
- [ ] Check disk space
- [ ] Optional: Configure Slack notifications
- [ ] Optional: Setup email alerts

---

## Support

**Documentation**: This file
**Log Monitoring**: `/admin/logs`
**Commands**: `php artisan log:stats`, `php artisan log:cleanup`

---

**Generated**: 30 Januari 2026
**Status**: ✅ Production Ready
**Version**: 1.0

🎉 **Professional logging system is ready for production!**
