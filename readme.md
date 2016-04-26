<h1>Pacote com diversão features para criação de projetos mais organizados em Laravel 5.x</h1>

<p>*Documentação e Projeto em desenvolvimento NÃO USE EM PRODUÇÃO!*</p></br>

<p>Entities -> Entidades que usam o ORM Eloquent</p>
<p>Resource	-> Semelhante as entidades, representa a saida para o client</p>
<p>Facades -> Facades para funcionalidades do Pacote</p>
<p>Http:</p>
<p>Controllers -> Controllers com facilitadores para API</p>
<p>Requests	-> Facilitador para gerenciamento de Validações via Request</p>
<p>routes.php -> Include de arquivos no diretório Http\Routes para rotas organizadas em diretórios</p>
<p>Enums -> Prove funcionalidades para gerenciamento de ENUMS</p>
<p>Libs:</p>
<p>Api.php -> Retorna a URI do recurso;</p>
<p>Search.php -> Provê funcionalidade de filtros no modelo via API (fields, criteria, includes, limit, order)</p>
<p>Repositories	-> Funcionalidades para Design Partern Repository (CRUD);</p>
<p>Traits:</p>
<p>ApiResponse -> Gerencia todas as respostas básicas da API, com códigos de retorno padrão HTTP</p>

<h2>Lumen</h2>

<p>Registrar o service provider (bootstrap/app):</p>

<p>$app->register(Lab123\Odin\Providers\LumenServiceProvider::class);</p>


Registrar em Console\Kernel.php

protected $commands = [
    \Lab123\Odin\Command\LumenVendorPublish::class
]

php artisan vendor:publish

Registrar no composer os helpers:

"autoload": {
    "psr-4": {
        "App\\": "app/"
    },
    "files": [
        "app/Helpers/helpers.php"
    ]
},

<p>Usar LumenApiController</p>