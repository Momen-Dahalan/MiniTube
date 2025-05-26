<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // التحقق من صحة البيانات
    $request->validate([
        'name_ar' => 'required|string|max:255',
        'name_en' => 'required|string|max:255',
    ]);

    // إنشاء التصنيف
    Category::create([
        'name_ar' => $request->name_ar,
        'name_en' => $request->name_en,
    ]);

    // إعادة التوجيه مع رسالة نجاح
    return redirect()->route('admin.categories.index')
        ->with('success', __('messages.Category created successfully'));
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
{
    // التحقق من البيانات
    $request->validate([
        'name_ar' => 'required|string|max:255',
        'name_en' => 'required|string|max:255',
    ]);

    // التحديث
    $category->update([
        'name_ar' => $request->name_ar,
        'name_en' => $request->name_en,
    ]);

    // إعادة التوجيه مع رسالة نجاح
    return redirect()->route('admin.categories.index')
        ->with('success', 'تم تحديث التصنيف بنجاح.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'تم حذف التصنيف بنجاح.');
    }
}
