<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
   public function index()
    {
        $users = User::with('roles')->paginate(15);
        $roles = Role::all(); // لجلب كل الأدوار عشان نستخدمها في تعديل الدور
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->syncRoles($request->role);

        return redirect()->back()->with('success', 'تم تحديث الدور بنجاح');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'تم حذف المستخدم بنجاح');
    }

}
