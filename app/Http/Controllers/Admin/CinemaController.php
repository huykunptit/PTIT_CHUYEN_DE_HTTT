<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use Illuminate\Http\Request;

class CinemaController extends Controller
{
    public function index(Request $request)
    {
        $cinemas = Cinema::orderBy('created_at', 'desc')->get();
        $selectedCity = $request->get('city');

        return view('admin.cinemas.index', compact('cinemas', 'selectedCity'));
    }
    
    public function create()
    {
        return view('admin.cinemas.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|in:HCM,HN',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->boolean('is_active');
        
        Cinema::create($validated);
        
        return redirect()->route('admin.cinemas.index')
            ->with('success', 'Rạp chiếu đã được thêm thành công!');
    }
    
    public function show(Cinema $cinema)
    {
        return view('admin.cinemas.show', compact('cinema'));
    }
    
    public function edit(Cinema $cinema)
    {
        return view('admin.cinemas.edit', compact('cinema'));
    }
    
    public function update(Request $request, Cinema $cinema)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|in:HCM,HN',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->boolean('is_active');
        
        $cinema->update($validated);
        
        return redirect()->route('admin.cinemas.index')
            ->with('success', 'Rạp chiếu đã được cập nhật thành công!');
    }
    
    public function destroy(Cinema $cinema)
    {
        $cinema->delete();
        
        return redirect()->route('admin.cinemas.index')
            ->with('success', 'Rạp chiếu đã được xóa thành công!');
    }
}
