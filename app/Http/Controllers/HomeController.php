<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class HomeController extends Controller
{
 public function index()
{
    $videos = Video::inRandomOrder()->paginate(9);
    return view('home', compact('videos'));
}



}
