<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Cinema;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('cinema')->orderBy('created_at', 'desc')->get();
        return view('admin.rooms.index', compact('rooms'));
    }
    
    public function create()
    {
        $cinemas = Cinema::where('is_active', true)->get();
        return view('admin.rooms.create', compact('cinemas'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'cinema_id' => 'required|exists:cinemas,id',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:500',
            'type' => 'required|in:STANDARD,VIP,IMAX,4DX',
            'is_active' => 'boolean',
        ]);
        
        Room::create($request->all());
        
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Phòng chiếu đã được thêm thành công!');
    }
    
    public function show(Room $room)
    {
        $room->load(['cinema', 'seats', 'showtimes.movie']);
        return view('admin.rooms.show', compact('room'));
    }
    
    public function edit(Room $room)
    {
        $cinemas = Cinema::where('is_active', true)->get();
        return view('admin.rooms.edit', compact('room', 'cinemas'));
    }
    
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'cinema_id' => 'required|exists:cinemas,id',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:500',
            'type' => 'required|in:STANDARD,VIP,IMAX,4DX',
            'is_active' => 'boolean',
        ]);
        
        $room->update($request->all());
        
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Phòng chiếu đã được cập nhật thành công!');
    }
    
    public function destroy(Room $room)
    {
        // Kiểm tra nếu có showtime đang sử dụng phòng này
        if ($room->showtimes()->where('date', '>=', now()->toDateString())->exists()) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Không thể xóa phòng chiếu đang có lịch chiếu trong tương lai!');
        }
        
        $room->delete();
        
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Phòng chiếu đã được xóa thành công!');
    }
}
