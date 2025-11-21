<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Room;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    public function index()
    {
        $showtimes = Showtime::with(['movie', 'room.cinema'])->orderBy('date', 'desc')->orderBy('start_time', 'desc')->get();
        return view('admin.showtimes.index', compact('showtimes'));
    }
    
    public function create()
    {
        $movies = Movie::all();
        $rooms = Room::with('cinema')->get();
        return view('admin.showtimes.create', compact('movies', 'rooms'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:ACTIVE,CANCELLED,COMPLETED',
        ]);
        
        // Check for conflicts
        $conflict = Showtime::where('room_id', $request->room_id)
            ->where('date', $request->date)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();
            
        if ($conflict) {
            return back()->withErrors(['start_time' => 'Thời gian chiếu bị trùng với suất chiếu khác.']);
        }
        
        Showtime::create($request->all());
        
        return redirect()->route('admin.showtimes.index')
            ->with('success', 'Lịch chiếu đã được tạo thành công!');
    }
    
    public function show(Showtime $showtime)
    {
        $showtime->load(['movie', 'room.cinema']);
        return view('admin.showtimes.show', compact('showtime'));
    }
    
    public function edit(Showtime $showtime)
    {
        $movies = Movie::all();
        $rooms = Room::with('cinema')->get();
        return view('admin.showtimes.edit', compact('showtime', 'movies', 'rooms'));
    }
    
    public function update(Request $request, Showtime $showtime)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:ACTIVE,CANCELLED,COMPLETED',
        ]);
        
        // Check for conflicts (excluding current showtime)
        $conflict = Showtime::where('room_id', $request->room_id)
            ->where('date', $request->date)
            ->where('id', '!=', $showtime->id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();
            
        if ($conflict) {
            return back()->withErrors(['start_time' => 'Thời gian chiếu bị trùng với suất chiếu khác.']);
        }
        
        $showtime->update($request->all());
        
        return redirect()->route('admin.showtimes.index')
            ->with('success', 'Lịch chiếu đã được cập nhật thành công!');
    }
    
    public function destroy(Showtime $showtime)
    {
        $showtime->delete();
        
        return redirect()->route('admin.showtimes.index')
            ->with('success', 'Lịch chiếu đã được xóa thành công!');
    }
}
