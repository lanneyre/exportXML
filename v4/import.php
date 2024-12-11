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



// je récupère la première ligne de temp
$sql = "SELECT * FROM `temp`";
$state = $pdo->query($sql);
$lignesTemp = $state->fetchAll(PDO::FETCH_ASSOC);

//on créé les premiers blocs
// Je récupère la liste des champs qui servent d'identifiant aux blocs
$queryAllChampsIBlocStatement->execute();
$champsIBloc = $queryAllChampsIBlocStatement->fetchAll(PDO::FETCH_ASSOC);
//j'initialise le bloc parent à 1
$parent = 1;

foreach ($lignesTemp as $ligneTemp) {
    //pour chaque champs prioritaire 
    foreach ($champsIBloc as $champIBloc) {
        // si il existe dans la table temp
        if (!empty($ligneTemp[$champIBloc["id"]])) {
            //creation datab 
            // Quand je ferai avec tous les lignes il faudra regarder si le parent existe auquel cas il faudra récupérer son id pour l'inserer dans datab et datac

            $querydatacbyValueStatement->execute(["val" => $ligneTemp[$champIBloc["id"]]]);
            $datab = $querydatacbyValueStatement->fetch(PDO::FETCH_OBJ);
            
            if($datab !== false){
                $parent = $datab->datab;
            } else {
                // Sinon on le créé comme là 
                
                if ($champIBloc["id"] == 31) {
                    // ne pas oublier de récupérer l'ID
                    $parent = $parent31;
                } else {
                    //NB Il faudra créer un bloc n° 31 en même temps que le bloc 10
                    $queryaddwithParentBlocStatement->execute(["bloc" => $champIBloc["blocs"], "parent" => $parent]);
                    // ne pas oublier de récupérer l'ID
                    $parent = $pdo->lastInsertId();
                }

                if($champIBloc["id"] == 10){
                    $queryaddwithParentBlocStatement->execute(["bloc" => $champsBlocs[31], "parent" => $parent]);
                    // ne pas oublier de récupérer l'ID
                    $parent31 = $pdo->lastInsertId();
                }
                
            }
            
            //creation datac identifiant
            // message(["datab" => $parent, "champ" => $champIBloc["id"], "value" => $ligneTemp[$champIBloc["id"]], "identifiantBloc" => 1]);
            $queryadddatacBlocStatement->execute(["datab" => $parent, "champ" => $champIBloc["id"], "value" => $ligneTemp[$champIBloc["id"]], "identifiantBloc" => 1]);

            //creation datac non identifiant
            // Je récupère tous les champs non prioritaire 
            $queryAllChampsNOTIBlocStatement->execute(["blocs" => $champIBloc["blocs"]]);
            $allOtherChamps = $queryAllChampsNOTIBlocStatement->fetchAll(PDO::FETCH_OBJ);
            // et pour chacun d'entres eu
            foreach ($allOtherChamps as $key => $value) {
                # s'il existe dans la table temp
                if (!empty($ligneTemp[$value->id])) {
                    // je les insert avec les bonnes valeurs afin qu'ils soient lié au bon datab
                    $queryadddatacBlocStatement->execute(["datab" => $parent, "champ" => $value->id, "value" => $ligneTemp[$value->id], "identifiantBloc" => 0]);
                }
            }
        }
    }
}

/****************************************************************************************************
 **************************************************************************************************** 
 **************************************************************************************************** 
 *****************************************************************************************************/

//on supprime la table temporaire 
$sqld = "DROP TABLE `temp`";
$pdo->exec($sqld);

header("Location: index.php?ok");
exit;