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
        $result['user_id'] = $user_id;


        //$result['open_games'] = (array)Game::getOpenGames();

        return view('home',$result);
    }

    public function openGames(Request $request){
      $open_games = Game::getOpenGames($request->message);
      $show = Array();
      if ($open_games){
      foreach ($open_games as $open_game){
          $show[] = Array(
            'url' => url("/game/".$open_game->game_id),
            'name' => $open_game->name
          );

        }

      }
return response()->json($show);

    }


}
