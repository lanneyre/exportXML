<?php
require("includes/top.inc.php");
if(empty($_FILES['excel']['name']) || empty($_FILES['excel']['size'])){
    header("Location: index.php?nofile");
    exit;
}

// $sqld = "DROP TABLE `temp`";
// $pdo->exec($sqld);


$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$spreadsheet = $reader->load($_FILES['excel']['tmp_name']);

$data = $spreadsheet->getActiveSheet()->toArray();

$sqlC = "CREATE TABLE IF NOT EXISTS `temp`  (`id` INT NOT NULL AUTO_INCREMENT";
$sql = "INSERT INTO `temp` (`id` [[champs]]) VALUES ";
$s = "";
$v = [];
foreach ($data[1] as $cell) {
    if($cell != -1){
        $sqlC .= ", `" . $cell . "` TEXT NULL";
        $s .= ", `" . $cell . "`";
    }
} 
$sql = str_replace("[[champs]]", $s, $sql);
$sqlC .= ", PRIMARY KEY (`id`)) ENGINE = InnoDB;"; 
$pdo->exec($sqlC);

// on insert les données 

for ($i=2; $i < sizeof($data); $i++) { 
    # code...
    $values = "(NULL, ";
    $vt=[];
    $date = [9, 10, 13];
    foreach ($data[$i] as $key => $cell) {
        if ($key != 23) {
            if(!in_array($key, $date)){
                $vt[] = '"' . $cell . '"';
            } else {
                $d = date_create($cell);
                $vt[] = '"' . date_format($d, "Y-m-d") . '"';
            }
        }
    }
    $values .= implode(", ", $vt);
    $values .= ") \n";
    $v[] = $values;
}
$sql .= implode(", ", $v);

$pdo->exec($sql);

//on remplie la bdd à partir de temp
/****************************************************************************************************
 **************************************************************************************************** 
 **************************************************************************************************** 
 *****************************************************************************************************/

 // problème de remplissage des champs seul le dernier champs est rempli
/***
    $selectChampsState = $pdo->query("SELECT * FROM `champs`");
    $selectChamps = $selectChampsState->fetchAll(PDO::FETCH_OBJ);
    $champsBlocs = [];
    foreach ($selectChamps as $key => $champ) {
        $champsBlocs[$champ->id] = ["bloc"=> $champ->blocs, "ibloc" => $champ->identifiantBloc];
    }

    function createBlocinBdd($c){
        global $queryaddwithParentBlocStatement, $queryadddatacBlocStatement, $pdo, $champsBlocs, $queryBlocsbyidStatement;
        if(empty($_SESSION["datab"])){
            $_SESSION["datab"] = 1;
        }
        $sql = "SELECT DISTINCT `" . $c . "` FROM `temp`";
        $state = $pdo->query($sql);
        $tocreate = $state->fetchAll(PDO::FETCH_OBJ);
        foreach ($tocreate as $valueToCreate) {
            if($champsBlocs[$c]["ibloc"] == 1){
                $queryaddwithParentBlocStatement->execute(["bloc" => $champsBlocs[$c]["bloc"], "parent" => $_SESSION["datab"]]);
                $_SESSION["datab"] = $pdo->lastInsertId();
            }
            if($c == 15){
                message(["datab" => $_SESSION["datab"], "champ" => $c, "value" => $valueToCreate->$c, "identifiantBloc" => $champsBlocs[$c]["ibloc"]]);
            }
            $queryadddatacBlocStatement->execute(["datab" => $_SESSION["datab"], "champ" => $c, "value" => $valueToCreate->$c, "identifiantBloc" => $champsBlocs[$c]["ibloc"]]);
        }
    }
    unset($_SESSION["datab"]);
    $sql = "SHOW COLUMNS FROM `temp`";
    $state = $pdo->query($sql);
    $chps = $state->fetchAll(PDO::FETCH_OBJ);
    foreach ($chps as $champ) {
        if($champ->Field != "id"){
            createBlocinBdd($champ->Field); 
        }
    }
*/

