var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

app.get('/',function (req,res) {
    res.send('Burası anasayfa');
});

io.on('connection',(socket)=>{
    socket.on('newMessageCreate',function () {
        console.log("çalıştı");
        io.emit('MessageList');
    })

});

http.listen(3000,function () {
    console.log("server çalışıyor...");
});
