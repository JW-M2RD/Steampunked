<?php
/**
 * Created by PhpStorm.
 * User: Aasir
 * Date: 2/17/16
 * Time: 10:37 AM
 */

require __DIR__ . "/../../vendor/autoload.php";


class SteampunkedModelTest extends \PHPUnit_Framework_TestCase
{
    public function test_construct()
    {
        $model = new Steampunked\SteampunkedModel();
        $this->assertInstanceOf('Steampunked\SteampunkedModel', $model);
    }

    public function test_seed_construct()
    {
        $model = new Steampunked\SteampunkedModel(52);
        $this->assertInstanceOf('Steampunked\SteampunkedModel', $model);
    }

    public function test_unsetFlags()
    {
        $model = new Steampunked\SteampunkedModel();
        $this->assertInstanceOf('Steampunked\SteampunkedModel', $model);

        $model->buildGrid();
        $grid = $model->getGrid();

        $tile = $grid[0][0];
        $tile->setFlag(true);

        $this->assertTrue($tile->isFlag());

        $model->switchTurns(); // this unsets flags on the grid
        $grid = $model->getGrid();
        $tile = $grid[0][0];
        $this->assertFalse($tile->isFlag());

    }

    public function test_discardPipe() {
        $model = new Steampunked\SteampunkedModel(1); // seed = 1
        $model->setGridSize(6); // invokes build grid

        $player1pipes = $model->getPlayer1PipeList();
        $pipe = $player1pipes[0];
        $this->assertEquals(Steampunked\Tile::IMG_PIPE_TEE_ESW, $pipe->getImageClass());

        $model->discardPipe(0);
        $player1pipes = $model->getPlayer1PipeList();
        $pipe = $player1pipes[0];
        $this->assertEquals(Steampunked\Tile::IMG_PIPE_STRAIGHT_H, $pipe->getImageClass());
    }

    public function test_gridSize(){
        $model = new Steampunked\SteampunkedModel();
        $model->setGridSize(10);

        $this->assertEquals(10, $model->getGridSize());

        $model->setGridSize("hi");
        $this->assertEquals(6, $model->getGridSize());

    }

    public function test_switchTurns(){
        $model = new Steampunked\SteampunkedModel();

        $this->assertEquals($model::PLAYER_1_TURN, $model->getTurn());
        $model->switchTurns();
        $this->assertEquals($model::PLAYER_2_TURN, $model->getTurn());
    }

    // this indirectly tests buildPipeList
    public function test_buildGrid() {
        $model = new Steampunked\SteampunkedModel(1); // seed = 1
        new Steampunked\SteampunkedController($model, array('gridSize' => '6'));
        $player1pipes = $model->getPlayer1PipeList();
        $pipe = $player1pipes[0];
        $this->assertEquals(Steampunked\Tile::IMG_PIPE_TEE_ESW, $pipe->getImageClass());

        $player2pipes = $model->getPlayer2PipeList();
        $pipe = $player2pipes[0];
        $this->assertEquals(Steampunked\Tile::IMG_PIPE_CAP_E, $pipe->getImageClass());

    }

    public function test_insertPipe() {
        $model = new Steampunked\SteampunkedModel(1); // seed = 1
        $model->setGridSize(6);

        // verify pipe can be inserted as-is
        $this->assertTrue($model->insertPipe(0,1,0));

        // verify that inserted pipe appears on gameboard
        $grid = $model->getGrid();
        $tile = $grid[0][1];
        $this->assertEquals(Steampunked\Tile::IMG_PIPE_TEE_ESW, $tile->getImageClass());

        $model->switchTurns();

        // verify pipe cannot be inserted as-is
        $this->assertFalse($model->insertPipe(5,1,0));

    }

    // this indirectly tests insertPipe and findLeaks
    public function test_addLeaks() {
        $model = new Steampunked\SteampunkedModel(1); // seed = 1
        new Steampunked\SteampunkedController($model, array('gridSize' => '6'));

        // add pipe to the gameboard
        new Steampunked\SteampunkedController($model, array('insert' => '0,1', 'pipe' => '0'));

        $grid = $model->getGrid();
        $tile = $grid[0][2];
        $this->assertEquals(Steampunked\Tile::IMG_PIPE_LEAK_W, $tile->getImageClass());
    }

    public function test_openValve() {
        $model = new Steampunked\SteampunkedModel(52); // seed = 1
        $model->setGridSize(6);

        // verify pipe cannot be inserted as-is
        $this->assertFalse($model->openValve());
        $model->rotatePipe(2);
        $model->rotatePipe(2);
        // verify pipe can be inserted as-is
        $this->assertTrue($model->insertPipe(0,1,2));
        //$model->rotatePipe(2);
        //$model->rotatePipe(2);
        //$model->rotatePipe(2);
        //$this->assertTrue($model->insertPipe(1,1,2));
        /*$model->rotatePipe(4);
        $model->rotatePipe(4);
        $model->rotatePipe(4);
        $this->assertTrue($model->insertPipe(1,2,4));

        $this->assertTrue($model->insertPipe(1,3,0));
        //$this->assertTrue($model->insertPipe(1,4,0));
        //$this->assertTrue($model->insertPipe(1,3,0));
*/
    }

    public function test_getTurn() {
        $model = new Steampunked\SteampunkedModel();
        $this->assertEquals(Steampunked\SteampunkedModel::PLAYER_1_TURN, $model->getTurn());
    }

    public function test_giveUp() {
        $model = new Steampunked\SteampunkedModel();
        $this->assertEquals(Steampunked\SteampunkedModel::PLAYER_1_TURN, $model->getTurn());

        $model->giveUp();
        $this->assertEquals(Steampunked\SteampunkedModel::PLAYER_2_TURN, $model->getTurn());
        $this->assertTrue($model->isGameOver());
    }

    public function test_reset() {
        $model = new Steampunked\SteampunkedModel();
        $model->switchTurns();
        $this->assertEquals(Steampunked\SteampunkedModel::PLAYER_2_TURN, $model->getTurn());

        $model->reset();

        $this->assertEquals(Steampunked\SteampunkedModel::PLAYER_1_TURN, $model->getTurn());
        $this->assertFalse($model->isGameOver());
    }

}