<?php
if (\false === defined('REQUEST_OK')) {
    header('Location: /ruigehond.php',\true,307);
    die();
}
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
        'yes' => 'Ja',
        'no' => 'Nee',
        'title' => 'Gezondheidcheck',
        // teksten
        'text_default' => '<p>Welkom bij <strong>{{name}}</strong>. Op advies van het RIVM stellen wij u deze 6 vragen.</p><p class="notice">Beantwoord de vragen naar waarheid voor uw huidige gezelschap</p><p>Uw antwoorden worden niet verzonden of bewaard.<br/>Bij negatief resultaat zullen wij u geen toegang verlenen. Bedankt voor uw begrip,<br/><strong>{{sign}}</strong></p>',
        'text_end' => 'Deze tekst is gebaseerd op het document ‘veilig werken bij contactberoepen’, zie rivm.nl/covid19',
        // custom
        'text_airbnb' => 'Beste gasten,<br/>
Hartelijk welkom bij <strong>{{name}}</strong>.<br/>
In verband met het COVID-19 virus en de daardoor ontstane maatregelen van het RIVM ben ik genoodzaakt de volgende 6 vragen aan gasten te stellen.<br/>
Een vriendelijk verzoek deze vragenlijst naar waarheid in te vullen. Ik wens u een heel prettige verblijf en blijf gezond!<br/>
Warme groet,<br/>
{{sign}}',
        'text_horecagedeelte' => 'Beste bezoeker,<br/>
Welkom in het horecagedeelte van {{name}}.<br/>
In verband met het COVID-19 virus en de daardoor ontstane maatregelen van het RIVM zijn wij genoodzaakt de volgende 6 vragen te stellen.<br/>
Een vriendelijk verzoek deze vragenlijst naar waarheid in te vullen. Bedankt voor uw medewerking.<br/>
{{sign}}',
        // vragen
        'milde_klachten' => 'Heeft één van u de afgelopen 24 uur of op dit moment één of meerdere van de volgende (milde) klachten: neusverkoudheid, hoesten, benauwdheid en/of koorts (vanaf 38 graden Celsius)?',
        'huisgenoot' => 'Heeft één van u op dit moment een huisgenoot/gezinslid met koorts en/of benauwdheidsklachten?',
        'coronavirus_gehad' => 'Heeft één van u het nieuwe coronavirus gehad (vastgesteld met een laboratoriumtest) en is dit in de afgelopen 7 dagen vastgesteld?',
        'huisgenoot_coronavirus_gehad' => 'Heeft één van u een huisgenoot/gezinslid met het nieuwe coronavirus (vastgesteld met een laboratoriumtest) en heeft één van u korter dan 14 dagen geleden contact gehad met deze huisgenoot/gezinslid terwijl hij/zij nog klachten had?',
        'thuisisolatie' => 'Is één van u in thuisisolatie vanwege direct contact met iemand waarbij het nieuwe coronavirus is vastgesteld?',
        'controlevraag' => 'Bent u allen verder gezond en voelt u zich momenteel gezond?',
        'controlevraag_h' => 'Controlevraag',
        // custom vragen
        'milde_klachten1' => 'Had u een of meerdere van deze klachten in de afgelopen 24 uur?<br/>Hoesten, neusverkoudheid, koorts, benauwdheidsklachten.',
        'milde_klachten2' => 'Had u of uw reisgezelschap een of meerdere van deze klachten in de afgelopen 24 uur?<br/>Hoesten, neusverkoudheid, koorts, benauwdheidsklachten.',
        'huisgenoot1' => 'Heeft u op dit moment een huisgenoot met koorts en/of benauwdheidsklachten?',
        'huisgenoot2' => 'Heeft u op dit moment een huisgenoot of iemand in uw reisgezelschap met koorts en/of benauwdheidsklachten?',
        'coronavirus_gehad1' => 'Heeft u het nieuwe Coronavirus gehad en is dit de afgelopen 7 dagen vastgesteld?',
        'huisgenoot_coronavirus_gehad1' => 'Heeft u een huisgenoot/gezinslid met het coronavirus en heeft u de afgelopen 14 dagen contact met die persoon gehad, terwijl hij of zij nog klachten had?',
        'huisgenoot_coronavirus_gehad2' => 'Heeft u een huisgenoot of is er in uw reisgezelschap iemand met het coronavirus en heeft u de afgelopen 14 dagen contact met die persoon gehad, terwijl hij of zij nog klachten had?',
        'thuisisolatie1' => 'Bent u in quarantaine omdat er direct contact is geweest met een persoon waarbij het Coronavirus is vastgesteld?',
        'controlevraag1' => 'Heeft u bovenstaande vragen naar waarheid beantwoord?',
    ),
);

