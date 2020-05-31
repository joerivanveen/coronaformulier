<?php
$language = $a['lang'];
if (isset($_GET['lang']) and isset($languages[$_GET['lang']])) {
    $language = $_GET['lang'];
}
function __(string $string)
{
    global $languages, $language;

    return $languages[$language][$string];
}

$languages = array(
    'NL' => array(
        'Ja' => 'Ja',
        'Nee' => 'Nee',
        // vragen
        'milde_klachten' => 'Heeft één van u de afgelopen 24 uur of op dit moment één of meerdere van de volgende (milde) klachten: neusverkoudheid, hoesten, benauwdheid en/of koorts (vanaf 38 graden Celsius)?',
        'huisgenoot' => 'Heeft één van u op dit moment een huisgenoot/gezinslid met koorts en/of benauwdheidsklachten?',
        'coronavirus_gehad' => 'Heeft één van u het nieuwe coronavirus gehad (vastgesteld met een laboratoriumtest) en is dit in de afgelopen 7 dagen vastgesteld?',
        'huisgenoot_coronavirus_gehad' => 'Heeft één van u een huisgenoot/gezinslid met het nieuwe coronavirus (vastgesteld met een laboratoriumtest) en heeft één van u korter dan 14 dagen geleden contact gehad met deze huisgenoot/gezinslid terwijl hij/zij nog klachten had?',
        'thuisisolatie' => 'Is één van u in thuisisolatie vanwege direct contact met iemand waarbij het nieuwe coronavirus is vastgesteld?',
        'controlevraag' => 'Bent u allen verder gezond en voelt u zich momenteel gezond?',
    ),
);

