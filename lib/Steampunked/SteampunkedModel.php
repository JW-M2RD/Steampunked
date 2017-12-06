<?php

/**
 * Created by PhpStorm.
 * User: hewhite
 * Date: 2/15/16
 * Time: 12:32 PM
 */

namespace Steampunked;


class SteampunkedModel
{
    // Turn Variables
    const PLAYER_1_TURN = 1;
    const PLAYER_2_TURN = 2;

    // Player Names
    private $player1Name = "Player 1";
    private $player2Name = "Player 2";

    // Current Turn
    private $turn = self::PLAYER_1_TURN;

    // GameOver Variable
    private $gameOver = false;

    private $firstMove = true;

    // Size of the grid
    private $gridSize = 6; // Default Size is 6

    // Grid Object
    private $grid;

    private $player1PipeList;
    private $player2PipeList;

    private $connectedToEnd = false;

    private $player1StartPipe = 0;
    private $player1EndPipe = 0;
    private $player2StartPipe = 0;
    private $player2EndPipe = 0;

    private $error = false;

    public function __construct($seed = null) {
        if ($seed === null) {
            $seed = time();
        }
        srand($seed);
    }

    /**
     * Goes through the grid and sets all tile flags to false
     */
    private function unSetFlags(){
        // Loop through rows
        for($i = 0; $i < $this->gridSize; $i++) {
            // Loop through columns
            for ($j = 0; $j < $this->gridSize + 2; $j++) {
                if ($this->grid[$i][$j] != null) {
                    $this->grid[$i][$j]->setFlag(false);
                }
            }
        }
    }

    public function buildPipeList($array){

        for($i = 0; $i < 5; $i++) {
            $random = rand(0, 3);
            if ($random == 0){
                array_push($array, new Tile(Tile::PIPE_CAP, 0, null));
            }
            elseif($random == 1){
                array_push($array, new Tile(Tile::PIPE_STRAIGHT, 0, null));
            }
            elseif($random == 2){
                array_push($array, new Tile(Tile::PIPE_NINETY, 0, null));
            }
            elseif($random == 3){
                array_push($array, new Tile(Tile::PIPE_TEE, 0, null));
            }
        }

        return $array;


    }

    public function discardPipe($index){
        $random = rand(0,3);

        if ($this->turn == self::PLAYER_1_TURN) {
            if($random == 0){
                $this->player1PipeList[$index] = new Tile(Tile::PIPE_CAP, 0, null);
            }elseif($random == 1){
                $this->player1PipeList[$index] = new Tile(Tile::PIPE_STRAIGHT, 0, null);
            }elseif($random == 2){
                $this->player1PipeList[$index] = new Tile(Tile::PIPE_NINETY, 0, null);
            }elseif($random == 3){
                $this->player1PipeList[$index] = new Tile(Tile::PIPE_TEE, 0, null);
            }
        }
        else {
            if($random == 0){
                $this->player2PipeList[$index] = new Tile(Tile::PIPE_CAP, 0, null);
            }elseif($random == 1){
                $this->player2PipeList[$index] = new Tile(Tile::PIPE_STRAIGHT, 0, null);
            }elseif($random == 2){
                $this->player2PipeList[$index] = new Tile(Tile::PIPE_NINETY, 0, null);
            }elseif($random == 3){
                $this->player2PipeList[$index] = new Tile(Tile::PIPE_TEE, 0, null);
            }
        }


    }

    public function buildGrid(){
        $this->grid = array();

        // Index for Start and end pipes
        $this->player1StartPipe = ($this->gridSize / 2) - 3;
        $this->player1EndPipe = ($this->gridSize / 2) - 2;
        $this->player2StartPipe = ($this->gridSize / 2) + 2;
        $this->player2EndPipe = ($this->gridSize / 2) + 1;

        for($i = 0; $i < $this->gridSize; $i++) {
            array_push($this->grid, array());
            // Loop for grid size + 2 because the start and end pipe index
            for ($j = 0; $j < $this->gridSize + 2; $j++) {

                // Player 1 Start Pipe
                if ($j == 0 && $i == $this->player1StartPipe) {
                    array_push($this->grid[$i], new Tile(Tile::PIPE_VALVE_CLOSED, 0, self::PLAYER_1_TURN));
                }
                // Player 2 Start Pipe
                else if($j == 0 && $i == $this->player2StartPipe) {
                    array_push($this->grid[$i], new Tile(Tile::PIPE_VALVE_CLOSED, 0, self::PLAYER_2_TURN));
                }
                // Player 1 End Gauge Top
                elseif ($j == $this->gridSize + 1 && $i == $this->player1EndPipe - 1 ) {
                    array_push($this->grid[$i], new Tile(Tile::PIPE_GAUGE_TOP_0, 0, self::PLAYER_1_TURN));
                }
                // Player 2 End Gauge Top
                elseif ($j == $this->gridSize + 1 && $i == $this->player2EndPipe - 1) {
                    array_push($this->grid[$i], new Tile(Tile::PIPE_GAUGE_TOP_0, 0, self::PLAYER_2_TURN));
                }
                // Player 1 End Gauge
                elseif ($j == $this->gridSize + 1 && $i == $this->player1EndPipe) {
                    array_push($this->grid[$i], new Tile(Tile::PIPE_GAUGE_BOTTOM_0, 0, self::PLAYER_1_TURN));
                }
                // Player 2 End Gauge
                elseif ($j == $this->gridSize + 1 && $i == $this->player2EndPipe) {
                    array_push($this->grid[$i], new Tile(Tile::PIPE_GAUGE_BOTTOM_0, 0, self::PLAYER_2_TURN));
                }
                else {
                    array_push($this->grid[$i], new Tile(Tile::PIPE_NULL, 0, null));
                }
            }
        }
        // Unsetting all the flags of each tile.
        $this->unSetFlags();
    }

