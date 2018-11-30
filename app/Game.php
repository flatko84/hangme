<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class Game extends Model
{

  public static function newWord($user_id){

    $saved = DB::table('user_to_game')->where('user_id', '=', $user_id)->first();

    if ($saved){
      $response = (array)$saved;

    }else{

    $word = DB::table('words')->inRandomOrder()->first();

    $incomplete = preg_replace('/\B.\B/', '.', $word->word);

    $letters_opened = preg_replace('/[^a-z]/s','',$incomplete);



    for ($i = 0; $i < strlen($word->word); $i++){
      if (in_array($word->word[$i],str_split($letters_opened))){
      $incomplete[$i] = $word->word[$i];

      }

    }

    $game_id = DB::table('games')->insert(Array('creator_user_id' => $user_id, 'open' => true));

    $response = Array(
        'user_id' => $user_id,
        'game_id' => $game_id,
        'complete' => $word->word,
        'incomplete' => $incomplete,
        'description' => $word->description,
        'mistakes' => '0',
        'letters_played' => $letters_opened
    );

    DB::table('user_to_game')->insert($response);
  }

return $response;
  }


public static function getCurrentWord($user_id){
  $game = DB::table('user_to_game')->where('user_id','=',$user_id)->first();
  return $game;

}


public static function newTurn($user_id, $data){

  if ($data['guess']==0){
DB::table('user_to_game')->where('user_id','=',$user_id)->increment('mistakes');

  }

DB::table('user_to_game')->where('user_id','=',$user_id)->update(array(

  'incomplete' => $data['incomplete'],
  'letters_played' => $data['letters_played']
));
}



public static function endGame($user_id, $result){

DB::table('users')->where('id','=',$user_id)->increment('games');
if ($result == true){
DB::table('users')->where('id','=',$user_id)->increment('won');
 };
DB::table('user_to_game')->where('user_id','=',$user_id)->delete();

}

public static function getResults($user_id){
  $score = DB::table('users')->where('id','=',$user_id)->first();
  $saved = DB::table('user_to_game')->where('user_id','=',$user_id)->count();
  //$saved =

  $result = Array(
    'won' => $score->won,
    'games' => $score->games,
    'lost' => $score->games - $score->won,
    'saved' => $saved
  );
  return $result;
}

public static function getOpenGames(){
  //$games = DB::table('games')->where('open','=','true');
  $query = DB::table('games')
  ->leftJoin('users','games.creator_user_id','=','users.id')
  ->get();

  return (array)$query;

}


    //
}
