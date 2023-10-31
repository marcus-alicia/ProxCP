var socket = io.connect('https://app.domain.com:8000', {
    secure: true
});
var user = $('#user').val();
socket.on('connect', function () {
    console.log('Connected to socket');
    $('#socket_error').css('visibility', 'hidden');
    $('#socket_error').css('padding', '0px');
    $('#socket_error').html('');
    socket.emit('addUserConnection', user);
});
socket.on('reconnecting', function () {
    console.log('Lost connection to socket! Attempting to reconnect...');
    $('#socket_error').css('visibility', 'visible');
    $('#socket_error').css('padding', '10px');
    $('#socket_error').html('Cannot connect to socket! All VM functions will fail :(');
});
