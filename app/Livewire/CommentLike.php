<?php

namespace App\Livewire;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CommentLike extends Component
{

    public Comment $comment;
    public bool $liked = false;

     public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->liked = $this->comment->likes()
                ->where('user_id', $user->id)
                ->where('is_like', true)
                ->exists();
        }
    }


      public function toggleLike()
    {
        $user = Auth::user();
        if (!$user) return;

        $existing = $this->comment->likes()
            ->where('user_id', $user->id)
            ->where('is_like', true)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->liked = false;
        } else {
            $this->comment->likes()->create([
                'user_id' => $user->id,
                'is_like' => true,
            ]);
            $this->liked = true;
        }

        $this->dispatch('$refresh'); // إذا بدك تحدث عدد اللايكات المعروض
    }


    public function render()
    {
        return view('livewire.comment-like');
    }
}
