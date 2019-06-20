# Iugu para PHP

## Requisitos

* PHP 7.0+

## Instalação

```
$ composer require unaspbr/iugu-php
```

O autoload do composer irá cuidar do resto.

## Exemplo de Uso

```php
Iugu::setApiKey("c73d49f9-6490-46ee-ba36-dcf69f6334fd"); // Ache sua chave API no Painel

Charge::create(
    [
        "token"=> "TOKEN QUE VEIO DO IUGU.JS OU CRIADO VIA BIBLIOTECA",
        "email"=>"your@email.test",
        "items" => [
            [
                "description"=>"Item Teste",
                "quantity"=>"1",
                "price_cents"=>"1000"
            ]
        ]
    ]
);
```

## Documentação

Acesse [iugu.com/documentacao](http://iugu.com/documentacao) para referência

## Laravel

Para usar essa biblioteca no Laravel, ver [mateusfccp/iugu-laravel](https://github.com/mateusfccp/iugu-laravel).
