<?php
date_default_timezone_set('Europe/Kiev');
$pdo = new PDO('mysql:host=172.22.75.8:3306;dbname=cms;user=cms-user;password=0314');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $text = $_POST['text'];
    $title = $_POST['title'];
    $date = date("Y-m-d H:i:s");
    $pdo->query("INSERT INTO news (title, text, date) VALUES ('{$title}', '{$text}', '{$date}')");
}

$per_page = 5;
$count_result = $pdo->query("SELECT COUNT(*) as count FROM news");
$count_row = $count_result->fetch();
$total_pages = $count_row['count'];
$count_page = ceil($total_pages / $per_page);
$page = $_GET['page'];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }

        form {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f2f2f2;
        }

        form, .container {
            width: 500px;
            text-align: center;
            margin: 0 auto;
        }

        form div {
            margin: 10px 0;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        form textarea {
            resize: vertical;
        }

        form button {
            background-color: #4CAF50;
            color: #fff;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        th:first-child,
        td:first-child {
            width: 50px;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin-right: 5px;
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
        }

        .pagination a.active,
        .pagination a:first-child,
        .pagination a:last-child {
            color: #fff;
            background-color: #4CAF50;
        }

        .pagination a.disabled {
            pointer-events: none;
            cursor: default;
            color: #000;
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<form action="" method="POST">
    <div>Title:</div>
    <div>
        <input type="text" name="title">
    </div>
    <div>Text:</div>
    <textarea name="text"></textarea>
    <div>
        <button type="submit">Add</button>
    </div>
</form>
<div class="container">
    <h1>News list</h1>
    <table>
        <tr>
            <th>id</th>
            <th>title</th>
            <th>date</th>
        </tr>
        <?php
        $start = ($page - 1) * $per_page;
        $sth = $pdo->query("SELECT * FROM news LIMIT $start, $per_page");
        while ($row = $sth->fetch()) : ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['title'] ?></td>
                <td><?= $row['date'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="pagination">
        <?php if ($page > 1) : ?>
            <a href="?page=<?= ($page - 1) ?>"><</a>
        <?php else : ?>
            <a class="disabled" href="?page=<?= ($page - 1) ?>"><</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $count_page; $i++) : ?>
            <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $count_page) : ?>
            <a href="?page=<?= ($page + 1) ?>">></a>
        <?php else : ?>
            <a class="disabled" href="?page=<?= ($page + 1) ?>">></a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
