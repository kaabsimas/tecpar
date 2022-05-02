# Desafio Brasil Tecpar

A seguinte documentação visa detalhar o procedimento de instalação, configuração e teste do projeto.

## Requerimentos

* PHP >= 7.2 
* composer >= 1.10.1

## Instalação

Após clonar o repositório, entrar no diretório do projeto e executar a instalção das dependências com o composer:

```
composer install
```

Copiar o arquivo .env para um .env.local e adicionar as configurações preferenciais de banco de dados na variável `DATABASE_URL`

```
cp .env .env.local
```

Em seguida executar a migration para iniciar o banco de dados:

```
php bin/console doctrine:migrations:migrate
```

Tenha em mente que após executar o comando de "mineiração" de blocos, será necessário resetar o banco antes de executar novamente, afim de evitar mais de uma sequência de encadeamentos:

```
//resetar banco
php bin/console doctrine:migrations:migrate first -n
```

## Testando a URL

Para realizar chamadas na URL de busca de hash, deve-seminiciar o servidor do Symfony:

```
symfony server:start
```

E em seguida, realizar uma requisição http GET para `http://localhost:8000/miner/mine/{entrada}` ou acessar o mesmo endereço via um navegador, onde {entrada} é a palavra para a qual se deseja encontrar um hash que atenda à uma determinada dificuldade:
Exemplo de requisição realizada com o Insomnia:

```
> GET /miner/mine/avato HTTP/1.1
> Host: localhost:8000
> User-Agent: insomnia/2021.3.0
> Accept: */*
```

Testando com o Curl:

```
curl --request GET --url http://localhost:8000/miner/mine/avato
```

Resposta:
 
```
{
  "hash": "000046cd920372f29a0d564769cc4e5f",
  "key": "Q4nkyFNV",
  "hashes": 169075
}
```

Onde "key" é a string aleatória que foi concatenada com "avato", hasheada com md5 para se encontrar o "hash" 000046cd920372f29a0d564769cc4e5f, após "hashes", 169075, tentativas.

A URL tem um limite de 10 chamadas por minuto por IP, contando a partir da primeira chamada.

## O comando

Para se testar o comando deve-se abrir um terminal e alterar o diretório para o raiz do projeto. Em seguida executar:

```
php bin/console avato:test {entrada} --requests={número de chamadas}
```

Onde {entrada} é a palavra que será hasheada na URL e {número de chamadas} é quantas vezes será feita uma chamada utilizando o resultado de uma como a entrada da próxima, registrando os resultados na tabela "block", encadeados pelos hashes.

Exemplo: 

```
php bin/console avato:test brasil --requests=50
```

Irá realizar 50 chamadas à URL `/miner/mine`, contando 6 segundos de intervalo entre cada chamada, calculando o tempo gasto esperando a resposta. Isso é feito para evitar que o IP local seja bloqueado quando forem realizadas 10 requisições até se completar um minuto.

Antes de executar novamente o comando, caso se deseje ter apenas registros de uma execução do comando para consultar posteriormente, deve-se resetar o banco de dados manualmente, utilizando o comando de migration do Symfony.

```
//resetar banco
php bin/console doctrine:migrations:migrate first -n

//recriar a tabela block
php bin/console doctrine:migrations:migrate
```

## Visualização de resultados

Para consultar os resultados, em páginas de 10 registros, a rota principal do site retorna um objeto json contendo as informações:

```
> GET / HTTP/1.1
> Host: localhost:8000
```

Resultado:

