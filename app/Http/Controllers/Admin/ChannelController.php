<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $channels= Channel::all();
        return view('admin.channels.index', compact('channels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
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
    public function edit(Channel $channel)
    {
        return view('admin.channels.edit', compact('channel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Channel $channel)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['name', 'description']);

        // إذا تم رفع صورة جديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إن وجدت
            if ($channel->image && \Storage::exists($channel->image)) {
                \Storage::delete($channel->image);
            }

            // تخزين الصورة الجديدة
            $data['image'] = $request->file('image')->store('channels', 'public');
        }

        $channel->update($data);

        return redirect()->route('admin.channels.index')->with('success', 'تم تحديث القناة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
        */
    public function destroy(Channel $channel)
    {
        // حذف الصورة من التخزين إن وُجدت
        if ($channel->image && \Storage::exists($channel->image)) {
            \Storage::delete($channel->image);
        }

        $channel->delete();

        return redirect()->route('admin.channels.index')->with('success', 'تم حذف القناة بنجاح');
    }
}
