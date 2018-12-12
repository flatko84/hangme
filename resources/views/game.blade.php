@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Hang Me
                  <div id="notify">
                    Game started
                  </div>
                  <div style="display: inline-block; position: relative; right:0px">
                  <input type=button value="Back To Stats" onClick="window.location='/home'">
                </div>
              </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div id="game">
                    <div>
                      <div style="display: inline-block;">
                        <h2 id="incomplete">{{ $incomplete }}</h2>

                      </div>
                      <div id="win" style="display: none;">
                        <h2>You won!</h2>
                      </div>
                      <div id="lose" style="display: none;">
                        <h2>You've been hanged!</h2>
                      </div>
                      <div id="correct">
                            
                      </div>
                      <div id="new-game" style="display: none;">
                        <input type="button" value="New Game" onclick="window.location='/game'">

                      </div>
                    </div>
                    <div>{{ $description }}</div>


                    <img id="pic" src="{{ $url.'/'.$mistakes }}.png"><br>
                    @foreach ($keyboards as $keyboard)
                    <p>
                    @foreach ($keyboard as $key)
                     @if ($key == "br")
                     <br>
                     @else
                    <input type="button" class="letter" value="{{ $key }}" id="{{ $key }}">
                     @endif
                    @endforeach
                    </p>
                    @endforeach
                    
                    
                    
                    
                    
                    

                 
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('gamescript')
<script type="text/javascript">var init_letters_played = '{{ $letters_played }}';var game_id = {{ $game_id }}</script>
<script type="text/javascript" src="{{ asset('js/ajax-game.js') }}"></script>



@endsection
