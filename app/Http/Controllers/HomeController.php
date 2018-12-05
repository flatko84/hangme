<?php

namespace App\Http\Controllers;

use App\Game;
use App\UserToGame;
use App\User;
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
        $score = User::where('id','=',$user_id)->first();
        $saved = UserToGame::where('user_id','=',$user_id)->where('result','=','-1')->count();


        $result = Array(
          'user_id' => $user_id,
          'won' => $score->won,
          'games' => $score->games,
          'lost' => $score->games - $score->won,
          'saved' => $saved
        );

        return view('home',$result);
    }

    public function openGames(Request $request){


      $open_games = Game::where('open','=','1')
      ->where('creator_user_id','!=',$request->message)
      ->get();


      $show = Array();
      if ($open_games){
      foreach ($open_games as $open_game){
          $show[] = Array(
            'url' => url("/game/".$open_game->game_id),
            'name' => $open_game->users->name
          );

        }

      }
return response()->json($show);

    }


}