    // Inserting the pipe at the indexes provided
    public function insertPipe($row, $col, $pipeIndex){

        if ($this->turn == self::PLAYER_1_TURN) {
            $pipe = $this->player1PipeList[$pipeIndex];
        }
        else {
            $pipe = $this->player2PipeList[$pipeIndex];
        }

        $pipeConnections = $pipe->getConnected();
        $canInsert = false;

        // If North tile can be connected
        if($row-1 >= 0){
            $northTile = $this->grid[$row-1][$col];
            $northConnections = $northTile->getConnected();

            if($northConnections["S"] && $pipeConnections["N"]){
                $canInsert = true;
            }
        }

        // If South tile can be connected
        if($row+1 < $this->gridSize){
            $southTile = $this->grid[$row+1][$col];
            $southConnections = $southTile->getConnected();

            if ($southConnections["N"] && $pipeConnections["S"]){
                $canInsert = true;
            }
        }

        // If East tile can be connected
        if($col+1 < $this->gridSize+2){
            $eastTile = $this->grid[$row][$col+1];
            $eastConnections = $eastTile->getConnected();

            if($eastConnections["W"] && $pipeConnections["E"]){
                $canInsert = true;
            }
        }

        // If West tile can be connected
        if($col-1 >= 0){
            $westTile = $this->grid[$row][$col-1];
            $westConnections = $westTile->getConnected();

            if($westConnections["E"] && $pipeConnections["W"]){
                $canInsert = true;
            }
        }


        // If it fulfilled any of the above conditions, it will not break the pipe logic and thus can be inserted
        if($canInsert){
            $this->grid[$row][$col] = new Tile($pipe->getPipeType(), $pipe->getRotation(), $this->getTurn());
            $this->discardPipe($pipeIndex);
            return true;
        }

        return false;

    }

    // Recursive function to add Leaks to the proper tiles using Depth First Search technique
    public function addLeaks($row, $col){
        // Set Flag on current tile
        $tile = $this->grid[$row][$col];
        if($tile != null) {
            $tile->setFlag(true);
            $tileConnected = $tile->getConnected();
        }
        else {
            return;
        }


        // If Connected in the North Direction
        if ($tileConnected["N"]){
            // Checks if the north tile is in the bounds
            if($row - 1 >= 0){
                $northTile = $this->grid[$row - 1][$col];

                // If Pipe type is null, add leak
                if ($northTile->getPipeType() == Tile::PIPE_NULL){
                    $this->grid[$row-1][$col] = new Tile(Tile::PIPE_LEAK, 3, $tile->getPlayer());
                }
                // If Pipe is not a leak, Call add Leaks recursively
                elseif ($northTile->getPipeType() != Tile::PIPE_LEAK && !$northTile->isFlag()){
                    $this->addLeaks($row-1, $col);
                }
            }
        }

        // If Connected in the South Direction
        if ($tileConnected["S"]){
            // Checks if the south tile is in the bounds
            if($row + 1 < $this->gridSize){
                $southTile = $this->grid[$row + 1][$col];

                // If Pipe type is null, add leak in the correct direction
                if ($southTile->getPipeType() == Tile::PIPE_NULL){
                    $this->grid[$row + 1][$col] = new Tile(Tile::PIPE_LEAK, 1, $tile->getPlayer());
                }
                // If Pipe is not a leak, Call add Leaks recursively
                elseif ($southTile->getPipeType() != Tile::PIPE_LEAK && !$southTile->isFlag()){
                    $this->addLeaks($row + 1, $col);
                }
            }
        }

        // If Connected in the East Direction
        if ($tileConnected["E"]){
            // Checks if the east tile is in the bounds
            if($col + 1 < $this->gridSize + 2){
                $eastTile = $this->grid[$row][$col + 1];

                // If Pipe type is null, add leak
                if ($eastTile->getPipeType() == Tile::PIPE_NULL){
                    $this->grid[$row][$col + 1] = new Tile(Tile::PIPE_LEAK, 0, $tile->getPlayer());
                }
                // If Pipe is not a leak, Call add Leaks recursively
                elseif ($eastTile->getPipeType() != Tile::PIPE_LEAK && !$eastTile->isFlag()){
                    $this->addLeaks($row, $col + 1);
                }
            }
        }

        // If Connected in the West Direction
        if ($tileConnected["W"]){
            // Checks if the west tile is in the bounds
            if($col - 1 >= 0){
                $westTile = $this->grid[$row][$col - 1];

                // If Pipe type is null, add leak
                if ($westTile->getPipeType() == Tile::PIPE_NULL){
                    $this->grid[$row][$col - 1] = new Tile(Tile::PIPE_LEAK, 2, $tile->getPlayer());
                }
                // If Pipe is not a leak and has not been visited, Call add Leaks recursively
                elseif ($westTile->getPipeType() != Tile::PIPE_LEAK && !$westTile->isFlag()){
                    $this->addLeaks($row, $col - 1);
                }
            }
        }
    }


