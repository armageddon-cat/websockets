<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <title>Document</title>
</head>
<body>
    <div style="width: 500px;height:500px;margin: 0 auto;border: 1px solid #ff2e49">
        <div id="chat_window" style="width: 500px;height:500px;"></div>
        <div style="width: 500px;height:20px;"><input size="67" type="text" name="message" id="user_message" title="Write your message"></div>
    </div>
</body>
</html>
<script>
    var socket = new WebSocket("ws://127.0.0.1:8000");
    socket.onopen = function () {
        $('#chat_window').append('<p>'+'Hello user'+'</p>');
    };
    $('#user_message').keypress(function( event ) {
        if (event.which == 13) {
            socket.send($('#user_message').val());
        }
    });
    socket.onmessage = function (event) {
        $('#chat_window').append('<p>'+event.data+'</p>');
    };
</script>