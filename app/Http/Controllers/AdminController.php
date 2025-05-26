<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $videosCount = \App\Models\Video::count();
        $channelsCount = \App\Models\Channel::count();
        $categoriesCount = \App\Models\Category::count();
        $usersCount = \App\Models\User::count();

        return view('admin.index', compact('videosCount', 'channelsCount', 'categoriesCount', 'usersCount'));
    }

}
