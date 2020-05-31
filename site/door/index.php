<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
// temporary file with corona form
Define('REQUEST_OK', \true);
require 'clients.php'; // this file is not part of the repository and cannot be summoned directly
if (isset($_GET['company']) and isset($a[$company = $_GET['company']])) {
    $a = $a[$company];
} else {
    header('Location: /ruigehond.php', \true, 307);
    die();
}
// selects language and holds the array with texts that is used by __ function
require 'languages.php';
// head
echo '<!DOCTYPE html><html lang="nl"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>';
echo $a['name'];
echo ' ~ </title><meta name="description" content="Corona maatregelen en gezondheidcheck cliënt - ';
echo $a['name'];
echo ' - online invullen, voldoe aan de eisen van het RIVM mbt corona."/><link rel="canonical" href="https://coronaformulier.nl/';
echo $company;
echo '"/><link rel="stylesheet" href="/door/style.css?version=0.1"/>';
?>
    <script>
        function ruigehond_setup() {
            var form = document.getElementById('the_form'),
                valid, scroll_to_element,
                // for the loops (don't use forEach, doesn't work on old iPad for instance)
                i, len, elements, el, r;
            for (i = 0, elements = form.querySelectorAll('input'), len = elements.length; i < len; ++i) {
                elements[i].addEventListener('change', function () {
                    valid = true; // valid until proven otherwise
                    // score berekenen of scroll naar eerste die niet is ingevuld
                    for (i = 0, elements = form.querySelectorAll('input'), len = elements.length; i < len; ++i) {
                        el = elements[i];
                        //if (valid === true) scroll_to_element = el;
                        r = form.querySelectorAll('input[name="' + el.name + '"]');
                        if (false === r[0].checked && false === r[1].checked) valid = false;
                    }
                    if (valid === true) {// allemaal ingevuld, controleer of alle antwoorden juist zijn
                        for (i = 0, elements = form.querySelectorAll(':checked'), len = elements.length; i < len; ++i) {
                            if (false === elements[i].hasAttribute('data-valid')) valid = false;
                        }
                        if (valid === true) {
                            document.getElementById('validation_check').style.display = 'block';
                        } else {
                            document.getElementById('validation_cross').style.display = 'block';
                        }
                    /*} else {
                        try {
                            window.scrollBy(0, scroll_to_element.getBoundingClientRect().top - 80);
                            scroll_to_element.focus();
                        } catch (e) {
                        }*/
                    }
                });
            }
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
// shortcut for li / question items
function echo_question(string $label, bool $right_answer, string $described_by = '')
{
    if ($described_by !== '') {
        $described_by_id = hash('md5', $described_by);
    }
    echo '<li><fieldset><legend>';
    echo __($label);
    echo '</legend><label><input type="radio" name="';
    echo $label;
    if (isset($described_by_id)) {
        echo '" aria-describedby="';
        echo $described_by_id;
    }
    if (true === $right_answer) {
        echo '" data-valid="1';
    }
    echo '" value="True"> ' . __('Ja') . '</label><label><input type="radio" name="';
    echo $label;
    if (false === $right_answer) {
        echo '" data-valid="1';
    }
    echo '" value="False"> ' . __('Nee') . '</label></fieldset>';
    if (isset($described_by_id)) {
        echo '<p class="describedby" id="';
        echo $described_by_id;
        echo '"><em>';
        echo $described_by;
        echo '</em></p>';
    }
    echo '</li>';
}
// the contents
echo ' 
<form method="post" id="the_form">
<section>
<h1>Gezondheidcheck ';
echo $a['name'];
echo '</h1>
<p><div class="notice">Beantwoord de vragen voor uw huidige gezelschap</div></p>
<p>Uw antwoorden worden niet verzonden of opgeslagen.<br/>Toon het resultaat aan ons personeel bij het eerste contact.</p>
</section>
<section>
<ol start="1">';
echo_question('milde_klachten', false);
echo_question('huisgenoot', false);
echo_question('coronavirus_gehad', false);
echo_question('huisgenoot_coronavirus_gehad', false);
echo_question('thuisisolatie', false);
echo '
</ol>
</section>
<section>
<h3>Controlevraag</h3>
<ol start="6">';
echo_question('controlevraag', true);
echo '
</ol>
<p>Alleen samen kunnen we corona de baas.<br/>
<strong>Blijf gezond!</strong><br/>';
echo $a['sign'];
echo '</p>
</form>
</section>
<footer>
<p>Ook zo&rsquo;n mooi formulier voor je gelegenheid? <a href="https://coronaformulier.nl/ruigehond.php">Meer informatie</a></p>
</footer>';
echo '<div id="validation_check"><div><strong>OK</strong>'.$a['name'].'</div></div><div id="validation_cross"><div><strong>×</strong>'.$a['name'].'</div></div>';
// end
echo '</body></html>';