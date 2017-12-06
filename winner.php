<?php
require __DIR__ . '/lib/steampunked.inc.php';
$view = new Steampunked\SteampunkedView($steampunked);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Steampunked</title>
    <link href="game.css" type="text/css" rel="stylesheet" />

</head>
<body>
<div id="logo" align="center">
    <?php echo $view->header(); ?>
</div>
<div class="winner" align="center">
    <?php echo $view->getWinner(); ?>
</div>
<form action="game-post.php" method="post">
    <div align="center">
        <?php echo $view->winnerOptions(); ?>
    </div>
</form>
</body>
</html>