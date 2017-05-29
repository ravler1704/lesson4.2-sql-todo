<?php
Error_reporting(E_ALL);
$pdo = new PDO("mysql:host=localhost;dbname=todolist", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<html>
    <head>
        <title>Список дела на сегодня</title>
    </head>
<body>
<h1>Список дела на сегодня</h1>
<form action="" method="post" enctype="multipart/form-data">
	<input type="text" name="description" placeholder="Описание задачи"/>
    <input type="submit" value="Добавить" />
</form>
</body>
</html>

<?php
if (!empty($_POST["description"])) {
    $description = htmlspecialchars($_POST["description"]);
    $array = array("description" => $description, "done" => 0);
    $sql = "INSERT INTO tasks(description, is_done, date_added) VALUES (:description, :done, NOW())";
    $stm = $pdo->prepare($sql);
    $stm -> execute($array);
}

if (isset($_GET["getId"])) {
    $getId = htmlspecialchars($_GET["getId"]);
} else {
    $getId = NULL;
}
if (isset($_GET["doWithId"])) {
    $doWithId = htmlspecialchars($_GET["doWithId"]);
} else {
    $doWithId = NULL;
}

if ($doWithId == 'del') {
    $delTask = "DELETE FROM tasks WHERE id = '$getId'";
    $stm = $pdo->prepare($delTask);
    $stm -> execute();
} else if ($doWithId == 'done') {
    $isDone = "UPDATE tasks SET is_done=1 WHERE id = '$getId'";
    $stm = $pdo->prepare($isDone);
    $stm -> execute();
}

echo '<table border="1">';
echo '<tr>';
echo '<td>Описание задачи</td>' . "\n";
echo '<td>Дата добавления</td>' . "\n";
echo '<td>Статус</td>' . "\n";
echo '<td>Действия</td>' . "\n";
echo '</tr>';

$printTable = $pdo->query("SELECT * FROM tasks");
foreach ($printTable as $key => $value) {
    if ($value['is_done'] == 1) {
        $echoDone = 'выполнено';
    } else {
        $echoDone = 'НЕ выполнено';
    }

    echo '<tr>';
    echo '<td>' . $value['description'] . '</td>' . "\n";
    echo '<td>' . $value['date_added'] . '</td>' . "\n";
    echo '<td>' . $echoDone . '</td>' . "\n";
    echo '<td><a href="index.php?getId=' . $value['id'] . '&doWithId=done">Выполнить </a><a href="index.php?getId=' . $value['id'] . '&doWithId=del">Удалить </a> </td>' . "\n";
    echo '</tr>';
}
echo '</table>';