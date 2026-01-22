# Enum Status Pendaftaran Fix
Date: 2026-01-18

## Problem
Database error when accessing `/cek-nsm-npsn` route:
```
SQLSTATE[22P02]: Invalid text representation: 7 ERROR: invalid input value for enum pendaftar_status_pendaftaran_final: "draft"
```

## Root Cause
The route was trying to use `'draft'` as a value for `status_pendaftaran`, but this value doesn't exist in the PostgreSQL enum type `pendaftar_status_pendaftaran_final`.

## Investigation
1. Checked the actual enum values in database:
   - menunggu_verifikasi
   - lengkapi_data
   - perbaiki_data
   - data_ditolak
   - data_disetujui
   - lulus
   - tidak_lulus
   - cadangan
   - diterima
   - ditolak
   - menunggu
   - baru
   - diproses
   - dicabut
   - selesai

2. The code was expecting: 'draft', 'menunggu', 'verifikasi', 'diterima', 'ditolak'

3. Actual values in use:
   - menunggu_verifikasi: 13 records (corresponds to draft/initial status)
   - lengkapi_data: 1 record
   - data_ditolak: 4 records
   - data_disetujui: 5 records
   - lulus: 1 record

## Solution
Changed the query in `routes/web.php` line 155:
```php
// Before:
->where('status_pendaftaran', '!=', 'draft')

// After:
->where('status_pendaftaran', '!=', 'menunggu_verifikasi')
```

## Files Modified
- `routes/web.php` - Fixed the enum value from 'draft' to 'menunggu_verifikasi'

## Status Mapping
Based on the application code analysis:
- Initial/Draft status: `menunggu_verifikasi`
- Data needs completion: `lengkapi_data`
- Data needs correction: `perbaiki_data`
- Data rejected: `data_ditolak`
- Data approved: `data_disetujui`
- Passed: `lulus`
- Failed: `tidak_lulus`
- Waitlisted: `cadangan`
- Accepted: `diterima`
- Rejected: `ditolak`

## Verification
The application code in Livewire controllers is already using the correct enum values (menunggu_verifikasi, data_disetujui, etc.), so no further changes are needed.

## Result
✅ The `/cek-nsm-npsn` route should now work without database errors.