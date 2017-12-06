<?php
/**
 * Created by PhpStorm.
 * User: hewhite
 * Date: 2/18/16
 * Time: 1:39 PM
 */

require __DIR__ . '/lib/steampunked.inc.php';

$controller = new Steampunked\SteampunkedController($steampunked, $_POST);
if($controller->isReset()) {
    unset($_SESSION[STEAMPUNKED_SESSION]);
}


header('Location: '.$controller->getPage());
exit;