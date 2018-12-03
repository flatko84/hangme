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
        $user_id = Auth::id();
        $result = Game::getResults($user_id);

        $open_games = Game::getOpenGames($user_id);
        $show = Array();
        foreach ($open_games as $open_game){
            $show[] = Array(
              'url' => url("/game/".$open_game->game_id),
              'name' => $open_game->name
            );

            $result['open_games'] = $show;

        }


        //$result['open_games'] = (array)Game::getOpenGames();

        return view('home',$result);
    }


}
