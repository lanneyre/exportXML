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

session_start();

require 'vendor/autoload.php';

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
                    if ($champ->bloc ==  7) {
                        $querydatacStatement->execute(["datab" => $champ->parent]);
                        $p = $querydatacStatement->fetch(PDO::FETCH_OBJ);
                        $html .= <<<HTML
                    <a href="index.php?datab=$id">$p->value - $nom</a> <a href="delbloc.php?id=$id" class="moins"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#00008B" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM184 232H328c13.3 0 24 10.7 24 24s-10.7 24-24 24H184c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/></svg></a>  <br>
HTML;
                    } else {
                        $html .= <<<HTML
                    <a href="index.php?datab=$id">$nom</a> <a href="delbloc.php?id=$id" class="moins"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#00008B" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM184 232H328c13.3 0 24 10.7 24 24s-10.7 24-24 24H184c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/></svg></a>  <br>
HTML;
                    }
                     
                }
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
        <legend>$bloc->nom <a class="plus" href="addbloc.php?id=$bloc->id"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#00008B" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/></svg></a></legend>
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