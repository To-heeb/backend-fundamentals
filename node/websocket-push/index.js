const http = require('http');
const WebSocketServer = require("websocket").server;
let connections = [];

// create a raw http server( this will help us create the TCP which will then pass to the websocket to do the job   )
const httpServer = http.createServer();

//  pass the httpServer object to the WebSocketServer library to do all the job, this will override request/reponse
const websocket = new WebSocketServer({ "httpServer": httpServer });
// listen to TCP socket
httpServer.listen(8080, () => console.log("My server is listening on port 8080"));

// when a websocket request comes it listens to it on port 8080 and gets the connection .. once you get a connection that is it.
websocket.on("request", request => {

    const connection = request.accept(null, request.origin);
    connection.on("message", message => {
        // someone just sent a message tell everyone
        connections.forEach(c => c.send(`User${connection.socket.remotePort} say: ${message.utf8DaSta}`));
    })

    connections.push(connection)
    // someone just connected, tell everyone
    connections.forEach(c => c.send(`User${connection.socket.remotePort} just connected.`))

    // when someone leave tell everyone and remove them from connections array
    //connection.on("close", () => {})
})