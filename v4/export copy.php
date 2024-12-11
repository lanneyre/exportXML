<?php
require("includes/top.inc.php");
/* create a dom document with encoding utf8 */
$domtree = new DOMDocument('1.0', 'UTF-8'); 
$domtree->preserveWhiteSpace = false;
$domtree->formatOutput = true;

function generate_XML($domtree, $domnode = null, $bloc_parent = 0){
    global $queryBlocsStatement, $querychampsStatement, $querydatabStatement, $champsBalise, $querydatacSansIDStatement, $querydatabbyParentStatement;

    //je récupère le bloc dont le parent est stocké dans $bloc_parent
    $queryBlocsStatement->execute([
        'parent' => $bloc_parent
    ]);
    $blocs = $queryBlocsStatement->fetchAll(PDO::FETCH_OBJ);
    
    // pour chaque bloc 
    foreach ($blocs as $bloc) {
        $xmlnode = $domtree->createElement($bloc->balise);
        // message($bloc->id);
        // rajout d'attribut sur la balise 
        if(!empty($bloc->attributBalise)){
            $att = explode("=", $bloc->attributBalise);
            $qualifiedName = $att[0];
            $value = substr($att[1], 1, -1);
            $xmlnode->setAttribute($qualifiedName, $value);
        }

        // si domnode est vide on attache à l'arbre directement sinon au noeud contenu dans domnode
        if (empty($domnode)) {
            $domtree->appendChild($xmlnode);
        } else {
            $domnode->appendChild($xmlnode);
        }

        //je récupère les datab en fonction du bloc
        $querydatabStatement->execute(["bloc" => $bloc->id]);
        $databs = $querydatabStatement->fetchAll(PDO::FETCH_OBJ);
        
        //message($querydatabStatement->debugDumpParams());
        foreach ($databs as $datab) {
            $querydatacSansIDStatement->execute(["datab"=>$datab->id]);
            $datacs = $querydatacSansIDStatement->fetchAll(PDO::FETCH_OBJ);
            foreach ($datacs as $datac) {
                $xmlnodechp = $domtree->createElement($champsBalise[$datac->champ], $datac->value);
                $xmlnode->appendChild($xmlnodechp);
            }
            // $domtree = generate_XML($domtree, $xmlnode, $bloc->id);
        }
        if($bloc->id == 1){
            $domtree = generate_XML($domtree, $xmlnode, $bloc->id);
        }
        //
   
        
    }
    return $domtree;
}


$domtree = generate_XML($domtree);

/* get the xml printed */

Header('Content-type: text/xml');
echo $domtree->saveXML();