<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $id = auth()->user()->people_id;
        $person = Person::find($id);
        return view('home', compact('person'));
    }
}
