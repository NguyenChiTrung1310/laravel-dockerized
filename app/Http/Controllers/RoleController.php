<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Roles/Index', [
                'roles' => Role::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Roles/Create', [
            "permissions" => Permission::pluck('name')->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name]);

        $role->syncPermissions($request->permissions);
        return to_route('roles.index')->with('success', 'Role created successfully.');}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('Roles/Show', [
            'role' => Role::with('permissions')->findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);

        return Inertia::render('Roles/Edit', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions()->pluck('name')->all()
            ],
            'permissions' => Permission::pluck('name')->all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return to_route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
