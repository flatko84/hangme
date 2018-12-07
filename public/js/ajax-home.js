var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
setInterval(function(){
$.ajax({
 url: '/opengames',
 type: 'post',
 data: {_token: CSRF_TOKEN, message: user_id},
 dataType: 'json',
 success: function(json) {
   var msg = "";
  for (i=0;i<json.length;i++){
    msg += "<a href='";
    msg += json[i].url;
    msg += "'>";
    msg += json[i].name;
    msg += "</a><br>";
  }
  
  $('#opengames').html(msg);
}
});
},3000);