```
{
  "1": {
    "0": {},
    "batch": {
      "date": "2022-05-02 01:56:43.000000",
      "timezone_type": 3,
      "timezone": "America\/Sao_Paulo"
    },
    "block_height": 1,
    "entry": "brasil",
    "nonce": "LBX7vXRH"
  },
  "2": {
    "0": {},
    "batch": {
      "date": "2022-05-02 01:56:49.000000",
      "timezone_type": 3,
      "timezone": "America\/Sao_Paulo"
    },
    "block_height": 2,
    "entry": "0000db91ef8b880390309d87c9d47387",
    "nonce": "bu1tDsoZ"
  },
  "3": {
    "0": {},
    "batch": {
      "date": "2022-05-02 01:56:53.000000",
      "timezone_type": 3,
      "timezone": "America\/Sao_Paulo"
    },
    "block_height": 3,
    "entry": "0000c89753c68ad4fd1029c09043323c",
    "nonce": "gsVsuZaH"
  },
  "4": {
    "0": {},
    "batch": {
      "date": "2022-05-02 01:56:59.000000",
      "timezone_type": 3,
      "timezone": "America\/Sao_Paulo"
    },
    "block_height": 4,
    "entry": "000092767f612857ab720062f79dd477",
    "nonce": "gDA9sHzj"
  },
  "5": {
    "0": {},
    "batch": {
      "date": "2022-05-02 01:57:04.000000",
      "timezone_type": 3,
      "timezone": "America\/Sao_Paulo"
    },
    "block_height": 5,
    "entry": "00005907b786ef02a9aec2c9fb7fd96c",
    "nonce": "MJTYAfuw"
  },
  "6": {
    "0": {},
    "batch": {
      "date": "2022-05-02 01:57:11.000000",
      "timezone_type": 3,
      "timezone": "America\/Sao_Paulo"
    },
    "block_height": 6,
    "entry": "00009590a3c03358aedb9c7146b06cfe",
    "nonce": "b6XQsUk7"
  },
  "7": {
    "0": {},
    "batch": {
      "date": "2022-05-02 01:57:15.000000",
      "timezone_type": 3,
      "timezone": "America\/Sao_Paulo"
    },
    "block_height": 7,
    "entry": "000073b5d4efe4ffe132afaffdb1d150",
    "nonce": "YL217Pne"
  },
  "8": {
    "0": {},
    "batch": {
      "date": "2022-05-02 01:57:21.000000",
      "timezone_type": 3,
      "timezone": "America\/Sao_Paulo"
    },
    "block_height": 8,
    "entry": "0000d0b7334134b4c677975e21420842",
    "nonce": "XjmLrzQS"
  },
  "9": {
    "0": {},
    "batch": {
      "date": "2022-05-02 01:57:26.000000",
      "timezone_type": 3,
      "timezone": "America\/Sao_Paulo"
    },
    "block_height": 9,
    "entry": "0000fd13fe1959c32ddf1ca55410661c",
    "nonce": "qmr1ueLf"
  },
  "10": {
    "0": {},
    "batch": {
      "date": "2022-05-02 01:57:32.000000",
      "timezone_type": 3,
      "timezone": "America\/Sao_Paulo"
    },
    "block_height": 10,
    "entry": "00002ff467001af208d471e1509b78a5",
    "nonce": "r7hXoyQA"
  }
}
```

Para acessar uma página específica, basta inserir um parâmetro `page` em formato query string na url:

```
> GET /?page=2 HTTP/1.1
> Host: localhost:8000
```

Resposta: 

```
{
  "11": {
    "0": {},
    "batch": {
      "date": "2022-05-02 02:11:18.000000",
      "timezone_type": 3,
      "timezone": "America\/Sao_Paulo"
    },
    "block_height": 13,
    "entry": "00007af589adc0e7619d3ca4463b8e6e",
    "nonce": "YsZYRtUd"
  },
  "12": {
    "0": {},
    "batch": {
      "date": "2022-05-02 02:11:24.000000",
      "timezone_type": 3,
      "timezone": "America\/Sao_Paulo"
    },
    "block_height": 14,
    "entry": "00001b79b80312cbc9a7d48e8b9a86e7",
    "nonce": "HhiBQ6ia"
  },
  "13": {
    "0": {},
    "batch": {
      "date": "2022-05-02 02:11:32.000000",
      "timezone_type": 3,
      "timezone": "America\/Sao_Paulo"
    },
    "block_height": 15,
    "entry": "0000d98ab82441d48400512f0cc6ea94",
    "nonce": "vYYywwZ8"
  }
}
```

Caso seja informada uma página além da quantidade máxima de páginas de acordo com a quantidade de resultados, uma exception será retornada com status 400

```
< HTTP/1.1 400 Bad Request
{
"mensagem": "Page \"9\" does not exist. The currentPage must be inferior to \"2\""
}
```

## Resolução de Problemas

Quaisquer dúvidas ou dificuldades encontradas em testar esse projeto, entrar em contato pelo e-mail albert_usm@hotmail.com ou whatsapp (21) 98024-2385