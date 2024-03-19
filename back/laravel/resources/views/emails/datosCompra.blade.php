<!DOCTYPE html>
<html>
<head>
    <title>Detalles de la compra</title>
</head>
<body>
    <h1>Detalles de la compra</h1>
    <p>Película: {{ $datosCompra['datosSesion']['pelicula']['titulo'] }}</p>
    <p>Butacas:</p>
    <ul>
        @foreach($datosCompra['butacas'] as $butaca)
            <li>Butaca: {{ $butaca['id'] }} - Precio: {{ $butaca['precio'] }}€</li>
        @endforeach
    </ul>
    <p>Día: {{ $datosCompra['datosSesion']['sesion']['fecha'] }}</p>
    <p>Hora: {{ $datosCompra['datosSesion']['sesion']['hora'] }}</p>
    <!-- Mostrar más detalles de la compra si es necesario -->
</body>
</html>
