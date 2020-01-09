## Directory Demo

### Docker

La forma más sencilla, rápida y menos intrusiba de desplegar la aplicación es el uso de Docker. El proyecto dispone de un
fichero *Dockerfile* para poder construir una imagen con todas las dependencias. También se dispone de un fichero 
*docker-compose.yml* que nos permitirá desplegar la aplicación de forma sencilla en nuestro equipo:

    docker-compose up
    
La imagen docker realiza la migración del modelo de base de datos al iniciar, con lo que no es necesario realizarla de forma
manual como se describe en pasos posteriores.

Para acceder a la linea de comandos una vez lanzado el entorno con docker utilizaremos los siguiente comando:

    docker-compose exec command
    
donde command, será el comando que desamos ejecutar, por ejemplo `docker-compose exec app bin/console fixtures:set` para
realizar la carga de los datos de prueba que veremos a continuación.

### Puesta en marcha sin Docker

Se trata de un proyecto PHP que utiliza Composer como gestor de dependencias, con lo que es necesario
tener Composer instalado. Para realizar la instalación de las dependencias lanzaremos el siguiente comando en la raíz 
del proyecto:

    composer install 

Existen dependencias de NPM para la interface de usuario, con lo que es necesario disponer de una versión actualizada
de node y las heramientas npm o yarn instaladas. Lanzaremos el siguiente comando en la raíz del proyecto:

    yarn install
    yarn build
    
En caso de no utilizar yarn:
    
    npm install
    npm run build
    
Para garantizar que todo funciona correctamente es recomendable realizar un warmup de la caché de aplicación, utilizaremos
 el siguiente comando:

    ./bin/console cache:warmup
    
Al tratarse de un proyecto demo donde el performance no es una prioridad, podemos utilizar en servidor embedido de php para
servir las peticiones:

    /usr/bin/php -S 127.0.0.1:8000 -t public
    
Una vez lanzado el servidor web, podemos acceder a la aplicación desde un explorador en la url http://127.0.0.1:8000

### Fixtures

El siguiente comando realiza una carga de usuarios de prueba para que podamos testear la aplicación sin tener que añadir
usuarios:

    ./bin/console fixtures:set

### Base de datos

Con el fin de simplificar el despliegue y al tratarse de un demo se ha optado por utilizar la implementación de SQLite 
para Doctrine, no obstante, Doctrine proporciona una excelente abstracción y puede ser facilmente migrado a otros motores 
ajustando la configuración dentro del fichero`.env` y con la ayuda de las utilidades de migraciones de Doctrine, 
tendríamos una implementación para multitud de motores de forma sencilla y rápida.

Para realiza la instalación de la base de datos tenemos que utilizar el siguiente comando:

    ./bin/console migrations:migrate
    
Éste llevará la base de datos a la última versión disponible.

### Test

Exiten dos grupos de tests, unos con un enfoque más unitario y funcional con PHPUnit, y otros con un enfoque de integración
que utiliza Cypress. Para ejecutar los test de PHPUnit utilizaremos el siguiente comando:

    composer test
    
La ejecución de los test con Cypress requieren de un entorno previo, para garantizar el aislamiento y la inmutabilidad de
estos. Se ha creado un script que lanzará un entorno docker y ejecutará los tests:

    ./bin/run_e2e_tests.sh
    
### Interface de comandos

Las funcionalidades básica están implementadas tanto en interface web/rest como por linea de comandos, disponemos de una
serie de comandos para interactuar con la plataforma:

      directory:user:create            
      directory:user:get               
      directory:user:search