//je récupère tous les champs possibles
$selectChampsState = $pdo->query("SELECT * FROM `champs`");
$selectChamps = $selectChampsState->fetchAll(PDO::FETCH_OBJ);
$champsBlocs = [];
//j'associe un id de champ à un bloc afin de gagner du temps : je saurait immédiatement dans quel blocs mettre un champs
foreach ($selectChamps as $key => $champ) {
    $champsBlocs[$champ->id] = $champ->blocs;
}

// je récupère la première ligne de temp
$sql = "SELECT * FROM `temp`";
$state = $pdo->query($sql);
$FirstLigneTemp = $state->fetch(PDO::FETCH_ASSOC);

//on créé les premiers blocs
// Je récupère la liste des champs qui servent d'identifiant aux blocs
$queryAllChampsIBlocStatement->execute();
$champsIBloc = $queryAllChampsIBlocStatement->fetchAll(PDO::FETCH_ASSOC);
//j'initialise le bloc parent à 1
$parent = 1;
//pour chaque champs prioritaire 
foreach ($champsIBloc as $champIBloc) {
    // si il existe dans la table temp
    if(!empty($FirstLigneTemp[$champIBloc["id"]])){
        //creation datab 
        // Quand je ferai avec tous les lignes il faudra regarder si le parent existe auquel cas il faudra récupérer son id pour l'inserer dans datab et datac
        // Sinon on le créé comme là 
        //NB Il faudra créer un bloc n° 31 en même temps que le bloc 10
        $queryaddwithParentBlocStatement->execute(["bloc"=> $champIBloc["blocs"], "parent" => $parent]);
        // ne pas oublier de récupérer l'ID
        $parent = $pdo->lastInsertId();

        //creation datac identifiant
        $queryadddatacBlocStatement->execute(["datab" => $parent, "champ" => $champIBloc["id"], "value" => $FirstLigneTemp[$champIBloc["id"]], "identifiantBloc"=>1]);

        //creation datac non identifiant
        // Je récupère tous les champs non prioritaire 
        $queryAllChampsNOTIBlocStatement->execute(["blocs" => $champIBloc["blocs"]]);
        $allOtherChamps = $queryAllChampsNOTIBlocStatement->fetchAll(PDO::FETCH_OBJ);
        // et pour chacun d'entres eu
        foreach ($allOtherChamps as $key => $value) {
            # s'il existe dans la table temp
            if (!empty($FirstLigneTemp[$value->id])) {
                // je les insert avec les bonnes valeurs afin qu'ils soient lié au bon datab
                $queryadddatacBlocStatement->execute(["datab" => $parent, "champ" => $value->id, "value" => $FirstLigneTemp[$value->id], "identifiantBloc" => 0]);
            }
        }
    }
}





$sql = "SELECT * FROM `temp` LIMIT 1, 100000";
$state = $pdo->query($sql);
$lignesTemp = $state->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($lignesTemp) ; $i++) {
    # code...
    krsort($lignesTemp[$i]);
}

// foreach ($lignesTemp as $data) {
//     # code...
//     //pour chaque ligne 
//     // J'ai donc une ligne contenant des données à insérer !
//     $switch = [];
//     foreach ($data as $key => $value) {  
//         if($key != "id" && !in_array($key, $switch) ){
//             //48
//             $bloc = $champsBlocs[$key];
//             $querychampsStatement->execute(["bloc"=>$bloc]);
//             $chpsDuBlocCourant = $querychampsStatement->fetchAll(PDO::FETCH_ASSOC);
            
//         }
//     }
// }

// message($lignesTemp);

/****************************************************************************************************
 **************************************************************************************************** 
 **************************************************************************************************** 
 *****************************************************************************************************/

// on supprime la table temporaire 
// $sqld = "DROP TABLE `temp`";
// $pdo->exec($sqld);

// header("Location: index.php?ok");
// exit;