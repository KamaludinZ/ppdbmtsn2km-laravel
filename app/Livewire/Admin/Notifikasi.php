<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Notifikasi extends Component
{
    use WithPagination;

    public $filter = 'all'; // all, unread, read
    public $selectedNotifications = [];
    public $selectAll = false;

    protected $queryString = ['filter'];

    public function mount()
    {
        $this->filter = request()->query('filter', 'all');
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedNotifications = $this->getNotifications()->pluck('id')->toArray();
        } else {
            $this->selectedNotifications = [];
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            $this->dispatch('notification-updated');
        }
    }

    public function markAsUnread($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->update(['read_at' => null]);
            $this->dispatch('notification-updated');
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->dispatch('notification-updated');
        session()->flash('message', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }

    public function markSelectedAsRead()
    {
        if (count($this->selectedNotifications) > 0) {
            Auth::user()->notifications()
                ->whereIn('id', $this->selectedNotifications)
                ->update(['read_at' => now()]);

            $this->selectedNotifications = [];
            $this->selectAll = false;
            $this->dispatch('notification-updated');
            session()->flash('message', 'Notifikasi yang dipilih telah ditandai sebagai sudah dibaca.');
        }
    }

    public function deleteNotification($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->delete();
            $this->dispatch('notification-updated');
            session()->flash('message', 'Notifikasi berhasil dihapus.');
        }
    }

    public function deleteSelected()
    {
        if (count($this->selectedNotifications) > 0) {
            Auth::user()->notifications()
                ->whereIn('id', $this->selectedNotifications)
                ->delete();

            $this->selectedNotifications = [];
            $this->selectAll = false;
            $this->dispatch('notification-updated');
            session()->flash('message', 'Notifikasi yang dipilih telah dihapus.');
        }
    }

    public function deleteAll()
    {
        Auth::user()->notifications()->delete();
        $this->selectedNotifications = [];
        $this->selectAll = false;
        $this->dispatch('notification-updated');
        session()->flash('message', 'Semua notifikasi telah dihapus.');
    }

    public function getNotifications()
    {
        $query = Auth::user()->notifications();

        if ($this->filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($this->filter === 'read') {
            $query->whereNotNull('read_at');
        }

        return $query->latest()->get();
    }

    public function render()
    {
        $notifications = $this->getNotifications();
        $unreadCount = Auth::user()->unreadNotifications()->count();

        $role = Auth::user()->role;

        return view('livewire.admin.notifikasi', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ])->layout('components.layouts.dashboard', [
            'role' => $role,
            'pageTitle' => 'Notifikasi',
            'pageDescription' => 'Kelola dan lihat semua notifikasi Anda'
        ]);
    }
}
