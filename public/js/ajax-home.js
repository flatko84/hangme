setInterval(function(){
$.ajax({
 url: '/opengames',
 type: 'post',
 data: {_token: CSRF_TOKEN, message: user_id},
 dataType: 'json',
 success: function(json) {
  for (i=0;i<json.length;i++){
    $('#opengames').html(json);

  }
}
});
},3000);
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
