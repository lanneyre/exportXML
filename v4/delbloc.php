<?php
if(empty($_GET["id"])){
    header("Location: index.php");
    exit;
}

include("includes/top.inc.php");

$querydeldatacBlocStatement->execute(["idc"=> $_GET["id"]]);
$querydeldatabBlocStatement->execute(["idb" => $_GET["id"]]);

header("Location: index.php");
exit;

