<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Game::getResults(Auth::id());
        $results = Array(
          'won' => $query->won,
          'games' => $query->games,
          'lost' => $query->games - $query->won
        );
        return view('home',$results);
    }
}
