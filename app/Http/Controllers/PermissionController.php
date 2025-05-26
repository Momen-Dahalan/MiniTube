<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
       // عرض جميع الصلاحيات
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    // عرض نموذج إضافة صلاحية جديدة
    public function create()
    {
        return view('admin.permissions.create');
    }

    // حفظ صلاحية جديدة
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web', // مهم إذا تستخدم guards
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'تم إضافة الصلاحية بنجاح');
    }

    // عرض نموذج تعديل صلاحية موجودة
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    // تحديث صلاحية موجودة
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'تم تحديث الصلاحية بنجاح');
    }

    // حذف صلاحية
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'تم حذف الصلاحية بنجاح');
    }
}
