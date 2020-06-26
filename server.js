var socket = require("socket.io"),
  express = require("express"),
  //https   = require('https'),
  http = require("http"),
  logger = require("winston");

// logger.remove(logger.transports.Console);
// logger.add(new logger.transports.Console(), { colorize: true, timestamp: true });
// logger.info(" SocketIO > listing on port ");
const io = socket.listen(http_server);
var app = express();
var http_server = http.createServer(app).listen(3001);

io.sockets.on("connection", function (socket) {
  socket.on("new_order", function (data) {
    console.log(data);
    io.emit("new_order", data);
  });
});
app.get("/", function (req, res) {
  // Brod Cast
  res.json({ status: "Working ...." });
});

app.post("/", function (req, res) {
  emitNewOrder(http_server);
  res.end();
});

function emitNewOrder(http_server) {
  // listen to a connection and run the call back function
  io.on("connection", function (socket) {
    socket.on("new_order", function (data) {
      console.log(data);
      io.emit("new_order", data);
    });
  });
}

/**
 *
 */

// const app = require("express")();
// const server = require("http").Server(app);
// server.listen(3001);
// const io = require("socket.io")(server);

// io.on("connection", (socket) => {
//   io.emit("this", { will: "be received by everyone" });

//   socket.on("private message", (from, msg) => {
//     console.log("I received a private message by ", from, " saying ", msg);
//   });

//   socket.on("disconnect", () => {
//     io.emit("user disconnected");
//   });
// });
// app.get("/", function (req, res) {
//   // Brod Cast
//   res.json({ status: "Working ...." });
// });
// app.post("/", function (req, res) {
//   const news = io.of("/").on("connection", (socket) => {
//     socket.emit("item", { news: "item" });
//   });
// });
