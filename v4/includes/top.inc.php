<?php
//on récupère les données de configurations
$env = explode("\n", file_get_contents('.env'));
foreach ($env as $config) {
    $c = explode("=", $config);
    if(sizeof($c)==2){
        define(trim($c[0]), trim($c[1]));
    }
}
// connexion à la bdd
$pdo = new PDO(
    'mysql:host='. BDD_HOST.';dbname='. BDD_BASE.';charset=utf8',
    BDD_USER,
    BDD_PWD
);

function message($msg){
    echo "<pre>";
    var_dump($msg);
    echo "</pre>";
}

include ("includes/request.inc.php");

function blocsaside($b){
    global $queryBlocsbyidStatement, $querydatabStatement, $querydatacStatement;
    $queryBlocsbyidStatement->execute([
        'id' => $b
    ]);
    $blocs = $queryBlocsbyidStatement->fetchAll(PDO::FETCH_OBJ);
    $html = "";
    if (sizeof($blocs) > 0) {
        
        foreach ($blocs as $bloc) {
            $querydatabStatement->execute(['bloc' => $bloc->id]);
            $champs = $querydatabStatement->fetchAll(PDO::FETCH_OBJ);
            if(sizeof($champs) > 0) {
                foreach ($champs as $champ) {
                    $querydatacStatement->execute(["datab"=> $champ->id]);
                    $c = $querydatacStatement->fetch(PDO::FETCH_OBJ);
                    $nom = empty($c->value) ? "En cours de création" : $c->value;
                    $id = empty($c->datab) ? $champ->id : $c->datab;
                    $html .= <<<HTML
                    <a href="index.php?datab=$id">$nom</a> <br>
HTML;                  
                }
            }
            else {
                $html .= '<a href="index.php?datab='.$bloc->id.'">En cours de création</a>  <br>';
            }            
        }
    }
    
    return $html;
}

function aside($parent = 0){
    global $queryBlocsStatement, $querydatacStatement;

    $queryBlocsStatement->execute([
        'parent' => $parent
    ]);
    $blocs = $queryBlocsStatement->fetchAll(PDO::FETCH_OBJ);
    $html = "";
    foreach ($blocs as $bloc) {
        # code...
        $html .= <<<HTML
    <fieldset>
        <legend>$bloc->nom <a class="plus" href="addbloc.php?id=$bloc->id">+</a></legend>
        [[champs]]
        <!-- <hr> -->
        [[blocsEnfants]]
    </fieldset>
HTML;
        $html = str_replace("[[champs]]", blocsaside($bloc->id), $html);
        $html = str_replace("[[blocsEnfants]]", aside($bloc->id), $html);
    }
    return $html;
}