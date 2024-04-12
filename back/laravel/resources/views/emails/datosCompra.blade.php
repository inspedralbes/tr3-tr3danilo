<!DOCTYPE html>
<html>
<head>
    <title>Detalles de la compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: black; /* Fondo negro */
            color: white; /* Letras blancas */
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            color: black; /* Color de texto negro para el contenido */
            text-align: center; /* Centra el contenido */
        }

        .imagen-pelicula {
            max-width: 300px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            display: block; /* Para centrar la imagen */
            margin-left: auto; /* Para centrar la imagen */
            margin-right: auto; /* Para centrar la imagen */
        }

        h1 {
            color: #333;
            text-align: center;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        li:hover {
            background-color: #e0e0e0;
        }

        p {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalles de la compra</h1>
        <!-- Agregar la imagen de la película -->
        <img src="{{ $datosCompra['datosSesion']['pelicula']['imagen'] }}" alt="Imagen de la película" class="imagen-pelicula">

        <p>Película: {{ $datosCompra['datosSesion']['pelicula']['titulo'] }}</p>
        <ul>
            @foreach($datosCompra['butacas'] as $butaca)
                <li>Fila: {{ $butaca['row'] }} Butaca: {{ $butaca['column'] }} - Precio: {{ $butaca['precio'] }}€</li>
            @endforeach
        </ul>
        <p>Día: {{ $datosCompra['datosSesion']['sesion']['fecha'] }}</p>
        <p>Hora: {{ $datosCompra['datosSesion']['sesion']['hora'] }}</p>
        <!-- Mostrar más detalles de la compra si es necesario -->
    </div>
</body>
</html>
