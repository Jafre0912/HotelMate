<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PageController extends Controller {

    public function index(): View {

        $rooms = Room::with('roomtype')->where('status', 1)->get();
        return view('pages.home', compact('rooms'));
    }

    public function list_rooms() {

        $rooms = Room::with('roomtype')->where('status', 1)->get();
        return view('pages.list-rooms', compact('rooms'));
    }

    public function search(Request $request) {
        // Validate the input data
        $validatedData = $request->validate([
            'check_in' => ['required', 'date', 'after:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'no_peron' => ['required']
        ]);
    
        // Get rooms that are available during the specified dates and have enough availability
        $rooms = Room::with('roomtype')
            ->where('status', 1)
            ->whereDoesntHave('orders', function (Builder $query) use ($validatedData) {
                // Filter out rooms that have existing bookings during the given check-in/check-out period
                $query->whereBetween('check_in', [$validatedData['check_in'], $validatedData['check_out']])
                      ->orWhereBetween('check_out', [$validatedData['check_in'], $validatedData['check_out']]);
            })
            ->get()
            ->filter(function($room) use ($validatedData) {
                // Check if the room has enough available slots
                $ordersCount = $room->orders()
                                    ->whereBetween('check_in', [$validatedData['check_in'], $validatedData['check_out']])
                                    ->orWhereBetween('check_out', [$validatedData['check_in'], $validatedData['check_out']])
                                    ->count();
    
                return $room->total_room > $ordersCount; // Only include rooms with enough availability
            });
    
        // Set up variables to pass to the view
        $searched = true;
        $fields = $validatedData;
    
        // Return the view with available rooms
        return view('pages.list-rooms', compact('rooms', 'searched', 'fields'));
    }       
    public function showProfile() {
        return view('pages.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request) {
        $user = Auth::user();
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->save();

        return redirect()->route('profile');
    }
}
