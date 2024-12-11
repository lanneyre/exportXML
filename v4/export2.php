<?php
require("includes/top.inc.php");
/* create a dom document with encoding utf8 */
$domtree = new DOMDocument('1.0', 'UTF-8');
$domtree->preserveWhiteSpace = false;
$domtree->formatOutput = true;

// Root flux
$xmlflux = $domtree->createElement("cpf:flux");
$xmlflux->setAttribute("xmlns:cpf", "urn:cdc:cpf:pc5:schema:1.0.0");
$domtree->appendChild($xmlflux);

// idFlux
$xmlidflux = $domtree->createElement("cpf:idFlux", "Identifiant du flux que j'ignore");
$xmlflux->appendChild($xmlidflux);

$xmlhoro = $domtree->createElement("cpf:horodatage", (new DateTime("now", new DateTimeZone("Europe/Paris")))->format("c"));
$xmlflux->appendChild($xmlhoro);

$xmlEmet = $domtree->createElement("cpf:emetteur");
$xmlflux->appendChild($xmlEmet);

//idClient + Certificateurs
$xmlidClient = $domtree->createElement("cpf:idClient", "04DRP907");
$xmlEmet->appendChild($xmlidClient);

$xmlCertifs = $domtree->createElement("cpf:certificateurs");
$xmlEmet->appendChild($xmlCertifs);

$xmlCertificateur = $domtree->createElement("cpf:certificateur");
$xmlCertifs->appendChild($xmlCertificateur);

$xmlidClient2 = $domtree->createElement("cpf:idClient", "04DRP907");
$xmlCertificateur->appendChild($xmlidClient2);

$xmlidContrat = $domtree->createElement("cpf:idContrat", "MCFCER002304");
$xmlCertificateur->appendChild($xmlidContrat);

$xmlCertifications = $domtree->createElement("cpf:certifications");
$xmlCertificateur->appendChild($xmlCertifications);


//certification 

$xmlCertification = $domtree->createElement("cpf:certification");
$xmlCertifications->appendChild($xmlCertification);

//on va créé les champs de certification 6 - 7 - 8
$xmltype = $domtree->createElement("cpf:type", "RS");
$xmlCertification->appendChild($xmltype);
$xmlcode = $domtree->createElement("cpf:code", "RS2383");
$xmlCertification->appendChild($xmlcode);
$xmlnature = $domtree->createElement("cpf:natureDeposant", "CERTIFICATEUR");
$xmlCertification->appendChild($xmlnature);

$champsPC = [10 => "cpf:idTechnique", 13 => "cpf:obtentionCertification", 14 => "cpf:donneeCertifiee", 15 => "cpf:dateDebutExamen", 16 => "cpf:dateFinExamen", 17 => "cpf:modalitePassageExamen", 18 => "cpf:codePostalCentreExamen", 19 => "cpf:dateDebutValidite", 20 => "cpf:dateFinValidite", 21 => "cpf:presenceNiveauLangueEuro", 23 => "cpf:presenceNiveauNumeriqueEuro", 24 => "cpf:scoring", 25 => "cpf:mentionValidee"];

$champsTit = [35 => "cpf:nomNaissance", 36 => "cpf:nomUsage", 37 => "cpf:prenom1", 40 => "cpf:anneeNaissance", 41 => "cpf:moisNaissance", 42 => "cpf:jourNaissance", 43 => "cpf:sexe", 46 => "cpf:libelleCommuneNaissance", 48 => "cpf:libellePaysNaissance"];


$xmlpassagescertif = $domtree->createElement("cpf:passageCertifications");
$xmlCertification->appendChild($xmlpassagescertif);

