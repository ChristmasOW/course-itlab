<?php
global $CoreParams;

use App\Core\Database\Database;
use App\Core\FrontController;
use App\Core\StaticCore;
use App\Models\News;

require_once('../config/config.php');
spl_autoload_register(function ($className) {
    $newClassName = str_replace('\\', '/', $className);
    if (stripos($newClassName, 'App/') === 0)
        $newClassName = substr($newClassName, 4);
    $path = "../src/{$newClassName}.php";
    if (file_exists($path))
        require_once($path);
});

StaticCore::Init();
StaticCore::Run();
StaticCore::Done();

$database = new Database(
    $CoreParams['Database']['Host'],
    $CoreParams['Database']['UserName'],
    $CoreParams['Database']['Password'],
    $CoreParams['Database']['Database']
);
$database->connect();

$record = new News();
$record->title = 'title';
$record->text = 'some text';
$record->date = '2023-08-11 19:40:00';
$record->save();





