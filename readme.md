#Pacote com diversas features para criação de projetos mais organizados em Laravel 5.x

*Documentação e Projeto em desenvolvimento NÃO USE EM PRODUÇÃO!*  
    
##Laravel 5.2.x

Registrar o service provider (bootstrap/app):

	$app->register(Lab123\Odin\Providers\ServiceProvider::class);
	
Rodar comando 
	
	php artisan vendor:publish


##Lumen 5.2.x

Registrar o service provider (bootstrap/app):

	$app->register(Lab123\Odin\Providers\LumenServiceProvider::class);


Registrar em Console\Kernel.php

	protected $commands = [
    	\Lab123\Odin\Command\LumenVendorPublish::class,
    	\Lab123\Odin\Command\LumenAppNameCommand::class
	]

Rodar comando 
	
	php artisan app:name 'NomeDoSeuProjeto'
	
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
