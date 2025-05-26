<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChannelController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user=Auth::user();
        $channel = Channel::where('user_id', $user->id)->first(); // يرجع كائن واحد
        $videos = $channel->videos()->latest()->paginate(6);
        return view('channels.index', compact(['channel', 'videos']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {        
        return view('channels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
          $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('channels', 'public');
        }

        $channel = Channel::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'image' => $path,
        ]);

        return redirect('/')->with('success', 'تم إنشاء القناة بنجاح');

    }

    /**
     * Display the specified resource.
     */
    public function show(Channel $channel)
    {
        $videos = $channel->videos()->latest()->paginate(6);   
        return view('channels.show', compact(['channel', 'videos']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Channel $channel)
    {
        $this->authorize('update' , $channel);
        return view('channels.edit', compact('channel'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Channel $channel)
{
    // تحقق أن المستخدم هو صاحب القناة
    if ($channel->user_id !== auth()->id()) {
        abort(403, 'غير مصرح لك بتعديل هذه القناة');
    }

    // التحقق من البيانات
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
    ]);

    // تحديث البيانات الأساسية
    $channel->name = $request->name;
    $channel->description = $request->description;

    // تحديث الصورة إذا تم رفع صورة جديدة
    if ($request->hasFile(key: 'image')) {
        // حذف الصورة القديمة إذا كانت موجودة
        if ($channel->image && Storage::exists($channel->image)) {
            Storage::delete($channel->image);
        }

        // رفع الصورة الجديدة
        $channel->image = $request->file('image')->store('channels', 'public');
    }

    // حفظ التعديلات
    $channel->save();

    return redirect()->route('channels.index', $channel)->with('success', 'تم تحديث القناة بنجاح');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel)
    {
        $this->authorize('delete' , $channel);
        // حذف صورة القناة من التخزين إن وُجدت
        if ($channel->image && Storage::disk('public')->exists($channel->image)) {
            Storage::disk('public')->delete($channel->image);
        }

        // حذف القناة نفسها
        $channel->delete();

        return redirect()->route('home.index')->with('success', 'تم حذف القناة بنجاح');
    }
}
