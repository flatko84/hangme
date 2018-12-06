<?php

namespace App\Http\Controllers;

use App\Game;
use App\User;
use App\UserToGame;
use App\Word;

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
        $saved = UserToGame::where('user_id', '=', $user_id)->where('result','=','-1')->first();
        $word = Game::where('game_id','=',$join_id)
        ->first();

        if ($saved){

          $response = Array(
            'user_id' => $user_id,
            'game_id' => $saved->game_id,
            'complete' => $saved->complete,
            'incomplete' => $saved->incomplete,
            'description' => $saved->description,
            'result' => '-1',
            'guesses' => $saved->guesses,
            'mistakes' => $saved->mistakes,
            'letters_played' => $saved->letters_played
          );

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

            UserToGame::insert($response);

          }else{

            $word = Word::inRandomOrder()->first();


        $incomplete = preg_replace('/\B.\B/', '.', $word->word);
        $letters_opened = preg_replace('/[^a-z]/s','',$incomplete);
        for ($i = 0; $i < strlen($word->word); $i++){
          if (in_array($word->word[$i],str_split($letters_opened))){
          $incomplete[$i] = $word->word[$i];

          }

        }

        $game_id = Game::insertGetId(
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

        UserToGame::insert($response);
      }

      $response['url'] = url('/');


      return view('game',$response);
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

          $this->endGame($user_id, $word->game_id, true);
        } else {
          $this->newTurn($user_id, $response);

  }


}else{


      $response['image']++;
    if ($response['image']>5){
      $response['end'] = "lose";

      $this->endGame($user_id, $word->game_id, false);
        }else{
        $this->newTurn($user_id, $response);
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


      $this->endGame($user_id, $word->game_id, $end);
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


    protected function newTurn($user_id, $data){


        Game::where('game_id','=',$data['game_id'])
        ->update(Array('open' => false));

        if ($data['guess']==0){

            UserToGame::where('user_id','=',$user_id)
              ->increment('mistakes');

            UserToGame::where('user_id','=',$user_id)
                  ->update(Array(
                      'letters_played' => $data['letters_played']
                      ));

            }else{
          UserToGame::where('user_id','=',$user_id)
                ->increment('guesses');

        UserToGame::where('user_id','=',$user_id)
            ->update(Array(
                'incomplete' => $data['incomplete'],
                'letters_played' => $data['letters_played']
                ));

              }
              }

    protected function endGame($user_id, $game_id, $result){
      User::where('id','=',$user_id)->increment('games');
      if ($result == true){
        User::where('id','=',$user_id)->increment('won');
      };
      UserToGame::where('user_id','=',$user_id)->update(['result' => (int)$result]);

      //user to game result -1=playing, 0=lost, 1=won

      $players = UserToGame::where('game_id','=',$game_id)->where('result','=','-1')->count();
      if ($players == 0){
        Game::where('game_id','=',$game_id)->update(['finished' => '1', 'open' => '0']);
      }

    }


}
