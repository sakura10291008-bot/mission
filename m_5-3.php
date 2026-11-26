<?php
$dsn = 'mysql:dbname=ユーザーネーム;host=localhost';
$user = 'ユーザーネーム';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
));

$editName = "";
$editComment = "";
$editNo = "";
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $editTarget = $_POST["editTarget"];
    if (!empty($name) && !empty($comment)) {
        
        if (!empty($editTarget)) {
            $sql = "UPDATE tb_bbs
        SET name = :name, comment = :comment
        WHERE id = :id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(":name", $name, PDO::PARAM_STR);
$stmt->bindParam(":comment", $comment, PDO::PARAM_STR);
$stmt->bindParam(":id", $editTarget, PDO::PARAM_INT);
$stmt->execute();
        } else {
            // ここに今までのINSERT処理を移す
            
        $sql = "INSERT INTO tb_bbs (name, comment, date)
                VALUES (:name, :comment, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->bindParam(":comment", $comment, PDO::PARAM_STR);
        $stmt->execute();
        }
    }
    }
if (isset($_POST["delete"])) {
    $deleteNo = $_POST["deleteNo"];

    if (!empty($deleteNo)) {
        // 削除処理
            $sql = "DELETE FROM tb_bbs WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $deleteNo, PDO::PARAM_INT);
        $stmt->execute();
    }
}
if (isset($_POST["edit"])) {
    $editNo = $_POST["editNo"];

    if (!empty($editNo)) {
        $sql = "SELECT * FROM tb_bbs WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $editNo, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();

        if ($row) {
            $editName = $row["name"];
            $editComment = $row["comment"];
        }
    }
}
$sql = "SELECT * FROM tb_bbs ORDER BY id ASC";
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();

foreach ($results as $row) {
    echo $row["id"] . " ";
    echo $row["name"] . " ";
    echo $row["comment"] . " ";
    echo $row["date"] . "<br>";
}
?>
<form action="" method="post">
    <input type="text" name="name" placeholder="名前"
     value="<?php echo htmlspecialchars($editName, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="text" name="comment" placeholder="コメント"
     value="<?php echo htmlspecialchars($editComment, ENT_QUOTES, 'UTF-8'); ?>">
     <input type="hidden" name="editTarget"
value="<?php echo htmlspecialchars($editNo, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="submit" name="submit" value="送信">
    <input type="number" name="deleteNo"placeholder="削除対象番号" >
     <input type="submit" name="delete" value="削除">
     
     <input type="number" name="editNo" placeholder="編集対象番号">
<input type="submit" name="edit" value="編集">
</form>