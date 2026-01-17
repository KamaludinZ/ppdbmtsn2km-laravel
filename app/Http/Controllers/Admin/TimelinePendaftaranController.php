<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimelinePendaftaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TimelinePendaftaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(TimelinePendaftaran::class, 'timeline');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timelines = TimelinePendaftaran::with('pendaftar')->orderBy('urutan')->get();
        $jalurOptions = [
            'prestasi' => 'Prestasi',
            'reguler' => 'Reguler',
            'tahfidz' => 'Tahfidz',
        ];

        return view('admin.timeline-pendaftaran.index', compact('timelines', 'jalurOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jalurOptions = [
            'prestasi' => 'Prestasi',
            'reguler' => 'Reguler',
            'tahfidz' => 'Tahfidz',
        ];

        $warnaOptions = [
            'blue' => 'Biru',
            'emerald' => 'Hijau Muda',
            'purple' => 'Ungu',
            'indigo' => 'Indigo',
            'orange' => 'Oranye',
            'teal' => 'Teal',
        ];

        return view('admin.timeline-pendaftaran.create', compact('jalurOptions', 'warnaOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_kegiatan' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
            'tempat' => 'nullable|string|max:200',
            'jalur_kode' => ['required', Rule::in(['prestasi', 'reguler', 'tahfidz'])],
            'warna' => ['required', Rule::in(['blue', 'emerald', 'purple', 'indigo', 'orange', 'teal'])],
            'aktif' => 'boolean',
            'urutan' => 'integer|min:0',
        ]);

        TimelinePendaftaran::create($request->all());

        return redirect()->route('admin.timeline-pendaftaran.index')->with('success', 'Timeline pendaftaran berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TimelinePendaftaran $timeline)
    {
        $jalurOptions = [
            'prestasi' => 'Prestasi',
            'reguler' => 'Reguler',
            'tahfidz' => 'Tahfidz',
        ];

        $warnaOptions = [
            'blue' => 'Biru',
            'emerald' => 'Hijau Muda',
            'purple' => 'Ungu',
            'indigo' => 'Indigo',
            'orange' => 'Oranye',
            'teal' => 'Teal',
        ];

        return view('admin.timeline-pendaftaran.edit', compact('timeline', 'jalurOptions', 'warnaOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TimelinePendaftaran $timeline)
    {
        $request->validate([
            'judul_kegiatan' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
            'tempat' => 'nullable|string|max:200',
            'jalur_kode' => ['required', Rule::in(['prestasi', 'reguler', 'tahfidz'])],
            'warna' => ['required', Rule::in(['blue', 'emerald', 'purple', 'indigo', 'orange', 'teal'])],
            'aktif' => 'boolean',
            'urutan' => 'integer|min:0',
        ]);

        $timeline->update($request->all());

        return redirect()->route('admin.timeline-pendaftaran.index')->with('success', 'Timeline pendaftaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimelinePendaftaran $timeline)
    {
        $timeline->delete();

        return redirect()->route('admin.timeline-pendaftaran.index')->with('success', 'Timeline pendaftaran berhasil dihapus.');
    }
}
