<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Storeroom;
use App\Models\Unit;
use Illuminate\Http\Request;

class StoreroomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $categories = Category::all();
        $units = Unit::all();

        return view('storeroom.index', compact(['categories', 'units']));
    }
}
