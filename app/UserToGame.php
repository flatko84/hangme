<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserToGame extends Model
{
  protected $table = 'user_to_game';
  protected $primaryKey = 'user_to_game_id';
  public $timestamps = false;

  public function users(){

    return $this->belongsTo('App\User','user_id', 'id');
  }
  
  public function games(){

    return $this->belongsTo('App\Game','user_id', 'creator_user_id');
  }
    //
}
