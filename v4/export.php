<?php
require("includes/top.inc.php");
/* create a dom document with encoding utf8 */
$domtree = new DOMDocument('1.0', 'UTF-8'); 
$domtree->preserveWhiteSpace = false;
$domtree->formatOutput = true;

function generate_XML($domnode = null, $parent = 0){
    global $domtree, $champsBalise, $querydatacSansIDStatement, $querydatabStatement, $queryBlocsStatement;

    $queryBlocsStatement->execute(["parent" => $parent]);
    $blocsByParent = $queryBlocsStatement->fetchAll(PDO::FETCH_OBJ);

    // pour chaque bloc 
    foreach ($blocsByParent as $bloc) {
        $xmlnode = $domtree->createElement($bloc->balise);


        if (!empty($bloc->attributBalise)) {
            $att = explode("=", $bloc->attributBalise);
            $qualifiedName = $att[0];
            $value = substr($att[1], 1, -1);
            $xmlnode->setAttribute($qualifiedName, $value);
        }

        if (empty($domnode)) {
            $domtree->appendChild($xmlnode);
        } else {
            $domnode->appendChild($xmlnode);
        }

        if(!empty($bloc->sous_balise)){
            $xmlsousnode = $domtree->createElement($bloc->sous_balise);
            $xmlnode->appendChild($xmlsousnode);
            $nodeCourant = $xmlsousnode;
        } else {
            $nodeCourant = $xmlnode;
        }

        //je récupère les datab en fonction du bloc
        $querydatabStatement->execute(["bloc" => $bloc->id]);
        $databs = $querydatabStatement->fetchAll(PDO::FETCH_OBJ);

        if(in_array($bloc->id, [1,2,3])){
            foreach ($databs as $datab) {
                # code...
                // message($datab);
                // if(!empty($datab->defaultvalue)){
                //     $xmlnodechp = $domtree->createElement($datab->nom, $datab->defaultvalue);
                //     $nodeCourant->appendChild($xmlnodechp);
                // } else {
                    $querydatacSansIDStatement->execute(["datab" => $datab->id]);
                    $datacs = $querydatacSansIDStatement->fetchAll(PDO::FETCH_OBJ);
                    foreach ($datacs as $datac) {
                        if (!empty($datac->defaultvalue)) {
                            $xmlnodechp = $domtree->createElement($datac->nom, $datac->defaultvalue);
                            $nodeCourant->appendChild($xmlnodechp);
                        }else{
                            
                        }
                        // $xmlnodechp = $domtree->createElement($champsBalise[$datac->champ], $datac->value);
                        // $nodeCourant->appendChild($xmlnodechp);
                    }
                // }

                
            }
        }

        $domtree = generate_XML($nodeCourant, $bloc->id);
        
        
    }
    

    return $domtree;
}
/* get the xml printed */
$domtree = generate_XML($domtree);

Header('Content-type: text/xml');
echo $domtree->saveXML();