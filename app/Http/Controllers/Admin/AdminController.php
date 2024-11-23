<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller {

    public function index(): View {
        $totalRooms = Room::sum('total_room');
        $reservedRoom = Order::whereDate('check_in', '>=', Carbon::now())->count();
        return view('admin.index', compact('totalRooms', 'reservedRoom'));
    }
}
