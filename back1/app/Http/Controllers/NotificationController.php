<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Liste des notifications de l'utilisateur connecté
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $nonLues = Notification::where('user_id', $user->id)
            ->where('lu', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'total_non_lues' => $nonLues
        ]);
    }

    /**
     * Notifications non lues
     */
    public function nonLues(Request $request)
    {
        $user = $request->user();

        $notifications = Notification::where('user_id', $user->id)
            ->where('lu', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count()
        ]);
    }

    /**
     * Marquer une notification comme lue
     */
    public function marquerCommeLu($id, Request $request)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $notification->marquerCommeLu();

        return response()->json([
            'message' => 'Notification marquée comme lue',
            'notification' => $notification
        ]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function marquerToutCommeLu(Request $request)
    {
        $this->notificationService->marquerToutCommeLu($request->user());

        return response()->json([
            'message' => 'Toutes les notifications ont été marquées comme lues'
        ]);
    }

    /**
     * Supprimer une notification
     */
    public function destroy($id, Request $request)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $notification->delete();

        return response()->json([
            'message' => 'Notification supprimée'
        ]);
    }

    /**
     * Nombre de notifications non lues
     */
    public function count(Request $request)
    {
        $count = Notification::where('user_id', $request->user()->id)
            ->where('lu', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
