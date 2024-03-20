# Cliente-Servidor CRUD

Este é um projeto simples que implementa um CRUD (Create, Read, Update, Delete) básico usando uma arquitetura cliente-servidor em PHP. Utiliza os seguintes verbos HTTP:
- GET
- POST
- PUT
- DELETE
- OPTIONS 

## Funcionalidades

- **CRUD**: Implementação das operações básicas de CRUD para gerenciar dados de clientes.
- **Cliente**: Classe representando um cliente com atributos como nome, e-mail, cidade e estado.
- **ClienteRepository**: Repositório para acessar e manipular os dados dos clientes.
- **Endpoints**: Implementação de endpoints para criar, listar, obter por ID, atualizar e excluir clientes.
- **Postman Collection**: Uma coleção do Postman está disponível com exemplos de requisições para testar os endpoints.

## Pré-requisitos

- PHP 7.x ou superior
- Servidor web (Apache, Nginx, etc.)
- Postman (para testar os endpoints)

## Estrutura da tabela
```sql
CREATE TABLE `clientes` (
  `cliente_id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`cliente_id`)
) ;
```

## Estrutura do Projeto
```bash
├── App/
│ ├── Database/
│ │ └── config.php
│ ├── Model/
│ │ └── Cliente.php
│ ├── Repository/
│ │ └── ClienteRepository.php
│ └── index.php
├── img/
│ ├── delete.png
│ ├── estrutura.png
│ └── ...
├── composer.json
├── composer.lock
├── .gitignore
└── README.md
```
## GET com erro 404
![image](https://github.com/JoaoEnrique13/cliente-servidor-crud/assets/99426704/ff476b8a-ba10-4b45-a3ae-4ce475388141)

## POST - insert
![image](https://github.com/JoaoEnrique13/cliente-servidor-crud/assets/99426704/45392b1c-9d54-4297-8e92-ad89e6a9c83d)

![image](https://github.com/JoaoEnrique13/cliente-servidor-crud/assets/99426704/008cde40-91a1-414d-9581-08a77724963b)

## Todos os registros – getAll():
![image](https://github.com/JoaoEnrique13/cliente-servidor-crud/assets/99426704/b621362e-c1fe-4c94-abd2-b8ddee0339ef)

## Registro específico – getById():
![image](https://github.com/JoaoEnrique13/cliente-servidor-crud/assets/99426704/4fe1b423-dd0c-48ef-aa8b-53aa863fd219)

## Registro específico não encontrado (404):
![image](https://github.com/JoaoEnrique13/cliente-servidor-crud/assets/99426704/a37efb87-41e6-4ead-8c80-7004b4895eb9)


## PUT para atualização: 
![image](https://github.com/JoaoEnrique13/cliente-servidor-crud/assets/99426704/02c59723-a9fd-4f96-bf40-6b880f034115)

## PUT para inserção:
![image](https://github.com/JoaoEnrique13/cliente-servidor-crud/assets/99426704/96c18be5-218a-4c7d-ad0f-f9551afe3a05)

## DELETE
![image](https://github.com/JoaoEnrique13/cliente-servidor-crud/assets/99426704/2b1b99ce-bc34-49a5-a051-05f8a597deef)
