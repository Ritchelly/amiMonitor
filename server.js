var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

io.on('connection', function(socket){
    
    // When a user is connected
    console.log('user connected');
    console.log(socket.id); 
        
    // When a user is disconnected
    socket.on('disconnect', function(){
        console.log('user disconnected');
    })

    //When the server receive a ami data
    socket.on('ami', function(data){
       console.log(data);
        io.emit('manager',data[0]);
    });

    //Example from custom status
   /*  socket.on('agentStatus', function(data){
        console.log(data);
        console.log('Emiting agent status');
         io.emit('agentStatus',data[0]);
     }); */
}); 
 
http.listen(3001, function(){
    console.log('listening on *:3001');
});