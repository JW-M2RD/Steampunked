<?php
/**
 * Created by PhpStorm.
 * User: Aasir
 * Date: 2/17/16
 * Time: 1:14 PM
 */

namespace Steampunked;


class IndexView
{
    /** Constructor
     * @param $steampunked Steampunked object */
    public function __construct(SteampunkedModel $steampunked) {
        $this->steampunked = $steampunked;
    }


    public function header(){
        $html =  '<img src="images/title.png" alt="Steampunked Title">';
        return $html;
    }

    public function form(){
        $html = <<<HTML
        <fieldset>
        <legend>Start Menu</legend>
        <form method="post" action="game-post.php">
            <p>
                <label for="player1Name">Player 1 Name:</label>
                <input type="text" name="player1Name" id="player1Name" value="Player 1">
            </p>
            <p>
                <label for="player2Name">Player 2 Name:</label>
                <input type="text" name="player2Name" id="player2Name" value="Player 2">
            </p>

            <p>Select Grid Size:</p>
            <input type="radio" name="gridSize" id="gridSize" value="6" checked="checked"/>
            <label for="gridSize">6x6</label>

            <input type="radio" name="gridSize" id="gridSize" value="10"/>
            <label for="gridSize">10x10</label>

            <input type="radio" name="gridSize" id="gridSize" value="20"/>
            <label for="gridSize">20x20</label>
            <p><input type="submit" name="start" value="Start Game"></p>

            <p><input type="submit" name="how-to-play" value="How to Play"></p>
        </form>
    </fieldset>
HTML;

        return $html;
    }





    private $steampunked;	// Steampunked object
}