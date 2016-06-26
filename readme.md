# Pacote com diversas features para criação de projetos mais organizados em Laravel 5.x ou Lumen 5.x #

*Documentação e Projeto em desenvolvimento NÃO USE EM PRODUÇÃO!*
    
## Laravel 5.2.x ##

Registrar o service provider (bootstrap/app):

	$app->register(Lab123\Odin\Providers\ServiceProvider::class);
	
Rodar comando 
	
	php artisan vendor:publish


## Lumen 5.2.x ##

Registrar o service provider (bootstrap/app):

	$app->register(Lab123\Odin\Providers\LumenServiceProvider::class);


Registrar em Console\Kernel.php

	protected $commands = [
    	\Lab123\Odin\Command\LumenVendorPublish::class,
    	\Lab123\Odin\Command\LumenAppNameCommand::class
	]

Rodar comando 
	
	php artisan app:name "NomeDoSeuProjeto"
	
Rodar comando 
	
	php artisan vendor:publish

Registrar no composer os helpers:

    "autoload": {
	    "psr-4": {
	        "App\\": "app/"
	    },
	    "files": [
	        "app/Supports/helpers.php"
	    ]
	}
	
## ENV ##

Não esqueça de parametrizar a URL principal do sistema

** API_URL=api.meu-sistema.dev **
	
	
## Recursos ##


#### Entidades ####

As entidades são recursos do seu sistema/api, elas são Models do Laravel mas bombadas!

Veja algumas propriedades extras:

	protected $resource = "meu-recurso";
	
Essa propriedade define o nome do recurso que será retornado na propriedade url na consulta ao seu recurso.

**Ex:**

	class User extends Entity
	{
	    protected $resource = "users";
	}
 
	GET http://api.meu-sistema.dev/users
	{
		"url" => "http://api.meu-sistema.dev/users",
		"nome" => "Jean Pierre",
		"idade" => 23
	}

**Obs: Caso ela seja omitida, será usado o nome da tabela.**