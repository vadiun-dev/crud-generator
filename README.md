# This is my package crud-generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vadiun-dev/crud-generator.svg?style=flat-square)](https://packagist.org/packages/vadiun-dev/crud-generator)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/vadiun-dev/crud-generator/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/vadiun-dev/crud-generator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/vadiun-dev/crud-generator/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/vadiun-dev/crud-generator/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/vadiun-dev/crud-generator.svg?style=flat-square)](https://packagist.org/packages/vadiun-dev/crud-generator)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require hitocean/crud-generator
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="crud-generator-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="crud-generator-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="crud-generator-views"
```

## Usage

### Configuracion de modelo
```php
php artisan make:hit-model-config
```
Este comando permite generar un archivo de configuracion para la generacion de un modelo. Este archivo va a estar situado en la carpeta _**generators/models**_. 

El archivo generado se puede ver asi.

```json
{
    "modelName": "Company",
    "root_folder": "src/Domain/Company/Models",
    "root_namespace": "Src\\Domain\\Company\\Models",
    "tableName": "companies",
    "crud": true,
    "attributes": [
        {
            "name": "name",
            "type": "string"
        },
        {
            "name": "email",
            "type": "string"
        },
        {
            "name": "address",
            "type": "?string"
        }
    ]
}
```
En este archivo se pueden modificar todos los valores que se deseen antes de generar los archivos para el modelo. 

### Crear Modelo
```php
php artisan make:hit-model
```

Este comando permite generar un modelo con los atributos y relaciones que se definieron en el archivo de configuracion.
Va a generar los siguientes archivos:
- Modelo
- Factory
- Migracion

**Es importante destacar que al correr el comando se van a tratar de crear todos los archivos dentro de generators/models. Por lo que si un modelo ya esta creado lo va a intentar reemplazar**

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [joaquin3684](https://github.com/vadiun-dev)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Cosas que faltan
- Tipo texto largo
- Tipo relaciones
- Tipo imagen
- Tipo Enum
- Generar Controlador
- Generar Resource
- Generar Migracion
- Generar Data
- Generar Tests
- Rollback
