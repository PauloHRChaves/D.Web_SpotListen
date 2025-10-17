# D.Web_ListenSpot
Projeto Web fullstack, modularizado e desacoplado<br>
Temática: música.

### Backend tools:
- Linguagem: ```PHP^8.2.12```<br>
- Gerenciador de dependências: ```COMPOSER```<br>
    Dependências:
    ```
        "vlucas/phpdotenv": "^5.6"
        "guzzlehttp/guzzle": "^7.10"
        "predis/predis": "^3.2"
    ```
- Banco de dados: ```MySQL```<br>
- API's consumidas:
    - <a href="https://developer.spotify.com/documentation/web-api">Spotify API</a>
    - <a href="https://www.last.fm/api">Last.fm API</a>
    - <a href="https://musicbrainz.org/doc/MusicBrainz_API">MusicBrainz API</a>
    - <a href="https://pt.wikipedia.org/w/api.php">MediaWiki API</a>
<br>

### Frontend tools:
- ```HTML```
- ```CSS```
- ```JS```
- ```Bootstrap```
- ```PHP```

<br>

**Recomendações:** Dashboard com Google looker

<br>
<hr>

# Backend:
> Instalar PHP

> Instalar o COMPOSER: <a href="https://getcomposer.org/download">getcomposer</a>
<br>

Modificar arquivo **php.ini**
- Buscar a pasta onde esta instalado o php: ex. **C:\php\php.ini** 
- Abrir / Editar arquivo como bloco de notas
- Buscar ( atalho: ctrl + f ) os itens abaixo e remover o ```;```
    * ;extension=openssl
    * ;extension=zip
    * ;extension=mbstring
    * ;extension=curl
    * ;extension=pdo_mysql
- Salvar arquivo ( atalho: ctrl + s )
<br><br>

No terminal do projeto:
```
cd Backend
```
```
composer install
```
<br>

Arquitetura básica das pastas:
<pre>
├── migrations/         -> versionamento do Banco de Dados
├── public/             -> Ponto de partida
│   └── index.php           -> Front Controller (arquivo pontapé)
├── src/                -> Source - contém toda lógica de negócio
│   ├── Controllers/
│   ├── Exceptions/
│   └── Services/
├── bootstrap.php           -> Iniciar o sistema e conexões 
└── routes.php              -> Arquivo com os Endpoints
</pre>
<br>
<hr>

# Frontend:
Arquitetura básica das pastas:
<pre>
├── static/             -> Arquivos estaticos
│   ├── css/
│   ├── imgs/
│   └── js/
└── templates/          -> Paginas
    ├── auth/           -> Paginas de autenticação
    ├── index.php       -> HOMEPAGE
    └── header.php      -> Cabeçalho de navegação
</pre>

<br>
<hr>

# INICIANDO PROJETO:


### Para "rodar" o Backend é necessario o comando:
```
php -S localhost:8131 -t Backend/public
```
* Build-In-Server do php
### Para "rodar" o FRONTEND é necessario o comando:
```
php -S localhost:8132 -t Frontend
```
* ```Extensão Live Server``` funciona para os arquivos **.html** e não **.php**<br>

#### Acesse a página: http://localhost:8001/templates/index.php