    public function openValve(){
        if($this->getTurn() == self::PLAYER_1_TURN){
            $rowStart = $this->player1StartPipe;
            $this->grid[$this->player1StartPipe][0] = new Tile(Tile::PIPE_VALVE_OPEN, 0, $this->getTurn());
        }
        else{
            $rowStart = $this->player2StartPipe;
            $this->grid[$this->player2StartPipe][0] = new Tile(Tile::PIPE_VALVE_CLOSED, 0, $this->getTurn());
        }

        if($this->findLeaks($rowStart, 0, $this->getTurn())){
            // If true, the current player loses!
            return false;
        }
        // No Leaks found
        else{
            // If the pipe path connects to the end pipe, the current player wins
            if($this->connectedToEnd){
                if($this->getTurn() == self::PLAYER_1_TURN){
                    $this->grid[$this->player1EndPipe-1][$this->gridSize + 1] = new Tile(Tile::PIPE_GAUGE_TOP_190, 0, self::PLAYER_1_TURN);
                    $this->grid[$this->player1EndPipe][$this->gridSize + 1] = new Tile(Tile::PIPE_GAUGE_BOTTOM_190, 0, self::PLAYER_1_TURN);
                }
                else{
                    $this->grid[$this->player2EndPipe-1][$this->gridSize + 1] = new Tile(Tile::PIPE_GAUGE_TOP_190, 0, self::PLAYER_2_TURN);
                    $this->grid[$this->player2EndPipe][$this->gridSize + 1] = new Tile(Tile::PIPE_GAUGE_BOTTOM_190, 0, self::PLAYER_2_TURN);
                }
                return true;
            }
            // If not connected to the end pipe, the current player loses
            return false;
        }
    }

