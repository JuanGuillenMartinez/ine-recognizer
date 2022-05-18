<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Usuario Registrado</title>
</head>

<body>
    <p>Hola! Hemos recibido su solicitud y le informamos que ha sido aceptada.</p>
    <p>Información del comercio</p>
    <ul>
        <li>ID del Comercio: {{ $user->commerce->id }}</li>
        <li>Comercio: {{ $user->commerce->name }}</li>
    </ul>
    <p>Correo registrado</p>
    <ul>
        <li>Correo electrónico: {{ $user->email }}</li>
        <li>Información generada: {{ $user->updated_at }}</li>
    </ul>
    <p>Puedes revisar la siguiente documentación para mas información:</p>
    <ul>
        <li>
            <a href="https://localhost/docs">
                Documentación
            </a>
        </li>
    </ul>
    <p>Cualquier duda, no dude en responde este mensaje</p>
</body>

</html>