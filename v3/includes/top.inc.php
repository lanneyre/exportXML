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

// On récupère tout le contenu de la table blocs
$queryBlocsSQL = 'SELECT * FROM `blocs` WHERE `parent`=:parent';
$queryBlocsStatement = $pdo->prepare($queryBlocsSQL);

// On récupère tout le contenu de la table champs
$querychampsSQL = 'SELECT * FROM `champs` WHERE `blocs`=:blocs';
$querychampsStatement = $pdo->prepare($querychampsSQL);


function create_champs($bloc){
    global $querychampsStatement;
    $querychampsStatement->execute([
        'blocs' => $bloc
    ]);
    $champs = $querychampsStatement->fetchAll(PDO::FETCH_OBJ);
    $html = "";
    foreach ($champs as $champ) {
        # code...
        $html.= <<<HTML
        <div class="champs">
            <label for="champ-$champ->id">$champ->nom</label><input type="string" name="$champ->balise" id="champ-$champ->id">
        </div>
HTML;
    }

    return $html;
}

function create_bloc($parent = 0){
    global $queryBlocsStatement;
    $queryBlocsStatement->execute([
        'parent' => $parent
    ]);
    $blocs = $queryBlocsStatement->fetchAll(PDO::FETCH_OBJ);
    $html = "";
    foreach ($blocs as $bloc) {
        # code...
        $html .= <<<HTML
    <fieldset>
        <legend>$bloc->nom</legend>
        [[champs]]
        <!-- <hr> -->
        [[blocsEnfants]]
    </fieldset>
HTML;
        $html = str_replace("[[champs]]", create_champs($bloc->id), $html);
        $html = str_replace("[[blocsEnfants]]", create_bloc($bloc->id),$html);
    }

    
    return $html;
}
//requetes préparées
