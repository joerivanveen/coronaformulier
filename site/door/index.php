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
echo ' ~ gezondheidcheck formulier</title><meta name="description" content="Instant gezondheidcheck formulier van ';
echo $a['name'];
echo ' - online invullen, meteen antwoord, gebaseerd op informatie van RIVM."/><link rel="canonical" href="https://coronaformulier.nl/door/';
echo $company;
echo '"/><meta property="og:url" content="https://coronaformulier.nl/door/';
echo $company;
echo '"/><meta property="og:site_name" content="Coronaformulier ';
echo $company;
echo '"/><meta property="og:title" content="';
echo $a['name'];
echo ' ~ coronaformulier"/><meta property="og:type" content="website"/><meta property="og:description" content="Gezondheidcheck aan de deur ivm corona / COVID-19 bij ';
echo $a['name'];
echo '."/> <meta property="og:image" content="https://coronaformulier.nl/img/dummy-V2.jpg"/><meta property="og:image:width" content="736"/><meta property="og:image:height" content="736"/><meta property="og:image:secure_url" content="https://coronaformulier.nl/img/dummy-V2.jpg"/><link rel="stylesheet" href="/door/style.css?version=0.2"/><style type="text/css">h1, h2, h3, strong {color: #';
echo $a['color'];
echo ';} input[type="radio"]:focus { outline: solid #';
echo $a['color'];
echo ' 4px; }</style>';
if (isset($a['style'])) echo '<style type="text/css">' . $a['style'] . '</style>';
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
    echo '" value="True"> ' . __('yes') . '</label><label><input type="radio" name="';
    echo $label;
    if (false === $right_answer) {
        echo '" data-valid="1';
    }
    echo '" value="False"> ' . __('no') . '</label></fieldset>';
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
echo '<img src="/img/' . $a['logo'] . '" alt="logo"/>';
echo ' 
<form method="post" id="the_form">
<section><h1>';
echo __('title');
echo '</h1><div>';
echo str_replace('{{name}}', $a['name'], str_replace('{{sign}}', $a['sign'], __($a['text'])));
echo '</div></section>
<section><ol>';
$array = array_keys($a['questions']);
$last_key = end($array);
foreach ($a['questions'] as $question=>$right_answer) {
    if ($question === $last_key) echo '<h3>' . __('controlevraag_h') . '</h3>';
    echo_question($question, $right_answer);
}
echo '</ol></form>
</section>
<footer>';
echo __('text_end');
echo '<p>Ook zo’n formulier voor je gelegenheid? <a href="https://coronaformulier.nl/ruigehond.php">Meer info</a></p>
</footer>';
echo '<div id="validation_check"><div><strong>OK</strong>';
echo $a['name'];
echo '</div></div><div id="validation_cross"><div><strong>×</strong>Geen toegang<br/>';
echo $a['name'];
echo '</div></div>';
// end
echo '</body></html>';