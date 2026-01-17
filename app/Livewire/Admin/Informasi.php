<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Models\Faq;
use App\Models\KontenInformasi;
use Livewire\Component;
use Livewire\WithPagination;

class Informasi extends Component
{
    use WithPagination;

    public $content;
    public $search = '';
    public $perPage = 10;

    // FAQ properties
    public $faq_id;
    public $pertanyaan;
    public $jawaban;
    public $urutan_faq = 0;
    public $aktif_faq = true;
    public $showFaqForm = false;
    public $isEditingFaq = false;

    // Konten Informasi properties
    public $konten_id;
    public $judul;
    public $isi;
    public $urutan = 0;
    public $aktif = true;
    public $showKontenForm = false;
    public $isEditingKonten = false;

    // Search & Filter untuk konten
    public $searchKonten = '';
    public $perPageKonten = 10;

    public function mount()
    {
        $this->content = Setting::get('page_informasi_content', '');
    }

    public function save()
    {
        $this->validate([
            'content' => 'required|string',
        ]);

        Setting::set('page_informasi_content', $this->content, 'text', 'Konten Halaman Informasi & Panduan');

        $this->dispatch('saved', message: 'Halaman Informasi berhasil diperbarui!');
    }

    // ===== KONTEN INFORMASI METHODS =====
    public function createKonten()
    {
        $this->resetKontenFields();
        $this->isEditingKonten = false;
        $this->showKontenForm = true;
    }

    public function editKonten($id)
    {
        $konten = KontenInformasi::find($id);
        $this->konten_id = $konten->id;
        $this->judul = $konten->judul;
        $this->isi = $konten->isi;
        $this->urutan = $konten->urutan;
        $this->aktif = $konten->aktif;

        $this->isEditingKonten = true;
        $this->showKontenForm = true;
    }

    public function saveKonten()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'urutan' => 'integer|min:0',
            'aktif' => 'boolean',
        ]);

        if ($this->isEditingKonten && $this->konten_id) {
            $konten = KontenInformasi::find($this->konten_id);
            $konten->update([
                'judul' => $this->judul,
                'isi' => $this->isi,
                'urutan' => $this->urutan,
                'aktif' => $this->aktif ?? true,
            ]);
            $this->dispatch('saved', message: 'Konten berhasil diperbarui.');
        } else {
            KontenInformasi::create([
                'judul' => $this->judul,
                'isi' => $this->isi,
                'urutan' => $this->urutan ?? 0,
                'aktif' => $this->aktif ?? true,
            ]);
            $this->dispatch('saved', message: 'Konten berhasil ditambahkan.');
        }

        $this->showKontenForm = false;
        $this->resetKontenFields();
    }

    public function deleteKonten($id)
    {
        $konten = KontenInformasi::find($id);
        if ($konten) {
            $konten->delete();
            $this->dispatch('saved', message: 'Konten berhasil dihapus.');
        }
    }

    public function cancelKonten()
    {
        $this->showKontenForm = false;
        $this->resetKontenFields();
    }

    private function resetKontenFields()
    {
        $this->konten_id = null;
        $this->judul = '';
        $this->isi = '';
        $this->urutan = 0;
        $this->aktif = true;
    }

    // ===== FAQ METHODS =====
    public function createFaq()
    {
        $this->resetFaqFields();
        $this->isEditingFaq = false;
        $this->showFaqForm = true;
    }

    public function editFaq($id)
    {
        $faq = Faq::find($id);
        $this->faq_id = $faq->id;
        $this->pertanyaan = $faq->pertanyaan;
        $this->jawaban = $faq->jawaban;
        $this->urutan_faq = $faq->urutan;
        $this->aktif_faq = $faq->aktif;

        $this->isEditingFaq = true;
        $this->showFaqForm = true;
    }

    public function saveFaq()
    {
        $this->validate([
            'pertanyaan' => 'required|string|max:500',
            'jawaban' => 'required|string',
            'urutan_faq' => 'integer|min:0',
            'aktif_faq' => 'boolean',
        ]);

        if ($this->isEditingFaq && $this->faq_id) {
            $faq = Faq::find($this->faq_id);
            $faq->update([
                'pertanyaan' => $this->pertanyaan,
                'jawaban' => $this->jawaban,
                'urutan' => $this->urutan_faq,
                'aktif' => $this->aktif_faq ?? true,
            ]);
            $this->dispatch('saved', message: 'FAQ berhasil diperbarui.');
        } else {
            Faq::create([
                'pertanyaan' => $this->pertanyaan,
                'jawaban' => $this->jawaban,
                'urutan' => $this->urutan_faq ?? 0,
                'aktif' => $this->aktif_faq ?? true,
            ]);
            $this->dispatch('saved', message: 'FAQ berhasil ditambahkan.');
        }

        $this->showFaqForm = false;
        $this->resetFaqFields();
    }

    public function deleteFaq($id)
    {
        $faq = Faq::find($id);
        if ($faq) {
            $faq->delete();
            $this->dispatch('saved', message: 'FAQ berhasil dihapus.');
        }
    }

    public function cancelFaq()
    {
        $this->showFaqForm = false;
        $this->resetFaqFields();
    }

    private function resetFaqFields()
    {
        $this->faq_id = null;
        $this->pertanyaan = '';
        $this->jawaban = '';
        $this->urutan_faq = 0;
        $this->aktif_faq = true;
    }

    public function render()
    {
        // Query konten informasi
        $kontenInformasi = KontenInformasi::when($this->searchKonten, function ($q) {
                $q->where('judul', 'like', '%' . $this->searchKonten . '%')
                  ->orWhere('isi', 'like', '%' . $this->searchKonten . '%');
            })
            ->orderBy('urutan', 'asc')
            ->paginate($this->perPageKonten, ['*'], 'kontenPage');

        // Query FAQ
        $faqs = Faq::when($this->search, function ($q) {
                $q->where('pertanyaan', 'like', '%' . $this->search . '%')
                  ->orWhere('jawaban', 'like', '%' . $this->search . '%');
            })
            ->orderBy('urutan', 'asc')
            ->paginate($this->perPage, ['*'], 'faqPage');

        return view('livewire.admin.informasi', [
            'kontenInformasi' => $kontenInformasi,
            'faqs' => $faqs
        ])
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Manajemen Informasi & Panduan',
                'pageDescription' => 'Edit konten halaman informasi, panduan dan FAQ pendaftaran'
            ]);
    }
}
