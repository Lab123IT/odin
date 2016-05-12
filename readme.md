#Pacote com diversas features para criação de projetos mais organizados em Laravel 5.x

*Documentação e Projeto em desenvolvimento NÃO USE EM PRODUÇÃO!*  
    
**Entities ->** Entidades que usam o ORM Eloquent  
  
**Resource ->** Semelhante as entidades, representa a saida para o client  
  
**Facades ->** Facades para funcionalidades do Pacote  
  
**Controllers ->** Controllers com facilitadores para API  
  
**Requests	->** Facilitador para gerenciamento de Validações via Request  
  
**routes.php ->** Include de arquivos no diretório Http\Routes para rotas organizadas em diretórios  
  
**Enums ->** Prove funcionalidades para gerenciamento de ENUMS  
    
**Api.php ->** Retorna a URI do recurso;  
  
**Search.php ->** Provê funcionalidade de filtros no modelo via API (fields, criteria, includes, limit, order)  
  
**Repositories	->** Funcionalidades para Design Partern Repository (CRUD);  
  
**ApiResponse ->** Gerencia todas as respostas básicas da API, com códigos de retorno padrão HTTP  
  

##Lumen

Registrar o service provider (bootstrap/app):

	$app->register(Lab123\Odin\Providers\LumenServiceProvider::class);


Registrar em Console\Kernel.php

	protected $commands = [
    	\Lab123\Odin\Command\LumenVendorPublish::class
	]

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
