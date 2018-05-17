<?php


require_once __DIR__.'/../vendor/autoload.php';

use Tracy\Debugger;
Debugger::enable();


if($_POST) {
    $api = new \VicHaunter\Telegram\Api('517124109:AAE0CKU4W1rlRFT7HLw1cHCjNejY5rAKjhs', 'ExchandlerBot');
    $api->setChat(-280298442);
    $api->sendMessage($_POST['message']);
}
//dump($api->send('sendMessage'));

?>

<form method="post">
    <textarea name="message"></textarea>
    <input type="submit" value="Enviar">
</form>
