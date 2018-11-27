@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Hang Me<div><input type=button value="Back To Stats" onClick="window.location='/home'" style="display: inline-block; position: relative; right:0"></div></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div id="game">
                    <div><h2 id="incomplete">{{ $incomplete }}</h2></div>
                    <div>{{ $description }}</div>


                      <img id="pic" src="0.png"><br>
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

                  <div>Whole word: <input id="word" name="whole"></input><input type="button" value="Guess" id="whole"></div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('gamescript')
<script type="text/javascript"><!--
$(document).ready(function(){
  var init_letters_played = '{{ $letters_played }}';
  for (i=0;i<init_letters_played.length;i++){
    $('#'+init_letters_played[i]).attr('disabled',true);

  }

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$('.letter').click( function() {
  var letter = $(this).attr("value");
	$.ajax({
		url: '/game/guess',
		type: 'post',
    data: {_token: CSRF_TOKEN, message: letter},
		dataType: 'json',
		success: function(json) {

		$('#incomplete').html(json.incomplete);

    $('#pic').attr('src',json.image + '.png');

		for (i=0;i<json.letters_played.length;i++){
      $('#'+json.letters_played[i]).attr('disabled',true);

    }
    if (json.end) {
      if (json.win) {
        var result = 'won';
      } else {
        var result = 'lost';
      }
      if (confirm('You ' + result + '! Start new game?')){
        window.location = '/game';
      }else{
        window.location = '/home';
      }

      }

    }


	});
});

$('#whole').click( function() {

	$.ajax({
		url: '/game/whole',
		type: 'post',
    data: {_token: CSRF_TOKEN, message: $('#word').val()},
		dataType: 'json',
		success: function(json) {

		$('#incomplete').html(json.incomplete);

    //$('#pic').attr('src',json.image + '.png');

		/*for (i=0;i<json.letters_played.length;i++){
      $('#'+json.letters_played[i]).attr('disabled',true);

    }*/

      if (json.win) {
        var result = 'won';
      } else {
        var result = 'lost';
      }
      if (confirm('You ' + result + '! Start new game?')){
        window.location = '/game';
      }else{
        window.location = '/home';
      }



    }


	});
});
});
//--></script>
@endsection
