<?php
global $CoreParams;

use App\Controllers\FrontController;
use App\Core\Database\Database;

require_once('../config/config.php');
spl_autoload_register(function ($className) {
    $newClassName = str_replace('\\', '/', $className);
    if (stripos($newClassName, 'App/') === 0)
        $newClassName = substr($newClassName, 4);
    $path = "../src/{$newClassName}.php";
    if (file_exists($path))
        require_once($path);
});

$front_controller = new FrontController();
$front_controller->run();

/*$datebase = new Database(
    $CoreParams['Database']['Host'],
    $CoreParams['Database']['UserName'],
    $CoreParams['Database']['Password'],
    $CoreParams['Database']['Database']
);

$datebase->connect();*/

/*$query = new QueryBuilder();*/

//query CROSS JOIN =============================

/*$query->select()
    ->from('news')
    ->crossJoin('comments');*/

//query RIGHT JOIN =============================

/*$query->select()
    ->from('news')
    ->rightJoin('comments', 'news.id = comments.news_id');*/

//query LEFT JOIN ==============================

/*$query->select()
    ->from('news')
    ->leftJoin('comments', 'news.id = comments.news_id');*/

//query INNER JOIN =============================

/*$query->select()
    ->from('news')
    ->innerJoin('comments', 'news.id = comments.news_id');*/

//query DELETE =================================

/*$query->delete()
    ->from('news')
    ->where(['id' => 31]);*/

//query UPDATE =================================

/*$update_data = [
    'title' => 'another news',
    'text' => 'another text',
    'date' => '2023-08-06 2:00:00',
];
$query->update($update_data)
    ->from('news')
    ->where(['id' => 30]);*/

//query INSERT =================================

/*$insert_data = [
    'title' => 'some news',
    'text' => 'some text',
    'date' => '2023-08-06 01:30:00'
];
$query->insert($insert_data)
    ->from('news');*/

//query SELECT =================================

/*$query->select(['title', 'text'])
    ->from('news')
    ->where(['id' => 3, 'title' => 'test']);*/

/*$rows = $datebase->execute($query);
var_dump($rows);
echo $query->getSql();*/

$CoreParams ['Database'] = [
    'Host' => '172.22.75.8:3306',
    'UserName' => 'cms-user',
    'Password' => '0314',
    'Database' => 'cms'
];





