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

// echo message unless cmd == giveup or open valve (maybe set a variable to test when to get page?
if($controller->getIsRedirect()) {
    header('Location: '.$controller->getPage());
    exit;
}
else {
    echo $controller->getResult();
}
exit;