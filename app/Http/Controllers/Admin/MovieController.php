<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::paginate(10);
        return view('admin.movies.index', compact('movies'));
    }
    
    public function create()
    {
        return view('admin.movies.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'genre' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'age_rating' => 'required|integer|in:0,13,16,18',
            'release_date' => 'required|date',
            'end_date' => 'required|date|after:release_date',
            'status' => 'required|in:COMING_SOON,NOW_SHOWING,ENDED',
            'rating' => 'nullable|numeric|min:0|max:10',
            'trailer_url' => 'nullable|url',
            'is_featured' => 'boolean',
        ]);
        
        Movie::create($request->all());
        
        return redirect()->route('admin.movies.index')
            ->with('success', 'Phim đã được thêm thành công!');
    }
    
    public function show(Movie $movie)
    {
        return view('admin.movies.show', compact('movie'));
    }
    
    public function edit(Movie $movie)
    {
        return view('admin.movies.edit', compact('movie'));
    }
    
    public function update(Request $request, Movie $movie)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'genre' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'age_rating' => 'required|integer|in:0,13,16,18',
            'release_date' => 'required|date',
            'end_date' => 'required|date|after:release_date',
            'status' => 'required|in:COMING_SOON,NOW_SHOWING,ENDED',
            'rating' => 'nullable|numeric|min:0|max:10',
            'trailer_url' => 'nullable|url',
            'is_featured' => 'boolean',
        ]);
        
        $movie->update($request->all());
        
        return redirect()->route('admin.movies.index')
            ->with('success', 'Phim đã được cập nhật thành công!');
    }
    
    public function destroy(Movie $movie)
    {
        $movie->delete();
        
        return redirect()->route('admin.movies.index')
            ->with('success', 'Phim đã được xóa thành công!');
    }
}
