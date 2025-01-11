<?php

namespace App\Http\Controllers;

use App\Http\Resources\Notification\NotificationsCollection;
use App\Http\Resources\Notification\NotificationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getUnreadNotifications(Request $request): JsonResponse
    {
        $user = auth()->user();
        $notifications = $user->unreadNotifications()->paginate(10);
        $user->unreadNotifications->markAsRead();
        return response()->json(NotificationsCollection::make($notifications));
    }

    public function getAllNotifications(Request $request): JsonResponse
    {
        $user = auth()->user();
        $notifications = $user->notifications()->paginate(10);
        return response()->json(NotificationsCollection::make($notifications));
    }
}
