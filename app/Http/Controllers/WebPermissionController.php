<?php

namespace App\Http\Controllers;

use App\Models\WebPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WebPermissionController extends Controller
{
    public function index()
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }
        $permissions = WebPermission::orderBy('group')->orderBy('name')->paginate(10);
        return view('web-permissions.index', compact('permissions'));
    }

    public function create()
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }
        return view('web-permissions.create');
    }

    public function store(Request $request)
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }
        
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:web_permissions,slug',
            'group' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        WebPermission::create($request->all());

        return redirect()->route('web-permissions.index')->with('success', 'Permission berhasil ditambahkan.');
    }

    public function edit($id)
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }
        $permission = WebPermission::findOrFail($id);
        return view('web-permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }
        
        $permission = WebPermission::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:web_permissions,slug,' . $permission->id,
            'group' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $permission->update($request->all());

        return redirect()->route('web-permissions.index')->with('success', 'Permission berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/');
        }
        $permission = WebPermission::findOrFail($id);
        $permission->delete();

        return redirect()->route('web-permissions.index')->with('success', 'Permission berhasil dihapus.');
    }
}
