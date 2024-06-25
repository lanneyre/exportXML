<?php
if(empty($_GET["id"])){
    header("Location: index.php");
    exit;
}

include("includes/top.inc.php");

$queryaddBlocStatement->execute(["bloc"=> $_GET["id"]]);

header("Location: index.php?datab=".$pdo->lastInsertId());
exit;

