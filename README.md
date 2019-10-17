# Incluir Tecnologia - Prova de Desenvolvedor - 2019

Esta prova tem como objetivo verificar se o candidato possui as seguintes competências:

1. Entendimento básico de git/github.
2. Entendimento básico de configuração de um ambiente de desenvolvimento Web no Linux ou sistema equivalente.
3. Entendimento básico de PHP, orientação a objetos e padrões de estilo de código.
4. Capacidade de realizar pequenas modificações em aplicações existentes.

O candidato deverá executar as atividades definidas na seção **5. Atividade avaliativa**. As seções **1.** a **4.** descrevem instruções de instalação, uso e dicas sobre o projeto.

---

## 1. Configuração mínima

- git
- Docker e Docker Compose
- Bom editor de código (é sugerido o **VSCode** com a extenção **PHP Intelephense**)
- **curl** e **jq** para realização das requisições http e estilização da resposta.

---

## 2. Instalação

### Docker

-   Docker (CE): https://docs.docker.com/install/linux/docker-ce/ubuntu/
-   Docker Compose: https://docs.docker.com/compose/install/
-   Etapas de pós-instalação: https://docs.docker.com/install/linux/linux-postinstall/

Importante: Para ver como este projeto utiliza o docker, abra o arquivo __docker-compose.yml__ no diretório raíz.

### curl e jq

- No linux digite o seguinte comando no terminal: `sudo apt install curl jq -y`

Importante: Você pode utilizar qualquer cliente http ao invés do curl. Ex: Postman, Chrome, Firefox, ...

---

## 3. Inicialização

### Docker

Na raíz do projeto, digite o comando abaixo (a aplicação ficará disponível enquanto esse comando estiver rodando):

```sh
docker-compose up
```

Você deverá ver uma série de informações referentes a inicialização dos *containers* da aplicação.

Em uma nova aba de terminal, digite o comando abaixo:

```sh
docker exec -it incluir2019-php bash
```

Com esse comando você terá acesso ao **Container do PHP** que roda a aplicação.

### Instalação de dependências

Para instalar todas as dependências de PHP do projeto, o seguinte comando deve ser executado (dentro do **Container do PHP**):

```sh
composer install
```
### Criando tabelas do Banco de Dados

Dentro do **Container do PHP**, rode o comando abaixo:

```
vendor/bin/doctrine-migrations migrations:migrate
```

serão criadas três tabelas: `municipio`, `bolsa_familia` e `doctrine_migration_versions`.

### Acessando o Banco de Dados

O banco de dados pode ser acessado via **Container do Mysql**. Digite o comando abaixo em uma nova aba do terminal.

```sh
docker exec -it incluir2019-sql bash
mysql -u provaincluir2019 -p provaincluir2019
# password: provaincluir2019
show tables;
```

---

## 4. Sobre o Projeto


### Realizando consultas


1. Consultar municípios

Utilize os comando abaixo fora dos *containers*.


```sh
curl -X GET "http://localhost:8888/municipio" | jq
```

Resultado:

```json
{
  "code": 200,
  "message": "ok",
  "data": [
    {
      "id": 29,
      "codigo_ibge": "3132404",
      "nome_cidade": "Itajubá"
    },
    {
      "id": 30,
      "codigo_ibge": "3152501",
      "nome_cidade": "Pouso Alegre"
    }
  ]
}
```

1. Consultar bolsa família de um município (Itajubá = 3132404) em um intervalo no intervalo de datas [06/04/2016, 01/08/2016]

```sh
curl -X GET "http://localhost:8888/municipio/3132404/bolsa-familia?data_inicial=06/04/2016&data_final=01/08/2016" | jq
```

Resultado:

