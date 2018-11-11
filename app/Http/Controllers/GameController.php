<?php

namespace App\Http\Controllers;

use App\Game;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{


  public function __construct()
  {
      $this->middleware('auth');
  }

    public function index(){

      $user_id = Auth::id();
      $game = Game::newWord($user_id);
    return view('game',$game);
    }


    public function guess(Request $request){
$user_id = Auth::id();
$letter = $request->message;
$word = Game::getCurrentWord($user_id);

$incomplete = $word->incomplete;
$complete = $word->complete;
$positions = $this->multiStrpos($complete,$letter);

$response = array(
  'end' => false,
  'win' => false,
  'guess' => false,
  'incomplete' => $incomplete,
  'letters_played' => $word->letters_played.$letter,
  'image' => $word->mistakes
);

if (!empty($positions)){
  foreach ($positions as $position){
    $incomplete[$position] = $letter;
  }
  $response['incomplete'] = $incomplete;
  $response['guess'] = true;

  if ($complete == $incomplete){

  $response['end'] = true;
  $response['win'] = true;
Game::endGame($user_id, true);
  } else {
Game::newTurn($user_id, $response);

  }


}else{


  $response['image']++;
  if ($response['image']>5){
    $response['end'] = true;
    $response['win'] = false;
    Game::endGame($user_id, false);
    }else{
    Game::newTurn($user_id, $response);
  }
}
return response()->json($response);
    }


public function whole(Request $request){
    $user_id = Auth::id();

  $whole = $request->message;
  $word = Game::getCurrentWord($user_id);
  $match = ($whole == $word->complete);
  Game::endGame($user_id, $match);
  return response()->json(array(
    'win' => $match,
    'incomplete' => $whole

)
  );
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
