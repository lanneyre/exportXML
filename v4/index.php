<?php
include("template/templateTop.php");
?>
<fieldset class="form">
    <?php
    if (empty($_GET["datab"])) {

        echo "<legend>Pas de champs disponible</legend>Pour remplir les champs merci de cliquer sur les blocs sur gauche";
    } else {
        $querydatabbyidStatement->execute(["id" => $_GET["datab"]]);
        $b = $querydatabbyidStatement->fetch(PDO::FETCH_OBJ);
        if($b===false){
            header("Location: index.php");
            exit;
        }
        $querychampsStatement->execute(["blocs" => $b->bloc]);
        $champs = $querychampsStatement->fetchAll(PDO::FETCH_OBJ);
        if (sizeof($champs) == 0) {
            header("Location: index.php");
            exit;
        }
        $queryBlocsbyidStatement->execute(["id" => $b->bloc]);
        $bloc = $queryBlocsbyidStatement->fetch(PDO::FETCH_OBJ);
        if ($bloc === false) {
            header("Location: index.php");
            exit;
        }
        echo "<legend>" . $bloc->nom . "</legend>";
    ?>
        <form>
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
        <fieldset class="footer">
            <section id="aide">&nbsp; </section>
            <section id="icons">
                <img src="images/sauvegarder.png" alt="Save">
                <img src="images/xml.png" alt="xml">
            </section>
        </fieldset>
    <?php
    }
    ?>
</fieldset>
<?php

include("template/templateFoot.php");