$file = fopen("candidats.csv", "rb");
feof($file);
feof($file); // Pour sauter les 2 premières lignes
while (!feof($file)) {
    //passage certifications
    $xmlpassages = $domtree->createElement("cpf:passageCertification");
    $xmlpassagescertif->appendChild($xmlpassages);

    // $querydatabStatement->execute(["bloc" => 5]);
    // $passagesCertifs = $querydatabStatement->fetchAll(PDO::FETCH_OBJ);

    $passage = fgetcsv($file, null, ";");
    $xmlpassage = $domtree->createElement("cpf:idTechnique", uniqid()."-" . uniqid() . "-".uniqid());
    $xmlpassages->appendChild($xmlpassage);

    //cpf:obtentionCertification
    $obtentionCertification = $domtree->createElement("cpf:obtentionCertification", "PAR_SCORING");
    $xmlpassages->appendChild($obtentionCertification);

    //cpf:donneeCertifiee
    $donneeCertifiee = $domtree->createElement("cpf:donneeCertifiee", "true");
    $xmlpassages->appendChild($donneeCertifiee);

    //cpf:dateDebutExamen
    $dateDebutExamen = $domtree->createElement("cpf:dateDebutExamen", (DateTime::createFromFormat("d/m/Y", $passage[9]))->format("Y-m-d"));
    $xmlpassages->appendChild($dateDebutExamen);

    //cpf:dateFinExamen
    $dateFinExamen = $domtree->createElement("cpf:dateFinExamen", (DateTime::createFromFormat("d/m/Y", $passage[10]))->format("Y-m-d"));
    $xmlpassages->appendChild($dateFinExamen);

    //cpf:modalitePassageExamen
    $modalitePassageExamen = $domtree->createElement("cpf:modalitePassageExamen", $passage[11]);
    $xmlpassages->appendChild($modalitePassageExamen);

    //cpf:codePostalCentreExamen
    $codePostalCentreExamen = $domtree->createElement("cpf:codePostalCentreExamen", $passage[12]);
    $xmlpassages->appendChild($codePostalCentreExamen);

    //cpf:dateDebutValidite
    $dateDebutValidite = $domtree->createElement("cpf:dateDebutValidite", (DateTime::createFromFormat("d/m/Y", $passage[13]))->format("Y-m-d"));
    $xmlpassages->appendChild($dateDebutValidite);

    //cpf:dateFinValidite
    $dateFinValidite = $domtree->createElement("cpf:dateFinValidite",(DateTime::createFromFormat("d/m/Y", $passage[13]))->format("2099-m-d"));
    $xmlpassages->appendChild($dateFinValidite);

    //cpf:presenceNiveauLangueEuro
    $presenceNiveauLangueEuro = $domtree->createElement("cpf:presenceNiveauLangueEuro", $passage[15]);
    $xmlpassages->appendChild($presenceNiveauLangueEuro);

    //cpf:presenceNiveauNumeriqueEuro
    $presenceNiveauNumeriqueEuro = $domtree->createElement("cpf:presenceNiveauNumeriqueEuro", $passage[16]);
    $xmlpassages->appendChild($presenceNiveauNumeriqueEuro);

    //cpf:scoring
    $scoring = $domtree->createElement("cpf:scoring", $passage[17]);
    $xmlpassages->appendChild($scoring);

    //cpf:mentionValidee
    $mentionValidee = $domtree->createElement("cpf:mentionValidee", "SANS_MENTION");
    $xmlpassages->appendChild($mentionValidee);

    //cpf:modalitesInscription
    $modalitesInscription = $domtree->createElement("cpf:modalitesInscription");
    $xmlpassages->appendChild($modalitesInscription);

    //cpf:modaliteAcces
    $modaliteAcces = $domtree->createElement("cpf:modaliteAcces", str_replace(" ", "_", strtoupper($passage[19])));
    $modalitesInscription->appendChild($modaliteAcces);

    //cpf:identificationTitulaire
    $identificationTitulaire = $domtree->createElement("cpf:identificationTitulaire");
    $xmlpassages->appendChild($identificationTitulaire);
    $titulaire = $domtree->createElement("cpf:titulaire");
    $identificationTitulaire->appendChild($titulaire);

    //cpf:nomNaissance
    $nomNaissance = $domtree->createElement("cpf:nomNaissance", $passage[20]);
    $titulaire->appendChild($nomNaissance);

    //cpf:nomUsage
    $nomUsage = $domtree->createElement("cpf:nomUsage", $passage[21]);
    $titulaire->appendChild($nomUsage);

    //cpf:prenom1
    $prenom1 = $domtree->createElement("cpf:prenom1", $passage[22]);
    $titulaire->appendChild($prenom1);

    //cpf:anneeNaissance
    $anneeNaissance = $domtree->createElement("cpf:anneeNaissance", $passage[24]);
    $titulaire->appendChild($anneeNaissance);

    //cpf:moisNaissance
    $moisNaissance = $domtree->createElement("cpf:moisNaissance", $passage[25]);
    $titulaire->appendChild($moisNaissance);

    //cpf:jourNaissance
    $jourNaissance = $domtree->createElement("cpf:jourNaissance", $passage[26]);
    $titulaire->appendChild($jourNaissance);

    //cpf:sexe
    $sexe = $domtree->createElement("cpf:sexe", $passage[27]);
    $titulaire->appendChild($sexe);

    //cpf:codeCommuneNaissance
    $codeCommuneNaissance = $domtree->createElement("cpf:codeCommuneNaissance");
    $titulaire->appendChild($codeCommuneNaissance);

    //cpf:libelleCommuneNaissance
    $libelleCommuneNaissance = $domtree->createElement("cpf:libelleCommuneNaissance", $passage[28]);
    $titulaire->appendChild($libelleCommuneNaissance);

    //cpf:codePostalNaissance
    $codePostalNaissance = $domtree->createElement("cpf:codePostalNaissance");
    $codeCommuneNaissance->appendChild($codePostalNaissance);
    //cpf:codePostalNaissance
    $codePostalNaissancecp = $domtree->createElement("cpf:codePostal", "00000");
    $codePostalNaissance->appendChild($codePostalNaissancecp);

    //cpf:libellePaysNaissance
    $libellePaysNaissance = $domtree->createElement("cpf:libellePaysNaissance", $passage[29]);
    $titulaire->appendChild($libellePaysNaissance);
}
fclose($file);
// Pour chaque étudiants

