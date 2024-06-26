<?php
// On récupère tout le contenu de la table blocs
$queryBlocsSQL = 'SELECT * FROM `blocs` WHERE `parent`=:parent';
$queryBlocsStatement = $pdo->prepare($queryBlocsSQL);

// On récupère tout le contenu de la table blocs
$queryBlocsbyidSQL = 'SELECT * FROM `blocs` WHERE `id`=:id';
$queryBlocsbyidStatement = $pdo->prepare($queryBlocsbyidSQL);

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

// On récupère tout le contenu de la table datac en fonction des blocs
$qyerydatacSQLbyBloc = 'SELECT c.id, c.datab, c.value FROM `datac` AS c JOIN `datab` AS b ON (c.datab = b.id) WHERE b.bloc=:bloc AND c.identifiantBloc = 1';
$querydatacbyBlocStatement = $pdo->prepare($qyerydatacSQLbyBloc);

// On récupère tout le contenu de la table datac by id
$qyerydatacbyidSQL = 'SELECT * FROM `datac` WHERE `id`=:id';
$querydatacbyidStatement = $pdo->prepare($qyerydatacbyidSQL);


// On récupère tout le contenu de la table datab by datab et champ
$qyerydatacbubandc = 'SELECT * FROM `datac` WHERE `datab`=:datab AND `champ`=:champ';
$querydatacbubandcStatement = $pdo->prepare($qyerydatacbubandc);


// Ajout d'un bloc de réponse :
$queryaddBloc = "INSERT INTO `datab` (`id`, `bloc`) VALUES (NULL, :bloc)";
$queryaddBlocStatement = $pdo->prepare($queryaddBloc);

// suppression d'un bloc de réponse :
$querydeldatacBloc = "DELETE FROM `datac` WHERE datab=:idc";
$querydeldatacBlocStatement = $pdo->prepare($querydeldatacBloc);
$querydeldatabBloc = "DELETE FROM `datab` WHERE id=:idb";
$querydeldatabBlocStatement = $pdo->prepare($querydeldatabBloc);

//insert datac
$queryadddatacBloc = "INSERT INTO `datac` (`id`, `datab`, `champ`, `value`, `identifiantBloc`) VALUES (NULL, :datab, :champ, :value, :identifiantBloc) ";
$queryadddatacBlocStatement = $pdo->prepare($queryadddatacBloc);
$queryupdatedatacBloc = "UPDATE `datac` SET `value` = :value WHERE `datac`.`datab` = :datab AND `datac`.`champ` = :champ";
$queryupdatedatacBlocStatement = $pdo->prepare($queryupdatedatacBloc);