<?php
/**
 * Created by PhpStorm.
 * User: Aasir
 * Date: 2/17/16
 * Time: 1:30 AM
 */

namespace Steampunked;


class Tile
{

    // Constants for Pipe Types Before Rotations
    const PIPE_CAP = 0;
    const PIPE_LEAK = 1;
    const PIPE_NINETY = 2;
    const PIPE_STRAIGHT = 3;
    const PIPE_TEE = 4;
    const PIPE_GAUGE_BOTTOM_0 = 5;
    const PIPE_GAUGE_BOTTOM_190 = 6;
    const PIPE_GAUGE_TOP_0 = 7;
    const PIPE_GAUGE_TOP_190 = 8;
    const PIPE_VALVE_CLOSED = 9;
    const PIPE_VALVE_OPEN = 10;
    const PIPE_NULL = 11;


    // Image Links
    const IMG_PIPE_CAP_E = "cap-e";
    const IMG_PIPE_CAP_N = "cap-n";
    const IMG_PIPE_CAP_S = "cap-s";
    const IMG_PIPE_CAP_W = "cap-w";

    const IMG_PIPE_GAUGE_0 = "gauge-0";
    const IMG_PIPE_GAUGE_190 = "gauge-190";
    const IMG_PIPE_GAUGE_TOP_0 = "gauge-top-0";
    const IMG_PIPE_GAUGE_TOP_190 = "gauge-top-190";

    const IMG_PIPE_LEAK_E = "leak-e";
    const IMG_PIPE_LEAK_N = "leak-n";
    const IMG_PIPE_LEAK_S = "leak-s";
    const IMG_PIPE_LEAK_W = "leak-w";

    const IMG_PIPE_NINETY_ES = "ninety-es";
    const IMG_PIPE_NINETY_NE = "ninety-ne";
    const IMG_PIPE_NINETY_SW = "ninety-sw";
    const IMG_PIPE_NINETY_WN = "ninety-wn";

    const IMG_PIPE_STRAIGHT_H = "straight-h";
    const IMG_PIPE_STRAIGHT_V = "straight-v";

    const IMG_PIPE_TEE_ESW = "tee-esw";
    const IMG_PIPE_TEE_NES = "tee-nes";
    const IMG_PIPE_TEE_SWN = "tee-swn";
    const IMG_PIPE_TEE_WNE = "tee-wne";

    const IMG_PIPE_VALVE_CLOSED = "valve-closed";
    const IMG_PIPE_VALVE_OPEN  = "valve-open";

    const IMG_PIPE_NULL = "null";

    private $pipeType;
    private $flag = false;

    // A connect array to show if the pipe is connected to something.
    private $connected = array("N" => false, "E" => false, "S" => false, "W" => false);

    // Default rotation is E
    private $pipeRotation = 0;

    // Default player is no one.
    private $player = -1;

    /**
     * Pipe constructor.
     * @param mixed $pipeType $rotation $player
     */
    public function __construct($pipeType, $rotation, $player)
    {
        $this->pipeType = $pipeType;
        $this->player = $player;
        $this->pipeRotation = $rotation;

        // Set the default connections of pipe types
        if($this->pipeType == self::PIPE_CAP){
            $this->connected = array("N" => false, "E" => true, "S" => false, "W" => false);
        }
        elseif($this->pipeType == self::PIPE_LEAK){
            $this->connected = array("N" => false, "E" => false, "S" => false, "W" => true);
        }
        elseif($this->pipeType == self::PIPE_NINETY){
            $this->connected = array("N" => false, "E" => true, "S" => true, "W" => false);
        }
        elseif($this->pipeType == self::PIPE_STRAIGHT){
            $this->connected = array("N" => false, "E" => true, "S" => false, "W" => true);
        }
        elseif($this->pipeType == self::PIPE_TEE){
            $this->connected = array("N" => false, "E" => true, "S" => true, "W" => true);
        }
        elseif($this->pipeType == self::PIPE_VALVE_OPEN || $this->pipeType == self::PIPE_VALVE_CLOSED ){
            // Assuming its the first piece
            $this->connected = array("N" => false, "E" => true, "S" => false, "W" => false);
        }
        elseif($this->pipeType == self::PIPE_GAUGE_BOTTOM_0){
            $this->connected = array("N" => false, "E" => false, "S" => false, "W" => true);
        }
        else{
            $this->connected = array("N" => false, "E" => false, "S" => false, "W" => false);
        }

        // Rotate the connections if the pipe has a rotation
        $this->rotate($this->pipeRotation);
    }

