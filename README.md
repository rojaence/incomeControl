# INCOMES CONTROL

## Descripción

- Solo es una pequeña aplicación de práctica utilizando algunos principios del patrón MVC con php
- La función básica es registrar gastos e ingresos personales con diversos tipos de datos

## Tecnologías

- PHP ~ 8.1.2
- MYSQL
- Composer
- [league/plates](https://packagist.org/packages/league/plates) (Motor de plantillas)
- [phpunit](https://packagist.org/packages/phpunit/phpunit) (Tests)
- [php-webdriver](https://packagist.org/packages/php-webdriver/webdriver) (Webdriver para test automatizado)
- Bootstrap (Frontend)

## Características

- Formularios
- Funciones CRUD mediante servicios para cada modelo
- Renderizado de views mediante motor de plantillas
- Uso de un webdriver para controlar un navegador de forma automatizada en los tests

## Configuraciones

- Se debe tener configurado un servidor web ya sea con apache, XAMPP, laragon, etc
- El script de la base de datos está en assets.
- Para instalar las dependencias:

```bash
composer install
```

- Se requiere un archivo .env en la raíz del proyecto con los siguientes datos (ajustar los valores según sea el caso):

```
ENVIRONMENT=production / development

DB_HOST=localhost
DB_NAME=personal_finance
DB_USER=root
DB_PASS=password

DB_HOST_TEST=localhost
DB_NAME_TEST=personal_finance_test
DB_USER_TEST=root
DB_PASS_TEST=password
```

- Cambiando el valor de environment a 'production' o 'development' hará que la conexión cambie de base de datos de producción a test (Para lo cual es necesario tener dos bases de datos)

- Para tests manuales hay un script en la carpeta assets (testDataScript.php) que prepara algunos datos, ejecutarlo en la terminal:

```
php assets/testDataScript.php
```
