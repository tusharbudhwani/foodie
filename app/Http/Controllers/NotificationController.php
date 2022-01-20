<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        auth()->user()->unreadNotifications->markAsRead();

        $notifications = auth()->user()->notifications()->get();
        $products = [];
        $dates = [];
        foreach($notifications as $notification) {
            array_push($dates, $notification->created_at);
            array_push($products, Product::where('id', $notification->product_id)->first());
        }
        return view('notifications.index', compact(['notifications', 'products', 'dates']));
    }
}
