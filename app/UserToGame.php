<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserToGame extends Model
{
  protected $table = 'user_to_game';
  protected $primaryKey = 'user_to_game_id';
  public $timestamps = false;

  public function users(){

    return $this->belongsTo('App\User', 'id','user_id');
  }
    //
}
