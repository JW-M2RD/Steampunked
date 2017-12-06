<?php
/**
 * Created by PhpStorm.
 * User: hewhite
 * Date: 2/12/16
 * Time: 3:43 PM
 */

require __DIR__ . "/../../vendor/autoload.php";

class SteampunkedControllerTest extends \PHPUnit_Framework_TestCase
{
    public function test_construct()
    {
        $model = new Steampunked\SteampunkedModel();
        $controller = new Steampunked\SteampunkedController($model, array('value' => 12));

        $this->assertInstanceOf('Steampunked\SteampunkedController', $controller);
        $this->assertEquals('game.php', $controller->getPage());
    }

    public function test_gridSize() {
        $model = new Steampunked\SteampunkedModel();
        $controller = new Steampunked\SteampunkedController($model, array('gridSize' => 6));
        $this->assertEquals(6, $model->getGridSize());
    }

    public function test_playerNames() {
        $model = new Steampunked\SteampunkedModel();
        $controller = new Steampunked\SteampunkedController($model, array('player1Name' => 'Joe', 'player2Name' => 'Blart'));
        $this->assertEquals('Joe', $model->getPlayer1Name());
        $this->assertEquals('Blart', $model->getPlayer2Name());
    }

    public function test_discard() {

        // setup the controller
        $model = new Steampunked\SteampunkedModel(1); // seed = 1
        $model->setGridSize(6);
        $controller = new Steampunked\SteampunkedController($model, array('value' => 'arbitrary'));

        $this->assertFalse($controller->isReset());
        $this->assertEquals('game.php', $controller->getPage());

        // get original pipe
        $player1pipes = $model->getPlayer1PipeList();
        $pipe = $player1pipes[0];
        $this->assertEquals(Steampunked\Tile::IMG_PIPE_TEE_ESW, $pipe->getImageClass());

        // get new pipe
        new Steampunked\SteampunkedController($model, array('discard' => 'arbitrary', 'pipe' => '0'));
        $player1pipes = $model->getPlayer1PipeList();
        $pipe = $player1pipes[0];
        $this->assertEquals(Steampunked\Tile::IMG_PIPE_STRAIGHT_H, $pipe->getImageClass());
    }

    public function test_rotate()
    {
        $model = new Steampunked\SteampunkedModel(1); // seed = 1
        $model->setGridSize(6);
        $controller = new Steampunked\SteampunkedController($model, array('value' => 'arbitrary'));

        $this->assertFalse($controller->isReset());
        $this->assertEquals('game.php', $controller->getPage());

        $player1pipes = $model->getPlayer1PipeList();
        $pipe = $player1pipes[0];
        $this->assertEquals(Steampunked\Tile::IMG_PIPE_TEE_ESW, $pipe->getImageClass());

        new Steampunked\SteampunkedController($model, array('rotate' => '90', 'pipe' => '0'));
        $player1pipes = $model->getPlayer1PipeList();
        $pipe = $player1pipes[0];
        $this->assertEquals(Steampunked\Tile::IMG_PIPE_TEE_SWN, $pipe->getImageClass());

    }

    public function test_giveUp()
    {
        $model = new Steampunked\SteampunkedModel();
        $controller = new Steampunked\SteampunkedController($model, array('give-up' => 'true'));
        $this->assertEquals('winner.php', $controller->getPage());
    }

    public function test_openValve() {
        // setup the controller
        $model = new Steampunked\SteampunkedModel(1); // seed = 1
        $model->setGridSize(6);
        $controller = new Steampunked\SteampunkedController($model, array('value' => 'arbitrary'));

        $controller2 = new Steampunked\SteampunkedController($model, array('open-valve' => 'arbitrary'));
        $this->assertFalse($model->getError());

        $this->assertEquals('winner.php', $controller2->getPage());

    }

    public function test_insertPipe() {
        // setup the controller
        $model = new Steampunked\SteampunkedModel(1); // seed = 1
        $model->setGridSize(6);
        $controller = new Steampunked\SteampunkedController($model, array('value' => 'arbitrary'));

        // get original pipe
        $player1pipes = $model->getPlayer1PipeList();
        $pipe = $player1pipes[0];
        $this->assertEquals(Steampunked\Tile::IMG_PIPE_TEE_ESW, $pipe->getImageClass());

        // get new pipe after adding it to the gameboard
        new Steampunked\SteampunkedController($model, array('insert' => '0,1', 'pipe' => '0'));
        $player1pipes = $model->getPlayer1PipeList();
        $pipe = $player1pipes[0];
        $this->assertEquals(Steampunked\Tile::IMG_PIPE_STRAIGHT_H, $pipe->getImageClass());
    }

    public function test_newGame() {
        $model = new Steampunked\SteampunkedModel(1); // seed = 1
        $controller = new Steampunked\SteampunkedController($model, array('new-game' => 'arbitrary'));
        $this->assertEquals('index.php', $controller->getPage());
        $this->assertTrue($controller->isReset());
    }

    public function test_howToPlay() {
        $model = new Steampunked\SteampunkedModel(1); // seed = 1
        $controller = new Steampunked\SteampunkedController($model, array('how-to-play' => 'arbitrary'));
        $this->assertEquals('introduction.php', $controller->getPage());
        $this->assertFalse($controller->isReset());
    }
}