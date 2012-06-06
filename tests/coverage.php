<?php
use \mageekguy\atoum;

$stdOutWriter = new atoum\writers\std\out();

$coverageField = new atoum\report\fields\runner\coverage\html('anchour', dirname(__DIR__) . '/target/coverage');
$coverageField->setRootUrl('file://' . dirname(__DIR__) . '/target/coverage/index.html');

$cliReport = new atoum\reports\realtime\cli();
$cliReport
    ->addWriter($stdOutWriter)
    ->addField($coverageField, array(atoum\runner::runStop))
;

$runner->addReport($cliReport);

?>
