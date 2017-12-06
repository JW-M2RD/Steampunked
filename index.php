<?php
require __DIR__ . '/lib/steampunked.inc.php';
$view = new Steampunked\IndexView($steampunked);
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Steampunked</title>
        <link href="style.css" type="text/css" rel="stylesheet" />

    </head>
    <body>
        <div id="logo">
            <?php echo $view->header(); ?>
        </div>
        <div id="form" align="center">
            <?php echo $view->form(); ?>
        </div>
    </body>
</html>
