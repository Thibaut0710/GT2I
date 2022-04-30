<?php
include("MagicParser.php");
$filename = "catalogue.XML";
function fonctionTexte($parseur, $texte)
{
    echo $texte;
}
function finDeLigne($parseur, $texte)
{
    echo '<br/>';
}
$parseurXML = xml_parser_create();

xml_set_character_data_handler($parseurXML, "fonctionTexte");
$fichier = MagicParser_fopen($filename, "r");
MagicParser_xml_cdata($parseurXML, $fichier);


if (!$fichier) die("Impossible d'ouvrir le fichier");

while ($ligne = MagicParser_fread($fichier, 1024)) {
    xml_parse($parseurXML, $ligne, feof($fichier)) or die("Erreur");
}
xml_parser_free($parseurXML);
MagicParser_fclose($fichier);
