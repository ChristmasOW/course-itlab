<?php
global $CoreParams;
require_once('../config/config.php');
spl_autoload_register(function ($className) {
    $path = "../src/{$className}.php";
    if (file_exists($path))
        require_once($path);
});

$datebase = new Database(
    $CoreParams['Database']['Host'],
    $CoreParams['Database']['UserName'],
    $CoreParams['Database']['Password'],
    $CoreParams['Database']['Database']
);

$datebase->connect();

$query = new QueryBuilder();
$query->select(['title', 'text'])
    ->from('news')
    ->where(['id' => 3, 'title' => 'test']);
$rows = $datebase->execute($query);
var_dump($rows);
echo $query->getSql();

$CoreParams ['Database'] = [
    'Host' => '172.22.75.8:3306',
    'UserName' => 'cms-user',
    'Password' => '0314',
    'Database' => 'cms'
];

/*$front_controller = new FrontController();
$front_controller->run();*/