    public function findLeaks($row, $col, $player){
        // Set Flag on current tile
        $tile = $this->grid[$row][$col];
        $this->grid[$row][$col]->setFlag(true);
        $tileConnected = $tile->getConnected();

        if($tile->getPlayer() == $player && $tile->getPipeType() == Tile::PIPE_GAUGE_BOTTOM_0){
            $this->connectedToEnd = true;
        }

        $leak = false;
        // If Connected in the North Direction
        if ($tileConnected["N"]){
            // Checks if the north tile is in the bounds
            if($row - 1 >= 0){
                $northTile = $this->grid[$row - 1][$col];

                // If Pipe type is null, add leak
                if ($northTile->getPipeType() == Tile::PIPE_LEAK){
                    $leak = true;
                    return $leak;
                }
                // If Pipe is not a leak, Call add Leaks recursively
                elseif ($northTile->getPipeType() != Tile::PIPE_LEAK && !$northTile->isFlag()){
                    $leak = $this->findLeaks($row - 1, $col, $player);
                }
            }
        }

        // If Connected in the South Direction
        if ($tileConnected["S"]){
            // Checks if the south tile is in the bounds
            if($row + 1 < $this->gridSize){
                $southTile = $this->grid[$row + 1][$col];

                // If Pipe type is null, add leak in the correct direction
                if ($southTile->getPipeType() == Tile::PIPE_LEAK){
                    $leak = true;
                    return $leak;
                }
                // If Pipe is not a leak, Call add Leaks recursively
                elseif ($southTile->getPipeType() != Tile::PIPE_LEAK && !$southTile->isFlag()){
                    $leak = $this->findLeaks($row + 1, $col, $player);
                }
            }
        }

        // If Connected in the East Direction
        if ($tileConnected["E"]){
            // Checks if the east tile is in the bounds
            if($col + 1 < $this->gridSize + 2){
                $eastTile = $this->grid[$row][$col + 1];

                // If Pipe type is null, add leak
                if ($eastTile->getPipeType() == Tile::PIPE_LEAK){
                    $leak = true;
                    return $leak;
                }
                // If Pipe is not a leak, Call add Leaks recursively
                elseif ($eastTile->getPipeType() != Tile::PIPE_LEAK && !$eastTile->isFlag()){
                    $leak = $this->findLeaks($row, $col + 1, $player);
                }
            }
        }

        // If Connected in the West Direction
        if ($tileConnected["W"]){
            // Checks if the west tile is in the bounds
            if($col - 1 >= 0){
                $westTile = $this->grid[$row][$col - 1];

                // If Pipe type is null, add leak
                if ($westTile->getPipeType() == Tile::PIPE_LEAK){
                    $leak = true;
                    return $leak;
                }
                // If Pipe is not a leak and has not been visited, Call add Leaks recursively
                elseif ($westTile->getPipeType() != Tile::PIPE_LEAK && !$westTile->isFlag()){
                    $leak = $this->findLeaks($row, $col - 1, $player);
                }
            }
        }
        return $leak;
    }

    /**
     * @return boolean
     */
    public function isGameOver()
    {
        return $this->gameOver;
    }

    /**
     * @param boolean $gameOver
     */
    public function setGameOver($gameOver)
    {
        $this->gameOver = $gameOver;
    }


    public function setPlayerNames($player1, $player2){
        $this->player1Name = $player1;
        $this->player2Name = $player2;
    }

    public function giveUp(){
        $this->switchTurns();
        $this->setGameOver(true);
    }

    public function reset(){
        $this->turn = self::PLAYER_1_TURN;
        $this->setGameOver(false);
        $this->connectedToEnd = false;
    }


    /**
     * @return int
     */
    public function getTurn()
    {
        return $this->turn;
    }

    public function switchTurns(){
        if($this->turn == self::PLAYER_1_TURN){
            $this->turn = self::PLAYER_2_TURN;
        }
        else{
            $this->turn = self::PLAYER_1_TURN;
        }

        $this->addLeaks($this->player1StartPipe, 0);
        $this->addLeaks($this->player2StartPipe, 0);
        $this->unSetFlags();
    }

    /**
     * @return int
     */
    public function getGridSize()
    {
        return $this->gridSize;
    }

    /**
     * @param int $gridSize
     */
    public function setGridSize($gridSize)
    {
        if(!is_numeric($gridSize)) {
            $gridSize = 6;
        }
        $this->gridSize = $gridSize;
        $this->reset();
        $this->buildGrid();

        $temp_array = array();
        $this->player1PipeList = $this->buildPipeList($temp_array);
        $this->player2PipeList = $this->buildPipeList($temp_array);

        // Adding leaks to player 1's pipe list
        $this->addLeaks($this->player1StartPipe, 0);
        // Adding leaks to player 2's pipe list
        $this->addLeaks($this->player2StartPipe, 0);
        //Set All flags of tiles to false after checking for leaks
        $this->unSetFlags();
    }

    /**
     * @return mixed
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @return mixed
     */
    public function getPipeList()
    {
        if ($this->turn == self::PLAYER_1_TURN) {
            return $this->player1PipeList;
        }
        return $this->player2PipeList;

    }

    /**
     * @return mixed
     */
    public function getPlayer2Name()
    {
        return $this->player2Name;
    }

    /**
     * @return mixed
     */
    public function getPlayer1Name()
    {
        return $this->player1Name;
    }


    public function rotatePipe($pipeIndex) {
        if ($this->turn == self::PLAYER_1_TURN) {
            $this->player1PipeList[$pipeIndex]->rotate(1); // number of times to perform rotation...
        }
        else {
            $this->player2PipeList[$pipeIndex]->rotate(1); // number of times to perform rotation...
        }
    }

    public function getError() {
        return $this->error;
    }

    public function setError($value) {
        $this->error = $value;
    }

    public function getPlayer1PipeList() {
        return $this->player1PipeList;
    }

    public function getPlayer2PipeList() {
        return $this->player2PipeList;
    }

    /**
     * @param boolean $firstMove
     */
    public function setFirstMove($firstMove)
    {
        $this->firstMove = $firstMove;
    }



}