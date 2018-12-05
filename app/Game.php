<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class Game extends Model
{

  protected $table = 'games';
  protected $primaryKey = 'game_id';
  public $timestamps = false;


  public function users(){

    return $this->belongsTo('App\User','creator_user_id','id');
  }


  public static function newGame($user_id, $join_id){

    $saved = DB::table('user_to_game')->where('user_id', '=', $user_id)->where('result','=','-1')->first();
    $word = DB::table('games')
    ->where('game_id','=',$join_id)
    ->first();

    if ($saved){
      $response = (array)$saved;

    }elseif ($join_id != '0' && $word->open == '1'){





        $incomplete = preg_replace('/\B.\B/', '.', $word->word);

        $letters_opened = preg_replace('/[^a-z]/s','',$incomplete);



        for ($i = 0; $i < strlen($word->word); $i++){
          if (in_array($word->word[$i],str_split($letters_opened))){
          $incomplete[$i] = $word->word[$i];

          }

        }

        $response = Array(
          'user_id' => $user_id,
          'game_id' => $join_id,
          'complete' => $word->word,
          'incomplete' => $incomplete,
          'description' => $word->description,
          'result' => '-1',
          'guesses' => '0',
          'mistakes' => '0',
          'letters_played' => $letters_opened
        );

        DB::table('user_to_game')->insert($response);

      }else{

        $word = DB::table('words')->inRandomOrder()->first();


    $incomplete = preg_replace('/\B.\B/', '.', $word->word);

    $letters_opened = preg_replace('/[^a-z]/s','',$incomplete);



    for ($i = 0; $i < strlen($word->word); $i++){
      if (in_array($word->word[$i],str_split($letters_opened))){
      $incomplete[$i] = $word->word[$i];

      }

    }

    $game_id = DB::table('games')
    ->insertGetId(
      Array(
      'creator_user_id' => $user_id,
      'open' => true,
      'word' => $word->word,
      'description' => $word->description,
      'finished' => '0'
      ));

    $response = Array(
        'user_id' => $user_id,
        'game_id' => $game_id,
        'complete' => $word->word,
        'incomplete' => $incomplete,
        'description' => $word->description,
        'result' => '-1',
        'guesses' => '0',
        'mistakes' => '0',
        'letters_played' => $letters_opened
    );

    DB::table('user_to_game')->insert($response);
  }

return $response;
  }


public static function getCurrentWord($user_id){
  $game = DB::table('user_to_game')->where('user_id','=',$user_id)->where('result','=','-1')->first();
  return $game;

}


public static function newTurn($user_id, $data){


    DB::table('games')
    ->where('game_id','=',$data['game_id'])
    ->update(Array('open' => false));

    if ($data['guess']==0){

        DB::table('user_to_game')
          ->where('user_id','=',$user_id)
          ->increment('mistakes');

        DB::table('user_to_game')
            ->where('user_id','=',$user_id)
              ->update(Array(
                  'letters_played' => $data['letters_played']
                  ));

        }else{
      DB::table('user_to_game')
            ->where('user_id','=',$user_id)
            ->increment('guesses');

    DB::table('user_to_game')
      ->where('user_id','=',$user_id)
        ->update(Array(
            'incomplete' => $data['incomplete'],
            'letters_played' => $data['letters_played']
            ));

          }
          }



public static function endGame($user_id, $game_id, $result){

  DB::table('users')->where('id','=',$user_id)->increment('games');
  if ($result == true){
    DB::table('users')->where('id','=',$user_id)->increment('won');
  };
  DB::table('user_to_game')->where('user_id','=',$user_id)->update(['result' => (int)$result]);

  //user to game result -1=playing, 0=lost, 1=won

  $players = DB::table('user_to_game')->where('game_id','=',$game_id)->where('result','=','-1')->count();
  if ($players == 0){
    DB::table('games')->where('game_id','=',$game_id)->update(['finished' => '1', 'open' => '0']);
  }

}

public static function getResults($user_id){
  $score = DB::table('users')->where('id','=',$user_id)->first();
  $saved = DB::table('user_to_game')->where('user_id','=',$user_id)->where('result','=','-1')->count();


  $result = Array(
    'won' => $score->won,
    'games' => $score->games,
    'lost' => $score->games - $score->won,
    'saved' => $saved
  );
  return $result;
}

public static function getOpenGames($user_id){
  $query = DB::table('games')
  ->leftJoin('users','games.creator_user_id','=','users.id')
  ->where('open','=','1')
  ->where('creator_user_id','!=',$user_id)
  ->get();

  return $query;

}

public static function getNotifications($game_id, $user_id){

    $query = DB::table('user_to_game')
    ->leftJoin('users','user_to_game.user_id','=','users.id')
    ->where('game_id','=',$game_id)
    ->where('user_id','!=',$user_id)
    ->get();

    return $query;

}



    //
}
