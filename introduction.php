<?php
require __DIR__ . '/lib/steampunked.inc.php';
$view = new Steampunked\IntroductionView($steampunked);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>How to Play</title>
    <link href="introduction.css" type="text/css" rel="stylesheet" />

</head>
<body>
<div id="logo">
    <?php echo $view->header(); ?>
</div>
<div>
    <?php echo $view->intro(); ?>
</div>
<form action="game-post.php" method="post">
    <div align="center">
        <?php echo $view->introOptions(); ?>
    </div>
</form>
<div class="team">
    <?php echo $view->team(); ?>
</div>
</body>
</html>
