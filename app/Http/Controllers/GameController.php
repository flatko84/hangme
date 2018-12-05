<?php

namespace App\Http\Controllers;

use App\Game;
use App\User;
use App\UserToGame;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{


    public function __construct()
  {
      $this->middleware('auth');
  }

    public function index($join_id = '0'){

        $user_id = Auth::id();


        $game = Game::newGame($user_id, $join_id);
        $game['url'] = url('/');

        return view('game',$game);
      }


  public function guess(Request $request){
      $user_id = Auth::id();
      $letter = $request->message;
      $word = UserToGame::where('user_id','=',$user_id)->where('result','=','-1')->first();

      $incomplete = $word->incomplete;
      $complete = $word->complete;
      $positions = $this->multiStrpos($complete,$letter);

      $response = array(
        'end' => false,
        'guess' => false,
        'incomplete' => $incomplete,
        'letters_played' => $word->letters_played.$letter,
        'image' => $word->mistakes,
        'game_id' => $word->game_id
      );



      if (!empty($positions)){
        foreach ($positions as $position){
          $incomplete[$position] = $letter;
        }
        $response['incomplete'] = $incomplete;
        $response['guess'] = true;

        if ($complete == $incomplete){

          $response['end'] = "win";

          Game::endGame($user_id, $word->game_id, true);
        } else {
          Game::newTurn($user_id, $response);

  }


}else{


      $response['image']++;
    if ($response['image']>5){
      $response['end'] = "lose";

      Game::endGame($user_id, $word->game_id, false);
        }else{
        Game::newTurn($user_id, $response);
        }
      }
      $response['url'] = url('/');
      return response()->json($response);
      }


    public function whole(Request $request){
      $user_id = Auth::id();

      $whole = $request->message;
      $word = UserToGame::where('user_id','=',$user_id)->where('result','=','-1')->first();

      $end = $whole == $word->complete ? "win" : "lose";


      Game::endGame($user_id, $word->game_id, $end);
      return response()->json(array(
        'end' => $end,
        'incomplete' => $whole

        )
      );
    }

  public function notifyGame(Request $request){
    $user_id = Auth::id();

    $notify = UserToGame::where('game_id','=',$request->message)
    ->where('user_id','!=',$user_id)
    ->get();



    $formatted = Array();
    foreach ($notify as $line){
      $entry['name'] = $line->users->name;
      $entry['guesses'] = $line->guesses;
      $entry['mistakes'] = $line->mistakes;
      $entry['result'] = $line->result;
      $formatted[]=$entry;
    }

    return response()->json(['data' => $formatted]);

  }


    protected function multiStrpos($word,$letter){
      $start = 0;
      $result = Array();
      while (($curr = strpos($word,$letter,$start)) !== false) {
        $start = $curr + 1;
        $result[] = $curr;
    }
return $result;
    }


}
