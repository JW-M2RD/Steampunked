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
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="site.con.js"></script>
    <script>

        var game;
        $(document).ready(function() {
            game = new Game("form");
        });

        /**
         * Initialize monitoring for a server push command.
         * @param key Key we will receive.
         */
        function pushInit(key) {
            var conn = new WebSocket('ws://webdev.cse.msu.edu:8079');
            conn.onopen = function (e) {
                console.log("Connection to push established!");
                conn.send(key);
            };

            conn.onmessage = function (e) {
                try {
                    var msg = JSON.parse(e.data);
                    if (msg.cmd === "reload") {
                        location.reload();
                    }
                } catch (e) {
                }
            };
        }

        pushInit("somekey");
    </script>
</head>
<body>
<div id="logo" align="center">
    <?php echo $view->header(); ?>
</div>
    <form>
        <div align="center">
            <div id="grid" class="game">
                <?php echo $view->grid(); ?>
            </div>

            <p id="turnMessage" class="message">
                <?php echo $view->turnMessage(); ?>
            </p>

            <p id="error" class="error">
                <?php echo $view->getError(); ?>
            </p>


            <div id="pipeOptions" align="center">
                <?php echo $view->pipeOptions(); ?>
            </div>

            <?php echo $view->buttonOptions(); ?>

            <div id="winnerOptions">
                <?php echo $view->winnerOptions(); ?>
            </div>

        </div>
    </form>
</body>
</html>