    /**
     * Rotates the pipe
     * @param int $rotations No. of times to rotate the pipe
     */
    public function rotate($rotations){
        for($i = 0; $i < $rotations; $i++){
            $this->pipeRotation++;
            // If it goes over 4, wrap around
            if($this->pipeRotation >= 4){
                $this->pipeRotation = 0;
            }
            $swap = $this->connected["N"];
            $this->connected["N"] = $this->connected["W"];
            $this->connected["W"] = $this->connected["S"];
            $this->connected["S"] = $this->connected["E"];
            $this->connected["E"] = $swap;
        }
    }

    public function setFlag($f) {
        $this->flag = $f;
    }

    public function isFlag() {
        return $this->flag;
    }


    /**
     * @return int
     */
    public function getRotation()
    {
        return $this->pipeRotation;
    }

    /**
     * @param int $rotation
     */
    public function setRotation($rotation)
    {
        $this->pipeRotation = $rotation;
    }

    /**
     * @return int
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param int $player
     */
    public function setPlayer($player)
    {
        $this->player = $player;
    }

    /**
     * @return mixed
     */
    public function getPipeType()
    {
        return $this->pipeType;
    }

    /**
     * @return array
     */
    public function getConnected()
    {
        return $this->connected;
    }


    /**
     * Image for current pipe
     * @return string name of class for background image of pipe
     */
    public function getImageClass(){
        $north = $this->connected["N"];
        $east = $this->connected["E"];
        $south = $this->connected["S"];
        $west = $this->connected["W"];

        if($this->pipeType == self::PIPE_CAP){
            if($north){
                return self::IMG_PIPE_CAP_N;
            }
            elseif($east){
                return self::IMG_PIPE_CAP_E;
            }
            elseif($south){
                return self::IMG_PIPE_CAP_S;
            }
            else{
                return self::IMG_PIPE_CAP_W;
            }
        }
        elseif($this->pipeType == self::PIPE_LEAK){
            if($north){
                return self::IMG_PIPE_LEAK_N;
            }
            elseif($east){
                return self::IMG_PIPE_LEAK_E;
            }
            elseif($south){
                return self::IMG_PIPE_LEAK_S;
            }
            elseif($west){
                return self::IMG_PIPE_LEAK_W;
            }
        }
        elseif($this->pipeType == self::PIPE_NINETY){
            if($east && $south){
                return self::IMG_PIPE_NINETY_ES;
            }
            elseif($north && $east){
                return self::IMG_PIPE_NINETY_NE;
            }
            elseif($south && $west){
                return self::IMG_PIPE_NINETY_SW;
            }
            elseif($west && $north){
                return self::IMG_PIPE_NINETY_WN;
            }
        }
        elseif($this->pipeType == self::PIPE_STRAIGHT){
            if($east && $west){
                return self::IMG_PIPE_STRAIGHT_H;
            }
            elseif($north && $south){
                return self::IMG_PIPE_STRAIGHT_V;
            }
        }
        elseif($this->pipeType == self::PIPE_TEE){
            if($east && $south && $west){
                return self::IMG_PIPE_TEE_ESW;
            }
            elseif($north && $east && $south){
                return self::IMG_PIPE_TEE_NES;
            }
            elseif($south && $west && $north){
                return self::IMG_PIPE_TEE_SWN;
            }
            elseif($west && $north && $east){
                return self::IMG_PIPE_TEE_WNE;
            }
        }
        elseif($this->pipeType == self::PIPE_VALVE_OPEN){
            return self::IMG_PIPE_VALVE_OPEN;
        }
        elseif($this->pipeType == self::PIPE_VALVE_CLOSED){
            return self::IMG_PIPE_VALVE_CLOSED;
        }
        elseif($this->pipeType == self::PIPE_GAUGE_BOTTOM_0){
            return self::IMG_PIPE_GAUGE_0;
        }
        elseif($this->pipeType == self::PIPE_GAUGE_TOP_0){
            return self::IMG_PIPE_GAUGE_TOP_0;
        }
        elseif($this->pipeType == self::PIPE_GAUGE_BOTTOM_190){
            return self::IMG_PIPE_GAUGE_190;
        }
        elseif($this->pipeType == self::PIPE_GAUGE_TOP_190){
            return self::IMG_PIPE_GAUGE_TOP_190;
        }
        else {
            return self::IMG_PIPE_NULL;
        }
    }

}