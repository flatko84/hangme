@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Hang Me
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
                      <div>
                        <h2 id="incomplete">{{ $incomplete }}</h2>

                      </div>
                      <div id="win" style="display: none;">
                        You won!
                      </div>
                      <div id="lose" style="display: none;">
                        You've been hanged!
                      </div>
                      <div id="new-game" style="display: none;">
                        <input type="button" value="New Game" onclick="window.location='/game'">

                      </div>
                    </div>
                    <div>{{ $description }}</div>


                    <img id="pic" src="{{ $mistakes }}.png"><br>
                    <input type="button" class="letter" value="q" id="q">
                    <input type="button" class="letter" value="w" id="w">
                    <input type="button" class="letter" value="e" id="e">
                    <input type="button" class="letter" value="r" id="r">
                    <input type="button" class="letter" value="t" id="t">
                    <input type="button" class="letter" value="y" id="y">
                    <input type="button" class="letter" value="u" id="u">
                    <input type="button" class="letter" value="i" id="i">
                    <input type="button" class="letter" value="o" id="o">
                    <input type="button" class="letter" value="p" id="p"><br>
                    &nbsp;&nbsp;&nbsp;<input type="button" class="letter" value="a" id="a">
                    <input type="button" class="letter" value="s" id="s">
                    <input type="button" class="letter" value="d" id="d">
                    <input type="button" class="letter" value="f" id="f">
                    <input type="button" class="letter" value="g" id="g">
                    <input type="button" class="letter" value="h" id="h">
                    <input type="button" class="letter" value="j" id="j">
                    <input type="button" class="letter" value="k" id="k">
                    <input type="button" class="letter" value="l" id="l"><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="letter" value="z" id="z">
                    <input type="button" class="letter" value="x" id="x">
                    <input type="button" class="letter" value="c" id="c">
                    <input type="button" class="letter" value="v" id="v">
                    <input type="button" class="letter" value="b" id="b">
                    <input type="button" class="letter" value="m" id="m">
                    <input type="button" class="letter" value="n" id="n">

                  <div>Whole word: <input id="word" name="whole"><input type="button" value="Guess" id="whole"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('gamescript')
<script type="text/javascript">var init_letters_played = '{{ $letters_played }}';</script>
<script type="text/javascript" src="{{ asset('js/ajax-game.js') }}"></script>


@endsection
