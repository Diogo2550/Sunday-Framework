# Sunday Framework
O Sunday Framework (SF) é um projeto criado por Diogo Alves, estudante do Instituto Federal do Rio de Janeiro (IFRJ) - *Campus*  Arraial do Cabo, com um intuito simples e prático: Criar uma API de forma simples em seu tempo livre, ou melhor dizendo, aos seus dias de domingo.

O objetivo do projeto é possibilitar a criação de uma API PHP sem necessitar de um amplo conhecimento sobre programação ou sobre desenvolvimento backend, tornando-se assim, perfeita para desenvolvedores frontend que precisam testar seus aplicativos e/ou estudantes que sentem curiosidade e gostaria de ver um aplicativo backend funcionando.

### Objetivo
O Sunday Framework tentará abstrair ao máximo todo o processo "complicado" por trás de uma aplicação backend para tanto facilitar, quanto, principalmente, agilizar o processo de criação de um app.

Dentre as funcionalidades previstas para serem criadas estão:
- Criação automática de banco de dados e tabelas (MySQL).
- Roteamento automático através de controllers.

<hr>

### Links
- [Requerimentos](#Requerimentos)
- [Instalação](#Instalação)
- [Como usar](#Como usar)

<hr>

### Requerimentos
- [Xampp](https://www.apachefriends.org/pt_br/index.html "Xampp")
- PHP 8.0.0 ou maior
- MySQL
- De preferência ter o [composer](https://getcomposer.org/download/ "composer") instalado
- Vontade de criar uma API

Para utilizar o Sunday Framework é necessário o uso de um servidor local e, até o presente momento, o banco de dados MySQL e a linguagem php instalada em sua versão 8 ou superior. Por isso, recomenda-se que se instale o xampp da apache pois o mesmo já vem com todos esses recursos em apenas um pacote.

### Instalação
Caso você possua o composer instalado em sua máquina, basta entrar no diretório de projetos do seu servidor local - no caso do apache é a pasta *htdocs* dentro da pasta raiz do xampp - e rodar em algum console o comando:

`composer create-project diogoadc/sunday-framework`

e tudo estará funcionando automaticamente.

Todavia, caso você não tenha instalado o composer, é possível instalar o Sunday Framework simplesmente fazendo um clone deste repositório e descompactando a pasta no diretório de projetos do seu servidor local.

### Como usar
#### Introdução
Até o momento, o SF possui, de padrão, uma hierarquia de pastas que não deve em hipótese alguma ser alterada. Cada pasta, com sua respectiva função será listada a seguir:

 ![Sunday Framework Estrutura de Pastas](https://i.imgur.com/h0YLNQa.png "Sunday Framework Estrutura de Pastas")

#### Estrutura de Pastas:
**_Core: **Possui o coração do Framework.
**Controllers:** Pasta responsável por armazenar todos os controladores das rotas.
**Models:** Pasta responsável por armazenas todos os modelos de dados.
**Public: **Pasta que até o presente momento não possui uma utilidade.
**vender:** Pasta pertencente ao composer.

#### Arquivos: 
**.gitignore:** Arquivo pertencente ao Github
**.htaccess: **Arquivo responsável pelo roteamento. Não o altere!
**composer.json:** Arquivo pertencente ao composer
**index.php:** O arquivo inicial de sua aplicação. Será abordado futuramente.
**LICENSE:** Arquivo de licença.
**README.md: **Arquivo do github.
**RestAPI.php:** Arquivo responsável pela sua API. Não o altere em hipótese alguma!
**settings.json:** Arquivo de configuração. Atualmente serve somente para configurar o banco de dados.

Os únicos arquivos/pastas que devem ser alterados são os arquivos** index.php** e **settings.json** e as pastas **Models** e **Controllers**. A seguir, cada um dos itens citados será explicado.

#### index.php
Atualmente o index.php tem uma função bem específica. A única linha que deve ser alterada é a linha que contém:

`$con->createConnetion()->autoCreateDatabase()->autoCreateTables();`

O método 'autoCreateDatabase()' irá criar automaticamente a database informada dentro do settings.json.
O método 'autoCreateTables()' irá criar automaticamente as tabelas no banco de dados de acordo com os models existentes na pasta Models.
Caso não deseja que isso aconteça automaticamente, basta apagar a chamada destas 2 funções, ou seja, a linha deverá ficar:

`$con->createConnection()`

#### settings.json
O settings.json é o arquivo responsável pela conexão com o banco de dados. Nele você terá de fornecer o host, username, password e o nome do banco de dados.

#### Models
A pasta models é responsável por guardar todos os models de sua aplicação mas... o que é um model?
Um model é basicamente um retrato de uma tabela, ou seja, um model é uma classe que contém todos os atributos que a tabela terá no banco de dados.
A estrutura básica de um model é:

    // A classe terá de ter a nomenclatura Nome + Model, ondem o nome pode ser entendido como o nome da tabela.
	// Todo model deverá herdar da classe BaseModel
    class ExampleModel extends BaseModel { 
    
		// Um protected $primaryKey é necessário para dizer qual é a chave primária da tabela. O seu valor deverá ser a propriedade que será a chave primária entre aspas.
		protected $primaryKey = 'id';
		
		// Demas propriedades publicas de acordo com sua necessidade
		public int $id;
		public DateTime $dt_nasc;

		public function __construct() { }

	}
	
**Atenção:** Utilize sempre propriedades pública em seus Models. Propriedades private não serão atribuídas ao banco de dados!
**Atenção 2:** Crie somente 1 model por arquivo. O arquivo deverá ter o mesmo nome que o model!

**OBS:** O SF vem com um model e um controller padrão para que se tenha um compreendimento maior do funcionamento!

#### Características de um model
Um model possui a propriedade "protected $primaryKey" que deverá receber como valor o nome do atributo que será a chave primaria da tabela.
Ex:

	protected $primaryKey = 'cpf';

essa linha informará à API que o cpf será a chave primária.

Todo model possui um método chamado "patchValues(array)" que recebe um array associativo qualquer e preenche o model com os valores que tiver no array. Caso queira, também poderá preenche-lo manualmete.

#### Controllers
A pasta controller é a responsável por tudo o que acontecerá em sua API. Ela criará suas rotas automaticamente e responderá requisições de acordo com o que for programada para fazer. Uma rota normal em sua API será descrita como:

	// Todos os parâmetros são opcionais!
	URL_Base/controller/metodo/parametro1/parametro2/parametro3/...


Assim que for criado um novo arquivo de controller, automaticamente aquele rota será acessível através da url e, cada função pública é um possível end-point (url) de rota.
**OBS:** Todos os controllers possuem 4 rotas "padrões", um get, post, put e delete; ou seja, caso seja criada uma função "get()" no controller "contatos", somente de enviar uma solitação do tipo get para "url_base/contato" irá acessar o método get().

Assim como os models, os controllers também possuem uma estrutura padrão que deve ser seguida que será explicada a seguir:

	// Todo controller deverá ter a nomeclatura "nome + Controller" (Em Pascal Case)
	// Todo controller deverá herdar da classe BaseController.
	class ExampleController extends BaseController {

		// Função opcional
		public function get($id = null) { }

		// Função opcional
		public function post() { }

		// Função opcional
		public function delete($id) { }

		// Função opcional
		public function put() { }

	}

O fluxo de um controller é bem simples de se compreender.
> Para cada função pública criada, você terá um novo end-point

Ou seja, caso você crie uma:
`public function pegarTodosOsElementos()`
você poderá acessar essa rota a qualquer momento.

#### Características de um controller
O SF funciona em torno dos controllers e dos models.
Dentro do controller é possível acessar o método

	$this->query->insert(model)
	$this->query->update(model);
	$this->query->select(model);
	$this->query->delete(model);
	$this->query->where(model, atributo);
	...

O query é o cara responsável por contruir as queries sem que seja necessário escreve-las.
Após você "montar" sua query como um quebra cabeça, você poderá utilizar a classe 'repository' para acessar o banco de dados. Os métodos que você irá são:

	$this->repository->select($this->query);
	$this->repository->selectAll($this->query);
	$this->repository->insert($this->query);
	$this->repository->update($this->query);
	$this->repository->delete($this->query);

Dado ao que foi dito até o momento, um fluxo básico de uma função no controller seria.
- Montar a query com um model.
- Realizar a query no banco de dados.

Ex:

	// Cria um model
	$model = new UserModel;
	// Preenche um model com seus dados
	$model->patchValues($this->data);
	
	// Monta uma query de select que pegará os valoes onde o id é igual ao id do model dado
	$this->query->select($model)->where($model, 'id');
	// Executa a query e retorna o valor pra fora da API
	return $this->respository->select($this->query);

**Obs:** É possível escrever tanto

	$this->query->select($model);
	$this->query->where($model, 'id');

quanto

	$this->query->select($model)->where($model, 'id');

Os dois modos são equivalentes.

<hr>
Todo controller possui um atributo com os dados enviados no corpo de uma requisição HTTP em formato json. Para acessa-la basta fazer:

	$this->data;

# Informações importantes
- O Sunday Framework foi criado para trabalhar somente com requisições que usam json. 
- Caso queira testar sua API sem ter criado um aplicativo para acessa-la, recomendo que utilize algum aplicativo com esse objetivo como o Postman.
- O Framework ainda está em fase de desenvolvimento. Sua versão atual é a 0.2.0.
- Bugs podem ser encontrados, caso encontre, por favor reporte aqui no Github para que sejam consertados.
- A documentação não está completa. Infelizmente, ainda muitas coisas irão mudar e devido a isso, não vale a pena escrever a forma com que se faz tudo com ela.
- Muito obrigado pela compreensão, caso tenha alguma dúvida pode entrar em contato comigo por email ou aqui pelo github.
	Email: Diogo2560@gmail.com