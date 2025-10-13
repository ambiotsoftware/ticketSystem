<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EmailNotification;
use Illuminate\Http\Request;

class EmailNotificationController extends Controller
{
    /**
     * Mostrar todas las notificaciones (solo para admins)
     */
    public function index()
    {
        // Solo administradores pueden ver las notificaciones
        if (auth()->user()->role !== 'admin') {
            abort(403, 'No tienes permisos para ver las notificaciones.');
        }

        $notifications = EmailNotification::with('ticket.client')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Marcar notificaciones como enviadas (simulación)
     */
    public function markAsSent(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $notificationIds = $request->input('notification_ids', []);
        
        if (empty($notificationIds)) {
            return back()->with('error', 'No se seleccionaron notificaciones.');
        }

        $updated = EmailNotification::whereIn('id', $notificationIds)
            ->where('sent', false)
            ->update([
                'sent' => true,
                'sent_at' => now()
            ]);

        return back()->with('success', "Se marcaron {$updated} notificaciones como enviadas.");
    }

    /**
     * Ver el contenido de una notificación específica
     */
    public function show(EmailNotification $notification)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $notification->load('ticket.client');
        
        return view('admin.notifications.show', compact('notification'));
    }
}
