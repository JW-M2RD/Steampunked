<?php
/**
 * Created by PhpStorm.
 * User: hewhite
 * Date: 2/12/16
 * Time: 3:43 PM
 */

require __DIR__ . "/../../vendor/autoload.php";

class SteampunkedViewTest extends \PHPUnit_Framework_TestCase
{
    public function test_construct()
    {
        $model = new Steampunked\SteampunkedModel();
        $view = new Steampunked\SteampunkedView($model);

        $this->assertInstanceOf('Steampunked\SteampunkedView', $view);
    }

    public function test_gridSize()
    {
        $model = new Steampunked\SteampunkedModel();
        $view = new Steampunked\SteampunkedView($model);

        $this->assertEquals($view->getSteampunked()->getGridSize(), 6); // default grid size

        $model->setGridSize(10);
        $this->assertEquals($view->getSteampunked()->getGridSize(), 10); // default grid size
        $model->setGridSize(20);
        $this->assertEquals($view->getSteampunked()->getGridSize(), 20); // default grid size
    }

    public function test_winnerOptions()
    {
        $model = new Steampunked\SteampunkedModel();
        $view = new Steampunked\SteampunkedView($model);

        $options = $view->winnerOptions();
        $this->assertContains("Play Again", $options);
        $this->assertContains("New Game", $options);

    }
//    public function test_tuenMessage()
//    {
//        $model = new Steampunked\SteampunkedModel();
//        $view = new Steampunked\SteampunkedView($model);
//
//        $turn = $model->getTurn(PLAYER_1_TURN);
//        $view=$turn->isGameOver();
//        $this->assertContains('Wins!',$view);
//    }

    public function test_getWinner()
    {
        $model = new Steampunked\SteampunkedModel();

        $view = new Steampunked\SteampunkedView($model);
        $win=$view->getWinner();
        $this->assertContains('Wins', $win);


    }

    public function test_buttons()
    {
        $model = new Steampunked\SteampunkedModel();
        $view = new Steampunked\SteampunkedView($model);

        $buttons = $view->buttonOptions();
        $this->assertContains("Rotate", $buttons);
        $this->assertContains("Discard", $buttons);
        $this->assertContains("Open Valve", $buttons);
        $this->assertContains("Give Up", $buttons);
    }



}