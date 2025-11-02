<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display all notifications
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        $title = 'Notifikasi';
        
        return view('notifications.index', compact('notifications', 'title'));
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
        }
        
        return back()->with('success', 'Notifikasi berhasil ditandai sebagai dibaca');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return back()->with('success', 'Semua notifikasi berhasil ditandai sebagai dibaca');
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        
        if ($notification) {
            $notification->delete();
            return back()->with('success', 'Notifikasi berhasil dihapus');
        }
        
        return back()->with('error', 'Notifikasi tidak ditemukan');
    }

    /**
     * Delete all notifications
     */
    public function destroyAll()
    {
        Auth::user()->notifications()->delete();
        
        return back()->with('success', 'Semua notifikasi berhasil dihapus');
    }

    /**
     * Get unread notification count (for AJAX)
     */
    public function unreadCount()
    {
        $count = Auth::user()->unreadNotifications->count();
        
        return response()->json(['count' => $count]);
    }
}