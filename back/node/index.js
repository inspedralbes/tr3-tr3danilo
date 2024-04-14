const express = require("express");
const cors = require("cors");
const http = require("http");
const { Server } = require("socket.io");

const app = express();
app.use(cors());
const port = 3102;

const server = http.createServer(app);
const io = new Server(server, {
  cors: {
    methods: ["GET", "POST"],
  },
});

let usuarisSesioActual = [];

io.on("connection", (socket) => {
  console.log("Nuevo cliente conectado:", socket.id);
  
  // Manejar la conexión del usuario a la sesión
  socket.on("joinSession", (sessionId) => {
    // Agregar el usuario conectado a la sesión actual
    usuarisSesioActual.push({
      id: socket.id,
      sessionId: sessionId,
    });

    console.log("Usuario", socket.id, "se ha unido a la sesión", sessionId);
  });
  socket.on("seatSelected", (seat) => {
    console.log(`Butaca seleccionada: ${JSON.stringify(seat)}`);
    // Emitir el evento a todos los clientes
    socket.broadcast.emit("seatSelected", seat);
  });

  socket.on('seatDeselected', (seat) => {
    console.log(`Butaca deseleccionada: ${JSON.stringify(seat)}`);
    // Emitir el evento a todos los clientes
    socket.broadcast.emit("seatDeselected", seat);
  });

  socket.on("disconnect", () => {
    console.log("Cliente desconectado:", socket.id);

    // Eliminar el ID del usuario desconectado de la lista de usuarios de la sesión actual
    usuarisSesioActual = usuarisSesioActual.filter(
      (user) => user.id !== socket.id
    );
  });
});

server.listen(port, () => {
  console.log(`Servidor Socket.IO en ejecución en el puerto ${port}`);
});
