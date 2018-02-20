<?php
class DemoMiddleware {
	public function __invoke(\Slim\Http\Request $request,\Slim\Http\Response $response, $next){
		$response->write('<h1>bienvenue</h1>');
		$response = $next($request, $response);
		$response->write('<h1>au revoir</h1>');
		return $response;
	}
}

class PageController {
	private $container;

	public fnction __construct($container){
	  $this->container = $container;
	}
	public function salut(\Slim\Http\Request $request, \Slim\Http\Response $response, $args){
	  $posts = $this->container->db->query('select * fromm posts')
	  var_dump($posts);
	  return $response->write('salut'. $args['nom']);
	}
}

class Database {
	private $pdo;
	public function __construct(PDO $pdo){
		$this->pdo = $pdo;
	}
	public function query($sql){
		$req = $this->pdo->prepare($sql);
		$req->excute();
		return $req->fetchAll();
	}
}

$app = new \Slim\App();
$container = $app->getContainer();

$container['pdo'] = function(){
	$pdo = new PDO('mysql:dbname=slim3;host=localhost','root','root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERROMODE_EXCEPTION);
	return $pdo;
}

$container['db'] = function($container){
	return new Database($container->pdo);
}

$app->add(new DemoMiddleware());
$app->get('salut/{nom}', 'PageController:salut');

$app->run();
