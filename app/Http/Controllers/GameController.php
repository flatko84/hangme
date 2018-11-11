<?php

namespace App\Http\Controllers;

use App\Game;

use Illuminate\Http\Request;

class GameController extends Controller
{


  public function __construct()
  {
      $this->middleware('auth');
  }

    public function index(){

      $user_id = '1';
      $game = Game::newWord($user_id);
    return view('game',$game);     // get user id
    }


    public function guess(Request $request){
$user_id = '1';
$letter = $request->message;
$word = Game::getCurrentWord($user_id);  //to do - get user id

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

if (!empty($positions)){  //ako ima syvpadenie - popylva
  foreach ($positions as $position){
    $incomplete[$position] = $letter;
  }
  $response['incomplete'] = $incomplete;
  $response['guess'] = true;

  if ($complete == $incomplete){  // ako e poznal dumata - win

  $response['end'] = true;
  $response['win'] = true;
Game::endGame($user_id, true);
  } else {
Game::newTurn($user_id, $response);

  }


}else{   // ako nqma syvpadenie - izgrajda besilka


  $response['image']++;
  if ($response['image']>5){   // ako nqma pove4e opiti - lose
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
    $user_id = '1';

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