// $queryEtudiantsStatement->execute(["champ"=> 35]);
// $candidats= $queryEtudiantsStatement->fetchAll(PDO::FETCH_OBJ);

$c = 1;
/*
$file = fopen("candidat.csv", "rb");
while (!feof($file)){
    // message($candidats);
    //print_r(fgetcsv($file, null, ";"));
    //foreach ($candidats as $candidat) {
        # code...
        // $xmlpassage = $domtree->createElement("cpf:passageCertification");
        // $xmlpassages->appendChild($xmlpassage);

        // foreach ($champsPC as $id => $balise) {
        //     $querydatacbubandcStatement->execute(["datab" => $passage->id, "champ" => $id]);
        //     $champs = $querydatacbubandcStatement->fetch(PDO::FETCH_OBJ);
        //     $xmlch = $domtree->createElement($balise, $champs->value);
        //     $xmlpassage->appendChild($xmlch);
        // }
        // $xmlmod = $domtree->createElement("cpf:modalitesInscription");
        // $xmlpassage->appendChild($xmlmod);
        // $xmlmoda = $domtree->createElement("cpf:modaliteAcces", "formation continue hors contrat de professionnalisation");
        // $xmlmod->appendChild($xmlmoda);
        // // identificationTitulaire 11
        // $xmlidTit = $domtree->createElement("cpf:identificationTitulaire");
        // $xmlpassage->appendChild($xmlidTit);
        // //titulaire
        // $xmlTit = $domtree->createElement("cpf:titulaire", $c++);
        // $xmlidTit->appendChild($xmlTit);
    }
//}
fclose($file);
    // foreach($passagesCertifs as $passage){

        

        
    // }


*/

// Validation du document XML
$schema = "certifications-cdc-v1.0.0.xsd";

//Header('Content-type: text/xml');
$domtree->save("temp.xml");

$tempDom = new DOMDocument();
$tempDom->load("temp.xml");



if($tempDom->schemaValidate($schema) ){
    Header('Content-type: text/xml');
    echo $domtree->saveXML();
} else {
    echo "<b>DOMDocument::schemaValidate() Generated Errors!</b>";
}

if (is_file("temp.xml")) {
    unlink("temp.xml");
}