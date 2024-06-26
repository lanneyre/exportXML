<?php
if(empty($_POST) || empty($_POST['datab'])){
    header("Location: index.php");
    exit;
}
$datab = $_POST['datab'];

include("includes/top.inc.php");

//message($_POST);
foreach ($_POST as $key => $value) {
    # code...
    if($key != "datab"){
        $querydatacbubandcStatement->execute(["datab" => $datab, "champ" => $key]);
        $nb = $querydatacbubandcStatement->rowCount();
        if($nb == 0){
            // on insert
            $querychampbyidStatement->execute(["id" => $key]);
            $identbloc = ($querychampbyidStatement->fetch(PDO::FETCH_OBJ))->identifiantBloc;
            $queryadddatacBlocStatement->execute([":datab" => $datab, ":champ" => $key, ":value" => $value, ":identifiantBloc" => $identbloc]);
        }
        if ($nb == 1) {
            // on update
            $queryupdatedatacBlocStatement->execute([":datab" => $datab, ":champ" => $key, ":value" => $value]);
        }
    }
}

header("Location: index.php?datab=".$datab);
exit;