<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use Illuminate\Http\Request;

class CinemaController extends Controller
{
    public function index()
    {
        $cinemas = Cinema::paginate(10);
        return view('admin.cinemas.index', compact('cinemas'));
    }
    
    public function create()
    {
        return view('admin.cinemas.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        Cinema::create($request->all());
        
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
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $cinema->update($request->all());
        
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
