<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# INE Recognizer

## Información

Este servicio proporciona una API fácil de utilizar y configurar para realizar análisis y verificación de rostros, detección y extracción de textos mediante el servicio de Azure FaceAPI.

## Requerimientos

-   Laravel 9

-   MariaDB 10.4.24

## Servicios de Microsoft Azure

-   Azure FaceAPI configurado correctamente

-   Modelo de reconocimiento de Azure Form Recognizer entrenado de acuerdo a las siguientes especificaciones

### Azure Form Recognizer

El modelo compuesto utilizado para realizar el reconocimiento de la Identificación Nacional de Elector (INE), debe contener los siguientes modelos diferentes, entrenado cada uno con al menos 5 fotografías diferentes. Este entrenamiento puede ser llevado a cabo mediante Form Recognizer Studio. Este modelo debe ser entrenado mediante entrenamiento de plantillas (Template) y utilizando la Version 3 de [Form Recognizer Custom Models](https://docs.microsoft.com/en-us/azure/applied-ai-services/form-recognizer/v3-migration-guide).

> Para mas información sobre como realizar el entrenamiento de los modelos, siga la documentación proporcionada por Microsoft Azure. [Azure Form Recognizer Docs](https://docs.microsoft.com/en-us/azure/applied-ai-services/form-recognizer/how-to-guides/build-custom-model-v3)

#### 1. Parte delantera del INE

Asegurarse de entrenar el modelo teniendo en cuenta la existencia de las diferentes versiones de INE expedidas hasta el momento.

-   apellido_materno

-   pais_emision

-   nacimiento

-   curp

-   registro

-   domicilio

-   localidad

-   vigencia

-   identificacion

-   sexo

-   estado

-   nombre

-   seccion

-   municipio

-   apellido_paterno

-   emision

-   clave_elector

> El JSON generado por el modelo debe contener los atributos exactamente iguales a los descritos anteriormente.

#### 2. Parte trasera del INE

Asegurarse de entrenar el modelo teniendo en cuenta la existencia de las diferentes versiones de INE expedidas hasta el momento.

-   identificador_titular

-   identificador_fecha

-   identificador_documento

> El JSON generado por el modelo debe contener los atributos exactamente iguales a los descritos anteriormente.

Luego de realizar el entrenamiento de los modelos descritos anteriormente, debe crear un solo modelo compuesto. Este modelo compuesto será el utilizado para realizar el reconocimiento utilizado en el servicio.

## Azure FaceAPI

Se debe contar con un recurso de Azure FaceAPI correctamente configurado. Para ver información sobre la configuración, consulte la siguiente [documentación oficial](https://docs.microsoft.com/es-mx/azure/cognitive-services/face/)

## Configuración de variables de entorno

Para garantizar el correcto funcionamiento del servicio, se deben configurar las siguientes variables de entorno en el archivo .env del proyecto. Se puede tomar como ejemplo el archivo .env.example dentro del repositorio.

    /.env
    ADMIN_USER=
    ADMIN_PASS=
    ADMIN_EMAIL=

    SUBSCRIPTION_KEY=
    FACEAPI_SUBSCRIPTION_KEY=
    URL_BASE_FACEAPI=

### Credenciales de Azure

-   SUBSCRIPTION_KEY : Esta debe contener la llave generada por el recurso de Azure Form Recognizer donde se encuentra alojado el modelo compuesto entrenado previamente.

-   FACEAPI_SUBSCRIPTION_KEY : Esta debe contener la llave generada por un recurso de Azure FaceApi donde se encuentra alojado servicio de reconocimiento de Azure ([FaceAPI](https://docs.microsoft.com/en-us/azure/cognitive-services/face/face-api-how-to-topics/howtodetectfacesinimage)).

-   URL_BASE_FACEAPI : Esta debe contener el URL del servicio de Azure FaceAPI proporcionado por ellos. Esta se puede encontrar en la información del recurso desde el portal de Azure.

### Credenciales del administrador

-   ADMIN_USER : Este nombre de usuario será asignado como nombre del administrador y del comercio generado para su uso.

-   ADMIN_PASS : Este debe contener la contraseña utilizada por el administrador necesario para realizar el inicio de sesión dentro del servicio.

-   ADMIN_EMAIL : Este debe contener el correo electrónico del administrador necesario para realizar el inicio de sesión dentro del servicio.

## Servicio de envió de correos electrónicos.

Es importante configurar un proveedor de correos electrónicos para realizar el envió de credenciales a cada uno de los comercios que sean registrados. Esto incluye la configuración de las variables de entorno relacionadas al envió de emails en Laravel.

## Instalación

Clonando el repositorio

    git clone https://github.com/JuanGuillenMartinez/ine-recognizer.git

Primero hay que instalar todas las librerías necesarias para el correcto funcionamiento del sistema mediante

    composer install

Una vez se han instalado las librerías, se deben generar las llaves del proyecto de Laravel mediante el siguiente comando.

    php artisan key:generate

Para correr las migraciones necesarias en el servicio es necesario ejecutar el siguiente comando.

    php artisan migrate

Para generar las credenciales del administrador, roles y permisos del servicios, es necesario ejecutar el siguiente comando.

    php artisan db:seed

## Cola de procesos

Es muy importante recalcar que se debe mantener una cola ejecutándose en el servidor para realizar tareas como el entrenamiento de los rostros de personas, envió de correos electrónicos, etc. Para iniciar la cola, es necesario ejecutar el siguiente comando.

    php artisan queue:work

## Corriendo el servicio

Una vez que se ha configurado todo, el servicio se puede usar mediante la ejecución del comando

    php artisan serve

### Documentación sobre la API

Una vez el servicio se encuentra ejecutándose correctamente, se puede acceder a la documentación de esta mediante http://127.0.0.1:8000/docs.

Esta documentación se encuentra realizada utilizando Swagger y OpenAPI 3.0.

## Licencia

Laravel es un framework de código abierto sobre la licencia. [MIT license](https://opensource.org/licenses/MIT).

## Contacto

Correo electrónico: juanguillenmtz16@gmail.com
