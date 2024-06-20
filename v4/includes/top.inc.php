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

include ("includes/request.inc.php");

function blocsaside($bloc){
    global $querydatacStatement, $querydatabStatement, $querychampnombyidStatement;
    $querydatabStatement->execute([
        'bloc' => $bloc
    ]);
    $blocs = $querydatabStatement->fetchAll(PDO::FETCH_OBJ);
    // var_dump($blocs);
    $html = "";
    foreach ($blocs as $bloc) {
        $querydatacStatement->execute(['datab' => $bloc->id]);
        $champs = $querydatacStatement->fetchAll(PDO::FETCH_OBJ);
        //var_dump($champs);

        foreach ($champs as $champ) {
            $html .= <<<HTML
            <a href="?datab=$bloc->id">$champ->value</a> <br>
HTML;
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
        blocsaside($bloc->id);
        $html = str_replace("[[champs]]", blocsaside($bloc->id), $html);
        $html = str_replace("[[blocsEnfants]]", aside($bloc->id), $html);
    }
    return $html;
}