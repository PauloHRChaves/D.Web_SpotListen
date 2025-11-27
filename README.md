# D.Web_ListenSpot
Projeto Web fullstack, modularizado e desacoplado<br>
Temática: música.

### Backend tools:
- Linguagem: ```PHP^8.2.12```<br>
- Banco de dados: ```MySQL```<br>
- API's consumidas:
    - <a href="https://developer.spotify.com/documentation/web-api">Spotify API</a>
    - <a href="https://www.last.fm/api">Last.fm API</a>
    - <a href="https://musicbrainz.org/doc/MusicBrainz_API">MusicBrainz API</a>
    - <a href="https://www.theaudiodb.com/free_music_api">Theaudiodb</a>
<br>

### Frontend tools:
- ```HTML```
- ```CSS```
- ```JS```
- ```Bootstrap```
- ```PHP```

<br>
<hr>

# SITES DE APOIO:
- <a href="https://icons.getbootstrap.com/">Bootstrap Icons</a>
- <a href="https://color.adobe.com">Adobe Colors</a>
- <a href="https://uiverse.io/">Bootstrap</a>

# Backend:
> Instalar PHP
<br>

**CASO NECESSÁRIO**
Modificar arquivo **php.ini**
- Buscar a pasta onde esta instalado o php: ex. **C:\php\php.ini** 
- Abrir / Editar arquivo como bloco de notas
- Buscar ( atalho: ctrl + f ) os itens abaixo e remover o ```;```
    * ;extension=openssl
    * ;extension=zip
    * ;extension=mbstring
    * ;extension=curl
    * ;extension=pdo_mysql
- Salvar arquivo
<br><br>

Arquitetura básica das pastas:
<pre>
├── migrations/             -> versionamento do Banco de Dados
├── public/                 -> Ponto de partida
│   └── index.php           -> Front Controller (arquivo pontapé)
├── src/                    -> Source - contém toda lógica de negócio
│   ├── Config/
│   ├── Controllers/
│   ├── Exceptions/
│   ├── Infrastructure/
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
├── templates/          -> Paginas
│   ├── auth/           -> Paginas de autenticação
│   └── header.html     -> Cabeçalho de navegação
└── index.html  
</pre>

<br>
<hr>

# INICIANDO PROJETO:

### Para "rodar" o Backend é necessario os comandos:
```
php Backend/migrations/build.php
```
```
php -S 127.0.0.1:8131 -t Backend/public
```

### Para "rodar" o FRONTEND é necessario o comando:
```
php -S 127.0.0.1:8132 -t Frontend
```

#### Acesse a página: http://127.0.0.1:8132
