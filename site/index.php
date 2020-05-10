<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
// temporary file with corona form
Define('REQUEST_OK', \true);
require 'clients.php'; // this file is not part of the repository and cannot be summoned directly
if (isset($_GET['company']) and isset($a[$company = $_GET['company']])) {
    $a = $a[$company];
} else {
    //header('Location: https://ruigehond.nl/', true, 301);
    header('Location: /ruigehond.php',\true,307);
    die();
}

// PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Base files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
// handle form
$message = '';
$success = true;
if (isset($_POST['corona']) and $_POST['corona'] === '') {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $fields = [
        'secret' => $recaptcha_secret_key,
        'response' => $_POST['g-recaptcha-token'],
    ];
    $fields_string = \http_build_query($fields);
    $ch = \curl_init();
    \curl_setopt($ch, CURLOPT_URL, $url);
    \curl_setopt($ch, CURLOPT_POST, true);
    \curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = (array)json_decode(\curl_exec($ch));
    if ($result['success'] === false) {
        $message .= '<h2>Recaptcha failed</h2>';
        $message .= '<p>' . var_export($result['error-codes'], true) . '</p>';
        $success = false;
    } else {
        if (floatval($result['score']) < floatval($recaptcha_pass_score)) {
            $message .= '<h2>Recaptcha failed</h2>';
            $message .= '<p>' . sprintf('%1$s score %2$s too low', 'reCaptcha', $result['score']) . '</p>';
            $success = false;
        }
    }
    // form is considered valid
    if ($success === true) {
        // create object of PHPMailer class with boolean parameter which sets/unsets exception.
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP(); // using SMTP protocol
            $mail->Host = 'smtp.gmail.com'; // SMTP host as gmail
            $mail->SMTPAuth = true;  // enable smtp authentication
            $mail->Username = 'coronaformulier@gmail.com';  // sender gmail host
            $mail->Password = 'R5%-+djewk'; // sender gmail host password
            $mail->SMTPSecure = 'tls';  // for encrypted connection
            $mail->Port = 587;   // port for SMTP
            $mail->setFrom('coronaformulier@gmail.com', 'Niet beantwoorden'); // sender's email and name
            $mail->addAddress($a['mail'], $a['sign']);  // receiver's email and name
            $mail->Subject = 'Gezondheidscheck van ' . htmlentities($_POST['naam']);
            $str = '';
            foreach ($_POST as $item => $value) {
                if ($item === 'g-recaptcha-token') continue;
                if ($item === 'corona') continue;
                $str .= $item . ': ' . htmlentities($value) . "\n";
            }
            $mail->Body = $str;
            $mail->send();
            $message .= '<h2>Bedankt!</h2>';
            $message .= '<p>Formulier is verstuurd naar ' . $a['name'] . '.</p>';
            $success = true;
        } catch (Exception $e) { // handle error.
            $message .= '<h2>Verzenden mislukt</h2>';
            $message .= '<p>' . $mail->ErrorInfo . '</p>';
        }
    }
}
// head
echo '<!DOCTYPE html><html lang="nl"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>';
echo $a['name'];
echo ' ~ corona checklist en maatregelen</title><meta name="description" content="Corona maatregelen en gezondheidscheck cliënt - ';
echo $a['name'];
echo ' - online invullen, voldoe aan de eisen van het RIVM mbt corona."/><link rel="canonical" href="https://coronaformulier.nl/';
echo $company;
echo '"/><meta property="og:url" content="https://coronaformulier.nl/';
echo $company;
echo '"/><meta property="og:site_name" content="';
echo $company;
echo '"/><meta property="og:title" content="';
echo $a['name'];
echo ' ~ corona checklist en maatregelen"/><meta property="og:type" content="website"/><meta property="og:description" content="Corona maatregelen en gezondheidscheck cliënt - ';
echo $a['name'];
echo ' - online invullen, voldoe aan de eisen van het RIVM mbt corona."/> <meta property="og:image" content="https://coronaformulier.nl/img/';
echo $a['logo'];
echo '"/><meta property="og:image:width" content="1280"/><meta property="og:image:height" content="1280"/><meta property="og:image:secure_url" content="https://coronaformulier.nl/img/';
echo $a['logo'];
echo '"/><link rel="stylesheet" href="/corona.css?version=0.2"/><style type="text/css">input[type=submit] { background-color: #';
echo $a['color'];
echo '; } h1, h2, h3, strong {color: #';
echo $a['color'];
echo ';} input[type="radio"]:focus { outline: solid #';
echo $a['color'];
echo ' 4px; } input[type="text"]:focus { background-image:url(\'data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20fill%3D%22%23';
echo $a['color'];
echo '%22%20d%3D%22M24%201.5L.31%2012.19%2024%2022.89z%22%2F%3E%3C%2Fsvg%3E\'); }</style><script src="https://www.google.com/recaptcha/api.js?render=';
echo $recaptcha_site_key;
echo '" async defer></script>';
?>
    <script>
        function ruigehond_setup() {
            var form = document.querySelector('form'),
                valid;
            form.querySelectorAll('input[type="submit"]').forEach(function (el) {
                el.addEventListener('blur', function () {
                    // remove notices
                    document.querySelectorAll('.notice').forEach(function (el) {
                        el.parentNode.removeChild(el);
                    });
                });
            });
            form.addEventListener('submit', function (e) {
                valid = true; // form is valid until proven otherwise
                form.querySelectorAll('input[type="submit"]').forEach(function (el) {
                    el.setAttribute('disabled', 'disabled');
                });
                form.querySelectorAll('input').forEach(function (el) {
                    var n, r;
                    if (valid === true) { el.focus(); }
                    if (el.type === 'radio') {
                        r = form.querySelectorAll('input[name="' + el.name + '"]');
                        if (false === r[0].checked && false === r[1].checked) valid = false;
                    } else if ((n = el.name) === 'naam' || n === 'geboortedatum' || n === 'vervoersmiddel') {
                        if (el.value === '') valid = false;
                    }
                });
                if (valid === false) {
                    alert('Vul het volledige formulier in alstublieft');
                    form.querySelectorAll('input[type="submit"]').forEach(function (el) {
                        el.removeAttribute('disabled');
                    });
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
                form.querySelectorAll('input[type="submit"]').forEach(function (el) {
                    el.insertAdjacentHTML('afterend', '<span class="notice">Bezig met verzenden</span>');
                });
                try {
                    grecaptcha.execute('<?php echo $recaptcha_site_key ?>', {action: 'form_submit'}).then(function (token) {
                        // set token in form
                        form.querySelector('[name="g-recaptcha-token"]').value = token;
                        // submit ok, let it go
                        form.submit();
                    })
                } catch (thrown_error) {
                    console.error(thrown_error);
                    alert('Recaptcha error');
                    form.querySelectorAll('input[type="submit"]').forEach(function (el) {
                        el.removeAttribute('disabled');
                    });
                }
                e.preventDefault();
                e.stopPropagation();
                return false;
            });
        }

        if (document.readyState !== 'loading') {
            ruigehond_setup();
        } else {
            document.addEventListener("DOMContentLoaded", function () {
                ruigehond_setup();
            });
        }
    </script>
<?php
echo '</head><body>';
// messages
if ($message !== '') {
    echo '<div class="message">';
    if ($success === false) {
        echo '<p class="error">Er is een fout opgetreden, probeer het alstublieft opnieuw</p>';
    }
    echo $message;
    echo '</div>';
}
// shortcut for li / question items
function echo_question(string $label, string $text, string $described_by = '') {
    if ($described_by !== '') {
        $described_by_id = hash('md5', $described_by);
    }
    echo '<li><fieldset><legend>';
    echo $text;
    echo '</legend><label><input type="radio" name="';
    echo $label;
    if (isset($described_by_id)) {
        echo '" aria-describedby="';
        echo $described_by_id;
        }
    echo '" value="Ja"> Ja</label><label><input type="radio" name="';
    echo $label;
    echo '" value="Nee"> Nee</label></fieldset>';
    if (isset($described_by_id)) {
        echo '<p class="describedby" id="';
        echo $described_by_id;
        echo '">';
        echo $described_by;
        echo '</p>';
    }
    echo '</li>';
}
// the contents
echo '<header>Graag volledig invullen en verzenden!</header> 
<section>
<p>Geachte heer / mevrouw,</p>

<p>Het Covid-19 (Corona) virus houdt iedereen bezig.<br/> 
Zoals u wellicht al vernomen heeft zullen wij vanaf 11 mei 2020 open gaan. We maken alleen afspraken met mensen waarvan we met vrij grote zekerheid weten dat ze gezond zijn en dat ze niet vallen onder de risicogroepen zoals gedefinieerd door het RIVM.<br/> 
U kunt geen afspraak maken als u behoort tot de groep kwetsbare mensen zoals gedefinieerd door de RIVM. Kwetsbare personen of mensen met een zwakke gezondheid zijn mensen van 70 jaar en ouder en mensen die een van de hieronder in de vragen vermelde aandoeningen hebben.</p>

<ul>
<li>Algemeen geldt: geen handen schudden, regelmatig handen wassen, hoesten en niezen in de ellenboog en gebruik van papieren zakdoekjes.</li> 
<li>Wanneer er tussen de afspraakbevestiging en de afspraak zelf verschijnselen optreden, wordt u verzocht de afspraak te annuleren.</li> 
<li>U wordt verzocht alleen te verschijnen op de afspraak. </li>
<li>U kunt geen gebruik maken van het toilet in de praktijk. </li>
</ul>
<img src="/img/';
echo $a['logo'];
echo '" alt="Logo praktijk ';
echo $a['name'];
echo '"/>
</section>
<form method="post" action="/';
echo $company;
echo '">
<input type="hidden" name="corona" value=""/>
<input type="hidden" name="g-recaptcha-token" value=""/>
<section>
<h1>Gezondsheidscheck ';
echo $a['name'];
echo '</h1>
<h3>Stap 1: Uw gegevens</h3>
<p>
<fieldset>
<label>Naam:<input type="text" name="naam" autocomplete="name"/><br/></label>
<label>Geboortedatum:<input type="text" name="geboortedatum" autocomplete="bday"/></label>
</p> 
<p><em>Indien 70 jaar en ouder (geboren in 1950 of vroeger), dan behoort u tot de kwetsbare groep.</em></p> 
<p>
<label>Vervoermiddel praktijk (fiets, lopen, auto etc.):<input type="text" name="vervoermiddel"/></label>
</p>
</fieldset>
</section>
<section>
<h3>Stap 2: Aandoeningen</h3>
<ol>';
echo_question('longprobleem', 'Heeft u een chronische luchtweg- of longprobleem en daar zo veel last van dat u onder behandeling van een longarts bent?');
echo_question('hartpatiënt', 'Bent u chronische hartpatiënt en heeft u daar zoveel last van dat u onder behandeling bent van een cardioloog?');
echo_question('diabetes', 'Heeft u diabetes mellitus (suikerziekte) met complicaties?');
echo_question('nieraandoeningen', 'Heeft u ernstige nieraandoeningen die leiden tot dialyse of niertransplantatie?');
echo_question('verminderde_weerstand', 'Heeft u een verminderde weerstand tegen infecties?', 'Hieronder vallen: Mensen met een verminderde weerstand tegen infecties doordat zij medicijnen gebruiken voor een auto-immuunziekte en mensen die een orgaan of stamceltransplantatie hebben ondergaan. Mensen die geen milt hebben of een milt die niet functioneert en mensen die een bloedziekte hebben. Mensen met een verminderde weerstand doordat ze weerstand verlagende medicijnen nemen. Kankerpatiënten tijdens of binnen 3 maanden na chemotherapie en/of bestraling. Mensen met ernstige afweerstoornissen waarvoor zij behandeling nodig hebben van een arts.');
echo_question('hivinfectie', 'Heeft u een hivinfectie die (nog) niet onder behandeling is van een arts of een hivinfectie met een CD4 cluster of differentiation 4 getal onder 200/mm2.');
echo_question('leverziekte', 'Heeft u een ernstige leverziekte?');
echo_question('overgewicht', 'Heeft u ernstig overgewicht?');
echo '
</ol>
</section>
<section>
<h3>Stap 3: Vragen voor de gezondheidscheck.</h3>
<p>Wanneer u één van onderstaande vragen met ‘Ja’ beantwoordt, mag u niet naar de afspraak komen. De afspraak moet worden uitgesteld totdat op elke vraag ‘Nee’ geantwoord kan worden.</p>
<ol start="9">';
echo_question('milde_klachten', 'Heeft u de afgelopen 24 uur of op dit moment één of meerdere van de volgende (milde) klachten: neusverkoudheid, hoesten, benauwdheid en/of koorts (vanaf 38 graden Celsius)?');
echo_question('huisgenoot', 'Heeft u op dit moment een huisgenoot/gezinslid met koorts en/of benauwdheidsklachten?');
echo_question('coronavirus_gehad', 'Heeft u het nieuwe coronavirus gehad (vastgesteld met een laboratoriumtest) en is dit in de afgelopen 7 dagen vastgesteld?');
echo_question('huisgenoot_coronavirus_gehad', 'Heeft u een huisgenoot/gezinslid met het nieuwe coronavirus (vastgesteld met een laboratoriumtest) en heeft u korter dan 14 dagen geleden contact gehad met deze huisgenoot/gezinslid terwijl hij/zij nog klachten had?');
echo_question('thuisisolatie', 'Bent u in thuisisolatie omdat u direct contact hebt gehad met iemand waarbij het nieuwe coronavirus is vastgesteld?');
echo '
</ol>
</section>
<section>
<h3>Stap 4: Controlevraag</h3>
<ul>
<li>Indien deze vraag met ‘Ja’ wordt beantwoord dan kan er behandeld worden.</li> 
<li>Indien alles ‘Nee’ dan is behandeling mogelijk na verdere anamnese en risico inschatting door behandelaar in samenspraak met u, de cliënt.</li>
</ul>

<ol start="14">';
echo_question('controlevraag', 'Bent u verder gezond en voelt u zich momenteel gezond?');
echo '
</ol>
<p><em>Uw gegevens worden verzonden via een beveiligde verbinding, rechtstreeks naar de praktijk.<br/>Er wordt niets opgeslagen.</em></p>
<p><input type="submit" value="Verzenden"/></p>
</section>
<section>
<h2>Aanvullende maatregelen in de praktijk:</h2>
<h3>Wat vragen we van u?</h3> 
<ul>
<li>Algemeen geldt: geen handen schudden, handen wassen, hoesten en niezen in de ellenboog en gebruik van papieren zakdoekjes.</li>
<li>Kom niet te vroeg, dus liefst vlak voor de afspraaktijd bij ons binnen.</li>
<li>Kom alleen, het aantal personen in de praktijk houden wij graag beperkt.</li>
<li>Bij binnenkomst wast u op de daarvoor aangewezen plaats de handen of gebruikt u de handalcohol.</li>
<li>Houd 1,5 meter afstand van elkaar.</li>
<li>Probeer als het mogelijk is zo min mogelijk deurkrukken, trapleuning, tafeloppervlakken en stoelen aan te raken met uw handen.</li>
<li>Houdt u er rekening mee dat de toiletten tijdelijk gesloten zijn.</li>
<li>Twijfelt u over uw gezondheid op de dag van de afspraak? Neem dan voor de afspraak contact met ons op om te overleggen of uw afspraak kan doorgaan.</li> 
<li>Betaling vindt bij voorkeur plaats via een pinbetaling of een betaalverzoek.</li>
<li>Geannuleerde afspraken vanwege gezondheidsoverwegingen zullen wij uiteraard niet in rekening brengen.</li>
<li>Volg de instructies van de behandelaar altijd op.</li>
<li>We hopen op uw begrip dat als gevolg van deze situatie uw afspraak wellicht later plaatsvindt dat u had gehoopt. </li>
</ul>

<h3>Onze voorzorgsmaatregelen:</h3> 
<ul>
<li>De behandelaar heeft geen ziekteverschijnselen. </li>
<li>Wij geven geen hand bij binnenkomst.</li>
<li>Na iedere cliënt reinigen en desinfecteren we alle vervuilde oppervlakken en ruimtes waar u bent geweest. </li>
<li>We ventileren de behandelkamer. </li>
<li>We plannen meer tijd voor de afspraken om bovenstaande voorzorgsmaatregelen mogelijk te maken.</li>
</ul>

<p>Wij kijken er naar uit om u weer van dienst te zijn zoals u dat van ons gewend bent. We zullen u over de verdere ontwikkelingen blijven informeren.</p>
<p><strong>Blijf gezond!</strong></p> 
<p>';
echo $a['sign'];
echo '</p>
<p><em>Uw gegevens worden verzonden via een beveiligde verbinding, rechtstreeks naar de praktijk.<br/>Er wordt niets opgeslagen.</em></p>
<p><input type="submit" value="Checklist verzenden"/></p>
</section>
</form>
<footer>
<p>Dit formulier maakt gebruik van Google recaptcha tegen spam. Verder worden er geen cookies geplaatst en al helemaal geen statistieken bijgehouden.</p>
<p>Ook zo&rsquo;n mooi formulier voor je praktijk? <a href="/ruigehond.php">Meer informatie</a></p>
<p>Dit formulier is voor <a href="';
echo $a['site'];
echo '">';
echo $a['name'];
echo '</a>.</p>
</footer>';
// end
echo '</body></html>';