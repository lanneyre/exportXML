<?php
// On récupère tout le contenu de la table blocs
$queryBlocsSQL = 'SELECT * FROM `blocs` WHERE `parent`=:parent';
$queryBlocsStatement = $pdo->prepare($queryBlocsSQL);

// On récupère tout le contenu de la table champs
$querychampsSQL = 'SELECT * FROM `champs` WHERE `blocs`=:blocs';
$querychampsStatement = $pdo->prepare($querychampsSQL);

// On récupère tout le contenu de la table champs par id
$querychampbyidSQL = 'SELECT * FROM `champs` WHERE `id`=:id';
$querychampbyidStatement = $pdo->prepare($querychampbyidSQL);
// On récupère tout le contenu de la table champs par id
$querychampnombyidSQL = 'SELECT `nom` FROM `champs` WHERE `id`=:id';
$querychampnombyidStatement = $pdo->prepare($querychampnombyidSQL);

// On récupère tout le contenu de la table datab
$qyerydatabSQL = 'SELECT * FROM `datab` WHERE `bloc`=:bloc';
$querydatabStatement = $pdo->prepare($qyerydatabSQL);

// On récupère tout le contenu de la table datab
$qyerydatabbyidSQL = 'SELECT * FROM `datab` WHERE `id`=:id';
$querydatabbyidStatement = $pdo->prepare($qyerydatabbyidSQL);

// On récupère tout le contenu de la table datac
$qyerydatacSQL = 'SELECT * FROM `datac` WHERE `datab`=:datab AND identifiantBloc = 1' ;
$querydatacStatement = $pdo->prepare($qyerydatacSQL);


// On récupère tout le contenu de la table datac by id
$qyerydatacbyidSQL = 'SELECT * FROM `datac` WHERE `id`=:id';
$querydatacbyidStatement = $pdo->prepare($qyerydatacbyidSQL);