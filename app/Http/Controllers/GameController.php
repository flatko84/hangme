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
        $response = Array();
        $response['user_id'] = $user_id;
        
        $response['result'] = '-1';
        $saved = UserToGame::where('user_id', '=', $user_id)->where('result','=','-1')->first();
        
              if ($saved){

                //  $response = (array)$saved;
            
            $response['game_id'] = $saved->game_id;
            $response['complete'] = $saved->complete;
            $response['incomplete'] = $saved->incomplete;
            $response['description'] = $saved->description;
            $response['guesses'] = $saved->guesses;
            $response['mistakes'] = $saved->mistakes;
            $response['letters_played'] = $saved->letters_played;
            $response['keyboard'] = $saved->keyboard;
          

        }else{
            
            if ($join_id != '0'){

                 $word = Game::where('game_id','=',$join_id)
                 ->where('open','=','1')
                 ->first();
                 
            

                 $response['game_id'] = $join_id;


          }else{

            
                $word = Word::inRandomOrder()->first();
                
                $game_id = Game::insertGetId(
                  Array(
                  'creator_user_id' => $user_id,
                  'open' => true,
                  'word' => $word->word,
                  'description' => $word->description,
                  'keyboard' => $word->keyboard,
                  'finished' => '0'
                  ));

        
            
                $response['game_id'] = $game_id;
           
          }
              //open first and last letter in every word
              $incomplete = preg_replace('/\B.\B/u', '.', $word->word);
              $ar_incomplete = preg_split('//u', $incomplete,-1, PREG_SPLIT_NO_EMPTY);
              $ar_complete = preg_split('//u', $word->word,-1, PREG_SPLIT_NO_EMPTY);
              
              // calculate letters played
              $ar_letters_opened = Array();
              for ($l = 0;$l < mb_strlen($incomplete,'UTF-8');$l++){
                  
                  if ($ar_incomplete[$l] != '.' && $ar_incomplete[$l] != ' ' && $ar_incomplete[$l] != ','){
                      $ar_letters_opened[] = $ar_incomplete[$l];
                  }
              }
              
              $letters_opened = implode('',$ar_letters_opened);
              
              

                for ($i = 0; $i < mb_strlen($incomplete,'UTF-8'); $i++){
                  if (mb_strpos($letters_opened,$ar_complete[$i],0,'UTF-8') !== false){
                  $ar_incomplete[$i] = $ar_complete[$i];

                  }
                  $incomplete = implode('',$ar_incomplete);

                }
              $response['complete'] = $word->word;
              $response['incomplete'] = $incomplete;
              $response['description'] = $word->description;
              $response['guesses'] = '0';
              $response['mistakes'] = '0';
              $response['letters_played'] = $letters_opened;
              $response['keyboard'] = $word->keyboard;
            //print_r($ar_incomplete);
            UserToGame::insert($response);
        }
        
          $response['url'] = url('/');
          $response['keyboard'] = $this->lettersLayout($response['keyboard']);
          //var_dump($response);
          return view('game',$response);
    }


  public function guess(Request $request){
      $user_id = Auth::id();
      $letter = $request->message;

      $word = UserToGame::where('user_id','=',$user_id)->where('result','=','-1')->first();
      Game::where('game_id','=',$word->game_id)
      ->update(Array('open' => false));

      $incomplete = $word->incomplete;
      $complete = $word->complete;
      $positions = $this->multiStrpos($complete,$letter);

      $response = array(
        'end' => false,
        'guess' => false,
        'incomplete' => $incomplete,
        'letters_played' => $word->letters_played.$letter,
        'image' => $word->mistakes,
        'guesses' => $word->guesses,
        'game_id' => $word->game_id
      );



      if (!empty($positions)){   //if guessed letter
          $ar_incomplete = preg_split('//u', $incomplete,-1, PREG_SPLIT_NO_EMPTY);
          
        foreach ($positions as $position){
           
          $ar_incomplete[$position] = $letter;
        }
        
        $incomplete = implode('',$ar_incomplete);
        
        $response['incomplete'] = $incomplete;
        $response['guess'] = true;

        if ($complete == $incomplete){    //guessed word - game won

          $response['end'] = "win";

          $this->endGame($user_id, $word->game_id, true);
        } else {
          $response['guesses']++;                     //word not guessed - game continues

          UserToGame::where('user_id','=',$user_id)
          ->where('game_id','=',$response['game_id'])
          ->update(Array(
                  'incomplete' => $response['incomplete'],
                  'letters_played' => $response['letters_played'],
                  'guesses' => $response['guesses']
                  ));

  }


}else{         //if no letter is guessed


      $response['image']++;
    if ($response['image']>5){             //6 mistakes - game lost
      $response['end'] = "lose";

      $this->endGame($user_id, $word->game_id, false);

    }else{                            // less than 6 mistakes - game continues

      UserToGame::where('user_id','=',$user_id)
            ->where('game_id','=',$response['game_id'])
            ->update(Array(
                'letters_played' => $response['letters_played'],
                'mistakes' => $response['image']
                ));
        }
      }
      $response['url'] = url('/');
      return response()->json($response);
      }




  protected function endGame($user_id, $game_id, $result){
                User::where('id','=',$user_id)->increment('games');
                if ($result == true){
                  User::where('id','=',$user_id)->increment('won');
                }
                UserToGame::where('user_id','=',$user_id)->update(['result' => (int)$result]);

                //user to game result -1=playing, 0=lost, 1=won

                $players = UserToGame::where('game_id','=',$game_id)->where('result','=','-1')->count();
                if ($players == 0){
                  Game::where('game_id','=',$game_id)->update(['finished' => '1', 'open' => '0']);
                }

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
      while (($curr = mb_strpos($word,$letter,$start,'UTF-8')) !== false) {
        $start = $curr + 1;
        $result[] = $curr;
    }
return $result;
    }

    protected function lettersLayout($type){
        switch ($type){
            case 'latin': $layout = ['q','w','e','r','t','y','u','i','o','p','br','a','s','d','f','g','h','j','k','l','br','z','x','c','v','b','n','m'];
                break;
            case 'cyr': $layout = ['у','е','и','ш','щ','к','с','д','з','ц','br','ь','я','а','о','ж','г','т','н','в','м','ч','br','ю','й','ъ','э','ф','х','п','р','л','б'];
                break;
     }
        return $layout;
    }


}
