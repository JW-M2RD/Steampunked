<?php
/**
 * Created by PhpStorm.
 * User: Aasir
 * Date: 2/17/16
 * Time: 1:14 PM
 */

namespace Steampunked;


class SteampunkedView
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

    public function grid(){
        $grid = $this->steampunked->getGrid();

        $html = '';

        for($i = 0; $i < count($grid); $i++){
            $html .= '<div class="row">';
            for($j = 0; $j < count($grid[0]); $j++){
                $tile = $grid[$i][$j];

                // If not a leak, make it a normal div
                if ($tile->getPipeType() != Tile::PIPE_LEAK){
                    $html .= '<div class="cell ' . $tile->getImageClass() .'"></div>';
                }
                // If a leak, make it a submit button
                else{
                    // Input over leak only for the player with the current turn
                    if(($this->steampunked->getTurn() == SteampunkedModel::PLAYER_1_TURN && $grid[$i][$j]->getPlayer() == SteampunkedModel::PLAYER_1_TURN) ||
                        ($this->steampunked->getTurn() == SteampunkedModel::PLAYER_2_TURN && $grid[$i][$j]->getPlayer() == SteampunkedModel::PLAYER_2_TURN)) {
//                    $html .= '<div class="cell ' . $tile->getImageClass() .'">';
                        $html .= '<div class="cell">';
                        $html .= '<input class=" hover ' . $tile->getImageClass() . '" type="submit" name="insert" value="' . $i . ',' . $j . '">';
                        $html .= '</div>';
                    }
                    else{
                        $html .= '<div class="cell ' . $tile->getImageClass() .'"></div>';
                    }
                }
            }
            $html .= '</div>';
        }

        return $html;
    }

    public function turnMessage(){
        $html = '';
        if($this->steampunked->getTurn() == SteampunkedModel::PLAYER_1_TURN){
            if($this->steampunked->isGameOver()){
                $html .= $this->steampunked->getPlayer1Name();
                $html .= " Wins!";
            }
            else {
                $html .= $this->steampunked->getPlayer1Name();
                $html .= "'s turn";
            }
        }
        else{
            if($this->steampunked->isGameOver()){
                $html .= $this->steampunked->getPlayer2Name();
                $html .= " Wins!";
            }
            else {
                $html .= $this->steampunked->getPlayer2Name();
                $html .= "'s turn";
            }
        }
        return $html;

    }

    public function pipeOptions(){
        $pipe = $this->steampunked->getPipeList();
        $html = "";
        for($i = 0; $i < count($pipe); $i++) {
            $tile = $pipe[$i];
            $html .= '<label for="pipeOtions" ><img src="images/'.$tile->getImageClass().'.png" /> </label >
            <input type = "radio" name ="pipe" id="pipeOtions" value="'.$i.'"/>';
        }

        return $html;
    }

    public function buttonOptions(){
        //$html = '<form action="game-post.php" method="post">';

        // Gets the pipe options and radio buttons

        // Create the buttons
        $html ='<input class="button" type="submit" name="rotate" value="Rotate">
            <input class="button" type="submit" name="discard" value="Discard">
            <input class="button" type="submit" name="open-valve" value="Open Valve">
            <input class="button" type="submit" name="give-up" value="Give Up">
        ';

        $html .= '<div>';

        return $html;
    }

    public function getSteampunked() {
        return $this->steampunked;
    }

    public function getError() {
        if ($this->steampunked->getError()) {
            $html = 'Please correct the orientation of the pipe before inserting it.';
            return $html;
        }
        return;
    }

    public function getWinner() {

        if($this->steampunked->isGameOver()) {
            $html = "<p>";

            if($this->steampunked->getTurn() == SteampunkedModel::PLAYER_1_TURN){
                $html .= $this->steampunked->getPlayer1Name();
                $html .= " Wins!";
            }
            else {
                $html .= $this->steampunked->getPlayer2Name();
                $html .= " Wins!";
            }

            $html .= "</p>";
            return $html;
        }

    }

    public function winnerOptions() {

        if($this->steampunked->isGameOver()) {
            $html = '<p><input class="button" type="submit" name="replay" value="Play Again"></p>';
            $html .= '<p><input class=button" type="submit" name="new-game" value="New Game"></p>';
            return $html;
        }

    }


    private $steampunked;	// Steampunked object

}