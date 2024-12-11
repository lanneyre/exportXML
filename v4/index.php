<?php
include("template/templateTop.php");
?>
<fieldset class="form">
    <?php
    if (empty($_GET["datab"])) {

        echo "<legend>Pas de champs disponible</legend>Pour remplir les champs merci de cliquer sur les blocs sur gauche";
    ?>
        <h2>Importer un fichier excel</h2>
        <form action="import.php" method="post" enctype="multipart/form-data" class="import">

            <div class="blocchamps">
                <label for="excel">Fichier excel</label>
                <input type="file" id="excel" name="excel" placeholder="Vous pouvez télécharger un fichier modèle" class="champs">
            </div>
            <input type="image" src="images/import.png" alt="Import" id="import">
        </form>

        <section id="icons" class="home">
            <img src="images/xml.png" alt="xml">
        </section>
    <?php
    } else {
        $querydatabbyidStatement->execute(["id" => $_GET["datab"]]);
        $b = $querydatabbyidStatement->fetch(PDO::FETCH_OBJ);
        if ($b === false) {
            echo '<script>window.location.replace("index.php")</script>';
            // header("Location: index.php");
            // exit;
        }


        $querychampsStatement->execute(["blocs" => $b->bloc]);
        $champs = $querychampsStatement->fetchAll(PDO::FETCH_OBJ);
        if (sizeof($champs) == 0) {
            echo '<script>window.location.replace("index.php")</script>';
        }
        $queryBlocsbyidStatement->execute(["id" => $b->bloc]);
        $bloc = $queryBlocsbyidStatement->fetch(PDO::FETCH_OBJ);
        if ($bloc === false) {
            echo '<script>window.location.replace("index.php")</script>';
        }
        echo "<legend>" . $bloc->nom . "</legend>";
    ?>
        <form method="POST" action="save.php" id="form">
            <input type="hidden" name="datab" value="<?php echo $_GET["datab"]; ?>">
            <div class="blocchamps">
                <label for="parent">Relié à : </label>
                <select name="parent" id="parent">
                    <option disabled>-- Choisir le bloc auquel ratacher ses données --</option>
                    <?php
                    $querydatabStatement->execute(['bloc' => $bloc->parent]);
                    $champsParent = $querydatabStatement->fetchAll(PDO::FETCH_OBJ);
                    if (sizeof($champsParent) > 0) {
                        foreach ($champsParent as $champ) {
                            $querydatacStatement->execute(["datab" => $champ->id]);
                            $c = $querydatacStatement->fetch(PDO::FETCH_OBJ);
                            $nom = empty($c->value) ? "En cours de création" : $c->value;
                            $id = empty($c->datab) ? $champ->id : $c->datab;
                            $selected = ($b->parent == $id) ? "selected" : "";

                            //
                            if ($champ->bloc ==  7) {
                                $querydatacStatement->execute(["datab" => $champ->parent]);
                                $p = $querydatacStatement->fetch(PDO::FETCH_OBJ);
                                echo '<option value="' . $id . '" ' . $selected . '>' . $p->value . " - " . $nom . '</option>';
                            } else {
                                echo '<option value="' . $id . '" ' . $selected . '>' . $nom . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <?php
            $html = "";
            foreach ($champs as $champ) {
                $querydatacbubandcStatement->execute(["datab" => $_GET["datab"], "champ" => $champ->id]);
                $c = $querydatacbubandcStatement->fetch(PDO::FETCH_OBJ);
                $v = empty($c->value) ? $champ->defaultvalue : $c->value;
                $html .= <<<HTML
            <div class="blocchamps">
                <label for="$champ->balise">$champ->nom</label>
                <input 
                    type="text" 
                    id="$champ->balise"
                    name="$champ->id"
                    value="$v" 
                    placeholder="$champ->format" 
                    class="champs" 
                    dataformat="$champ->format" 
                    datadef="$champ->definition">
            </div>
HTML;
            }
            echo $html;
            ?>
        </form>
        <footer class="enfants">
            <?php
            $queryBlocsStatement->execute([
                'parent' => $b->bloc
            ]);
            $blocs = $queryBlocsStatement->fetchAll(PDO::FETCH_OBJ);

            foreach ($blocs as $bloc) {
                echo "<div>";
                echo "<h4>$bloc->nom</h4>";

                //message($b);

                $querydatabbyParentStatement->execute(['parent' => $b->id, "bloc" => $bloc->id]);
                $databEnfants = $querydatabbyParentStatement->fetchAll(PDO::FETCH_OBJ);
                //message($databEnfants);
                foreach ($databEnfants as $databEnfant) {
                    // message($databEnfant);
                    $querydatacStatement->execute(['datab' => $databEnfant->id]);
                    $champ = $querydatacStatement->fetch(PDO::FETCH_OBJ);

                    $nom = empty($champ->value) ? "En cours de création" : $champ->value;
                    $id = empty($champ->datab) ? $databEnfant->id : $champ->datab;
                    if ($databEnfant->bloc ==  7) {
                        $querydatacStatement->execute(["datab" => $databEnfant->parent]);
                        $p = $querydatacStatement->fetch(PDO::FETCH_OBJ);
                        echo <<<HTML
                    <a href="index.php?datab=$id">$p->value - $nom</a> <a href="delbloc.php?id=$id" class="moins"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#00008B" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM184 232H328c13.3 0 24 10.7 24 24s-10.7 24-24 24H184c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/></svg></a>  <br>
HTML;
                    } else {
                        echo <<<HTML
                    <a href="index.php?datab=$id">$nom</a> <a href="delbloc.php?id=$id" class="moins"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#00008B" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM184 232H328c13.3 0 24 10.7 24 24s-10.7 24-24 24H184c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/></svg></a>  <br>
HTML;
                    }
                    // echo $champ->value . "<br>";
                    // message($champs);
                    // foreach ($champs as $champ) {

                    // }
                }

                echo "</div>";
            }
            ?>
        </footer>
        <fieldset class="footer">
            <section id="aide">&nbsp; </section>
            <section id="icons">
                <img src="images/sauvegarder.png" alt="Save" id="save">
                <img src="images/xml.png" alt="xml">
            </section>
        </fieldset>
    <?php
    }
    ?>
</fieldset>
<?php

include("template/templateFoot.php");
