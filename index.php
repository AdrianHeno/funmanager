<?php
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$app = new \Slim\App(['settings' => $config]);
$c = new \Slim\Container($config);
$container = $app->getContainer();

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};

// PDO database library
//Set up a database connection
$container['db'] = function ($c) {
    $pdo = new PDO('mysql:host=' . getenv("MYSQL_HOST") . ';dbname=' . getenv("MYSQL_DATABASE"), getenv("MYSQL_USER"), getenv("MYSQL_PASSWORD"));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$app->get('/funds', function ($request, $response, $args) {//Get all budget rows
    $sth = $this->db->prepare("SELECT * FROM funds");
    $sth->execute();
    $funds = $sth->fetchAll();
    return $this->response->withJson($funds);
 });


 $app->get('/funds/{id}', function ($request, $response, $args) {//Get a budget row
    $fund_id = (int)$args['id'];
    $sth = $this->db->prepare("SELECT * FROM funds WHERE funds.id =" . $fund_id);
    $sth->execute();
    $funds = $sth->fetchAll();
    return $this->response->withJson($funds);
 });
 
 $app->get('/outflow/{id}/{amount}', function ($request, $response, $args) {//subtract funds from a budget row
    $fund_id = (int)$args['id'];
    $purchase_amount = (int)$args['amount'];
    $sth = $this->db->prepare("SELECT * FROM funds WHERE funds.id =" . $fund_id);
    $sth->execute();
    $funds = $sth->fetchAll();
    
    $new_funds = $funds[0][funds] - $purchase_amount;
    
    $sth = $this->db->prepare("UPDATE funds SET funds.funds = " . $new_funds ." WHERE funds.id =" . $fund_id);
    $sth->execute();
 });
 
 $app->get('/inflow/{id}/{amount}', function ($request, $response, $args) {//Add funds to a budget row
    $fund_id = (int)$args['id'];
    $purchase_amount = (int)$args['amount'];
    $sth = $this->db->prepare("SELECT * FROM funds WHERE funds.id =" . $fund_id);
    $sth->execute();
    $funds = $sth->fetchAll();
    
    $new_funds = $funds[0][funds] + $purchase_amount;
    
    $sth = $this->db->prepare("UPDATE funds SET funds.funds = " . $new_funds ." WHERE funds.id =" . $fund_id);
    $sth->execute();
 });
 
 


$app->run();
?>
