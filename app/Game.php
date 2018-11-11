<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{

  public static function newWord($user_id){
    DB::table('games')->where('user_id', '=', $user_id)->delete();
    $word = DB::table('words')->inRandomOrder()->first();

    $incomplete = substr($word->word,0,1).str_repeat('.',strlen($word->word)-2).substr($word->word,-1);


    $response = Array(
        'user_id' => $user_id,
        'complete' => $word->word,
        'incomplete' => $incomplete,
        'mistakes' => '0',
        'letters_played' => ''
    );

    DB::table('games')->insert($response);
    $response['description'] = $word->description;

return $response;
  }


public static function getCurrentWord($user_id){
  $game = DB::table('games')->where('user_id','=',$user_id)->first();
  return $game;

}


public static function newTurn($user_id, $data){

  if ($data['guess']==0){
DB::table('games')->where('user_id','=',$user_id)->increment('mistakes');

  }

DB::table('games')->where('user_id','=',$user_id)->update(array(

  'incomplete' => $data['incomplete'],
  'letters_played' => $data['letters_played']
));
}



public static function endGame($user_id, $result){

DB::table('users')->where('id','=',$user_id)->increment('games');
if ($result == true){
DB::table('users')->where('id','=',$user_id)->increment('won');
 };
DB::table('games')->where('user_id','=',$user_id)->delete();

}

public static function getResults($user_id){
  $result = DB::table('users')->where('id','=',$user_id)->first();
  return $result;
}
    //
}
