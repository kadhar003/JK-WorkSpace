const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const cors = require('cors');

const app = express();

app.use(cors());

const server = http.createServer(app);

const io = new Server(server, {
    cors: {
        origin: "*",
    }
});

let onlineUsers = {};

io.on('connection', (socket) => {

    console.log('User Connected:', socket.id);

    // Chat Message
    socket.on('chat-message', (data) => {

        io.emit('chat-message', data);

    });

    // User Online
    socket.on('user-online', (userId) => {

        onlineUsers[userId] = socket.id;

        io.emit('online-users', Object.keys(onlineUsers));

        console.log('Online Users:', onlineUsers);

    });
    
    socket.on('code-update', (file) => {

        socket.broadcast.emit(
            'code-update',
            file
        );

    });

    // Disconnect
    socket.on('disconnect', () => {

        for (let userId in onlineUsers) {

            if (onlineUsers[userId] === socket.id) {

                delete onlineUsers[userId];

            }
        }

        io.emit('online-users', Object.keys(onlineUsers));

        console.log('User Disconnected');

    });

    socket.on('task-moved', (task) => {

        io.emit('task-moved', task);

    });

    socket.on('note-updated', (note) => {

        io.emit('note-updated', note);

    });



    

});

server.listen(3001, () => {

    console.log('Realtime Server Running On Port 3001');

});