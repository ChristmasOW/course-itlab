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

$core = \App\Core\Core::GetInstance();
$core->init();
$core->run();
$core->done();

$record = new \App\Models\News();
$record->title = 'title';
$record->text = 'some text';
$record->date = '2023-08-14 23:00:00';
$record->save();





