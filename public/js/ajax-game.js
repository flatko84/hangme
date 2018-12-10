$(document).ready(function(){
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

  document.onkeypress = function(evt) {
     evt = evt || window.event;
     var charCode = evt.which || evt.keyCode;
     var charStr = String.fromCharCode(charCode);
     
         $('#'+charStr).click();
     
  };

  setInterval(function(){

  $.ajax({
   url: '/notify',
   type: 'post',
   data: {_token: CSRF_TOKEN, message: game_id},
   dataType: 'json',
   success: function(json) {
     var ms = "";
     for (i=0;i<json.data.length;i++){

       ms += json.data[i].name;

       if (json.data[i].result == '0'){ ms += " has been hanged."; }
       if (json.data[i].result == '1'){ ms += " guessed the word."; }
       if (json.data[i].result == '-1'){

       ms += " has guessed ";
       ms += json.data[i].guesses;
       ms += " letters and made ";
       ms += json.data[i].mistakes;
       ms += " mistakes.";

     }
     ms += "<br>";
    $('#notify').html(ms);

  }

}
});
},3000);


  for (i=0;i<init_letters_played.length;i++){
    $('#'+init_letters_played[i]).attr('disabled',true);

  }

    $('.letter').click( function() {
      var letter = $(this).attr("value");
	     $.ajax({
		       url: '/game/guess',
		       type: 'post',
           data: {_token: CSRF_TOKEN, message: letter},
		       dataType: 'json',
		       success: function(json) {

		$('#incomplete').html(json.incomplete);

    $('#pic').attr('src',json.url+'/'+json.image + '.png');

		for (i=0;i<json.letters_played.length;i++){
      $('#'+json.letters_played[i]).attr('disabled',true);

    }
    if (json.end) {
        $('#'+json.end).css('display','inline-block');
        $('#new-game').css('display','inline-block');
        


      }
    }
	});
});



});