```json
{
  "code": 200,
  "message": "ok",
  "data": [
    {
      "id": 29,
      "municipio": "Itajubá",
      "data_referencia": "01/05/2016",
      "valor_total": "R$ 459.034,00",
      "quantidade_beneficiados": 2679
    },
    {
      "id": 30,
      "municipio": "Itajubá",
      "data_referencia": "01/06/2016",
      "valor_total": "R$ 458.259,00",
      "quantidade_beneficiados": 2662
    },
    {
      "id": 31,
      "municipio": "Itajubá",
      "data_referencia": "01/07/2016",
      "valor_total": "R$ 549.784,00",
      "quantidade_beneficiados": 2851
    },
    {
      "id": 32,
      "municipio": "Itajubá",
      "data_referencia": "01/08/2016",
      "valor_total": "R$ 549.145,00",
      "quantidade_beneficiados": 2828
    }
  ]
}
```

### Banco de dados

O banco de dados é manipulado via ORM. Consulte a documentação do [Doctrine](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/basic-mapping.html#basic-mapping) em caso de dúvidas.

## 5. Atividade avaliativa

### Introdução

1. Consultar o site de dados do governo e baixar a [lista de códigos do SIAFI](http://dados.gov.br/dataset/lista-de-orgaos-do-siafi).
2. Encontrar o código do SIAFI referente a Universidade Federal de Itajubá (Órgão UGE Código).
3. Acessar a documentação de [API do governo](http://www.transparencia.gov.br/swagger-ui.html#!/Licita231245es32do32Poder32Executivo32Federal/licitacoesUsingGET).
4. Inserir data inicial 01/01/2016, data final 31/01/2016, código da Universidade Federal de Itajubá e clicar em "Testar Agora!".
5. Você verá um *json* de resultado das licitações parecido com o recorte abaixo:

```json
[
  {
    "id": 230912613,
    "dataResultadoCompra": "02/02/2016",
    "dataAbertura": "27/01/2016",
    "dataReferencia": "12/01/2016",
    "dataPublicacao": "12/01/2016",
    "situacaoCompra": {
      "codigo": 103,
      "descricao": "Publicado"
    },
    "modalidadeLicitacao": {
      "descricao": "Pregão - Registro de Preço"
    },
    "instrumentoLegal": null,
    "valor": 0,
    "municipio": {
      "codigoIBGE": "3132404",
      "nomeIBGE": "ITAJUBÁ",
      "pais": "BRASIL",
      "uf": {
        "sigla": "MG",
        "nome": "MINAS GERAIS"
      }
    },
    "unidadeGestora": {
      "codigo": "153030",
      "nome": "UNIVERSIDADE FEDERAL DE ITAJUBA/MG",
      "descricaoPoder": "EXECUTIVO",
      "orgaoVinculado": {
        "codigoSIAFI": "26261",
        "cnpj": "",
        "nome": "Universidade Federal de Itajubá",
        "naturezaJuridica": {
          "codigo": "0000    ",
          "descricao": "Sem informação",
          "codigoTipo": "0 ",
          "descricaoTipo": "Sem informação"
        },
        "sigla": ""
      },
      "orgaoMaximo": {
        "codigo": "26000",
        "nome": "Ministério da Educação",
        "sigla": ""
      },
      "existeFatoDespesa": false,
      "existeFatoReceita": false
    },
    "licitacao": {
      "numero": "000022016",
      "objeto": "Objeto: Pregão Eletrônico -  Registro de preços para eventual compra de pneus, incluindo os serviços de montagem, alinhamento, balanceamento, cambagem e caster para os veículos oficiais da UNIFEI/Itajubá, conforme condições, quantidades e exigências estabelecidas neste Edital e seus anexos.",
      "numeroProcesso": "23088019594201508",
      "contatoResponsavel": "LEANDRO LOPES TRINDADE"
    }
  },
  {
    "id": 231420069,
    "dataResultadoCompra": "07/01/2016",
    "dataAbertura": "07/01/2016",
    "dataReferencia": "22/12/2015",
    "dataPublicacao": "22/12/2015",
    "situacaoCompra": {
      "codigo": 103,
      "descricao": "Publicado"
    },
    "modalidadeLicitacao": {
      "descricao": "Pregão - Registro de Preço"
    },
    "instrumentoLegal": null,
    "valor": 0,
    "municipio": {
      "codigoIBGE": "3132404",
      "nomeIBGE": "ITAJUBÁ",
      "pais": "BRASIL",
      "uf": {
        "sigla": "MG",
        "nome": "MINAS GERAIS"
      }
    },
    "unidadeGestora": {
      "codigo": "153030",
      "nome": "UNIVERSIDADE FEDERAL DE ITAJUBA/MG",
      "descricaoPoder": "EXECUTIVO",
      "orgaoVinculado": {
        "codigoSIAFI": "26261",
        "cnpj": "",
        "nome": "Universidade Federal de Itajubá",
        "naturezaJuridica": {
          "codigo": "0000    ",
          "descricao": "Sem informação",
          "codigoTipo": "0 ",
          "descricaoTipo": "Sem informação"
        },
        "sigla": ""
      },
      "orgaoMaximo": {
        "codigo": "26000",
        "nome": "Ministério da Educação",
        "sigla": ""
      },
      "existeFatoDespesa": false,
      "existeFatoReceita": false
    },
    "licitacao": {
      "numero": "001152015",
      "objeto": "Objeto: Pregão Eletrônico -  Registro de preços para eventual compra de pneus, incluindo os serviços de montagem, alinhamento, balanceamento, cambagem e caster para os veículos oficiais da UNIFEI/Itajubá.",
      "numeroProcesso": "23088019594201508",
      "contatoResponsavel": ""
    }
  }
]
```

### Descrição da Prova

A prova consiste em consultar os dados do governo (via api), salvar no banco de dados e criar uma rota de consulta. Para isso você deverá:

1. Persistência:

   1. Criar uma entidade na pasta `src/Entities` (nome sugerido: `Licitacao`) com os atributos:
      1.  id: gerado pelo banco de dados.
      2.  municipio: deve ser uma associação do tipo ManyToOne com a entidade Municipio (exatamente como feito na entidade BolsaFamilia)
      3.  dataReferencia: campo dataReferencia do json
      4.  nomeOrgao: campo unidadeGestora.orgaoVinculado.nome
      5.  codigoOrgao: campo unidadeGestora.orgaoVinculado.codigoSIAFI
      6.  dataPublicacao: campo dataPublicacao
      7.  dataResultadoCompra: campo $dataResultadoCompra
      8.  objetoLicitacao (length=1000): campo licitacao.objeto
      9.  numeroLicitacao: campo licitacao.numero
      10. responsavelContato: campo licitacao.contatoResponsavel;
   2. Adicionar o atributo `licitacoes` na entidade Municipio para mapeamento OneToMany para licitações (simular ao feito no atributo `bolsaFamilia`).
   3. Validar o mapeamento via comando `vendor/bin/doctrine orm:validate-schema` (dentro do **Container do PHP**)
   4. Gerar uma migração via comando `vendor/bin/doctrine-migrations migrations:diff` (dentro do **Container do PHP**)
   5. Aplicar a migração no banco de dados via commando `vendor/bin/doctrine-migrations migrations:migrate`.
   6. Verificar (acessando o banco de dados) se a tabela de licitações foi criada.

2. Download dos dados:

   1. Criar um método para consulta de licitações no arquivo `src/Services/Transparencia.php` (similar ao que foi feito para o bolsa família).
   2. Criar, na pasta `src/DbFixtures`, uma semente (ou *fixture*) que utiliza a classe `Transparencia` para consultar programaticamente o sistema do governo e salvar os dados no banco de dados (similar ao arquivo `src/DbFixtures/BolsaFamiliaLoader.php`). Nome sugerido: `LicitacaoLoader.php`, essa classe deve receber o código SIAFI pesquisado e o intervalo de datas (01/01/2016, 31/12/2018). Como o limite de datas é de um mês, será necessário realizar várias consultas internas.
   3. Inclua sua *fixture* no arquivo `config/data-fixtures.php` do mesmo modo como foi adicionada a *fixture* para o bolsa família. Obs.: aconselha-se a deixar comentada a linha `$loader->addFixture(new BolsaFamiliaLoad...` e descomentar quando o seu código de licitações estiver funcionando corretamente.
   4. Não se preocupe caso veja o erro **Não foi possível estabelecer conexão com a API**, esse erro ocorre por falha temporária na internet, basta tentar novamente.
   5. Rodar o comando `php config/data-fixtures.php` para salvar os dados no banco de dados. Como serão feitas muitas consultas na api do governo é normal que o script demore.
   6. Verificar (acessando o banco de dados) se a tabela de licitações foi preenchida. Não se preocupe se ao invés de acentos você ver o símbolo "�".

3. Serviços
    1. Dentro da Pasta `Services/Db` criar uma classe para consultas de licitações (similar ao feito para o BolsaFamilia).
    2. Dentro da pasta `Actions` criar uma pasta chamada `Licitacao` e dentro dela uma action com o nome `Licitacao`.

4. Action e Rota
   1. Criar uma rota que utiliza o serviço criado e retorna os resultados de licitações (simular ao feito na action `src/Actions/BolsaFamilia/BolsaFamiliaMes`).
   2. Registrar a rota no arquivo `config/routes.php` (Sugestão de rota `/municipio/{codigoIbge}/licitacoes`).

5. Utilizando a rota criada:
    1. utilize o curl para verificar se os dados estão sendo retornados pela API (similar ao descrito na **Seção 4**).

6. Resultado Esperado:

```sh
curl -X GET "http://localhost:8888/municipio/3132404/licitacoes?data_inicial=06/04/2016&data_final=01/05/2016" | jq
```

```json
{
  "code": 200,
  "message": "ok",
  "data": [
    {
      "id": 653,
      "municipio": "Itajubá",
      "data_referencia": "25/04/2016",
      "codigo_orgao": 26261,
      "nome_orgao": "Universidade Federal de Itajubá",
      "data_publicacao": "01/01/1900",
      "data_resultado_compra": "06/05/2016",
      "objeto_licitacao": "Objeto: Espelho, 4mm de espessura, 2m x 3m, inclusa instalação em parede, por meio de trilho metálico, cola ou botões franceses.",
      "numero_licitacao": "000402016",
      "responsavel_contato": "JOSE ALBERTO FERREIRA FILHO"
    },
    {
      "id": 654,
      "municipio": "Itajubá",
      "data_referencia": "28/04/2016",
      "codigo_orgao": 26261,
      "nome_orgao": "Universidade Federal de Itajubá",
      "data_publicacao": "28/04/2016",
      "data_resultado_compra": "25/05/2016",
      "objeto_licitacao": "Objeto: Pregão Eletrônico -  Registro de preços para eventual compra de materiais Bibliográficos (Livros) publicados por editoras internacionais.",
      "numero_licitacao": "000212016",
      "responsavel_contato": "JOSE ALBERTO FERREIRA FILHO"
    },
    {
      "id": 657,
      "municipio": "Itajubá",
      "data_referencia": "28/04/2016",
      "codigo_orgao": 26261,
      "nome_orgao": "Universidade Federal de Itajubá",
      "data_publicacao": "28/04/2016",
      "data_resultado_compra": "30/05/2016",
      "objeto_licitacao": "Objeto: Pregão Eletrônico -  Aquisição de materiais elétricos, eletrônicos e outros, conforme condições, quantidades e exigências estabelecidas neste Edital e seus anexos.",
      "numero_licitacao": "000222016",
      "responsavel_contato": "JOSE ALBERTO FERREIRA FILHO"
    }
  ]
}
```

### Entrega

1. A data de entrega da prova é definida pelo candidato ao receber a prova. Não serão aceitas provas após o prazo.
2. A prova deve ser armazenada no github do candidato e ele deve enviar o link de acesso para o email `suporte@incluirtecnologia.com.br`.
