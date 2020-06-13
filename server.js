var socket  = require('socket.io'),
    express = require('express'),
    //https   = require('https'),
    http    = require('http'),
    logger  = require('winston');

logger.remove(logger.transports.Console);
logger.add(new logger.transports.Console, {colorize:true,timestamp:true}); 
logger.info(' SocketIO > listing on port ');

var app = express();
var http_server =  http.createServer(app).listen(3001);

function emitNewOrder(http_server) {
    var io = socket.listen(http_server);

    // listen to a connection and run the call back function
    io.sockets.on('connection' ,function (socket) {
        
        socket.on("new_order",function(data){
            console.log(data);
            io.emit("new_order" , data);
        })
        

    });
}
emitNewOrder(http_server);