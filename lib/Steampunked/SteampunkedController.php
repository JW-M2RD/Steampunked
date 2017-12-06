<?php

/**
 * Created by PhpStorm.
 * User: hewhite
 * Date: 2/15/16
 * Time: 12:29 PM
 */

namespace Steampunked;

class SteampunkedController
{
    private $page = 'game.php';
    private $reset = false;         // True if we need to reset the game
    private $model;
    private $result = "";

    private $redirect = true;

    /**
     * Constructor
     * @param SteampunkedModel $model The SteampunkedModel object
     * @param $post $_POST array
     */
    public function __construct(SteampunkedModel $model, $post) {
        $this->model = $model;

        if($model->isGameOver()) {
            // only respond to play again and new game buttons
            if(isset($post['replay'])){
                $this->redirect = false;
                $this->model->reset();
                $this->model->setGridSize($this->model->getGridSize());
                $this->buildResult();
            }
            if(isset($post['new-game'])){
                $this->reset = true;
            }
            return;
        }

        // new game
        if (isset($post['player1Name']) && isset($post['player2Name'])) {
            $this->model->setPlayerNames($post['player1Name'],$post['player2Name']);
        }
        if(isset($post['gridSize'])){
            $this->model->setGridSize($post['gridSize']);
        }

        if(isset($post['give-up'])){
            $this->redirect = false;
            $this->model->setError(false);
            $this->model->giveUp();
            $this->buildResult();
        } else if(isset($post['discard']) && isset($post['pipe'])){
            $this->model->discardPipe($post['pipe']);
            $this->model->switchTurns();
            $this->redirect = false;
            $this->buildResult();
        } else if(isset($post['rotate']) && isset($post['pipe'])){
            $this->model->setError(false);
            $this->model->rotatePipe($post['pipe']);
            $this->redirect = false;
            $this->buildResult();
        } else if(isset($post['open-valve'])){
            $this->redirect = false;
            $this->model->setError(false);
            $this->model->setGameOver(true);
            if(!$this->model->openValve()) {
                $this->model->giveUp();
            }
            $this->buildResult();
        } else if(isset($post['insert']) && isset($post['pipe'])){
            $this->redirect = false;
            $index = explode(',', $post['insert']);
            if ($this->model->insertPipe(intval($index[0]), intval($index[1]), $post['pipe'])) {
                $this->model->switchTurns();
                $this->model->setError(false);
                $this->buildResult();
            }
            else {
                $this->model->setError(true);
                $this->buildResult();
            }
        } else if(isset($post['how-to-play'])){
            $this->reset = true;
            $this->page = 'introduction.php';
        }
    }

    /**
     * Get the value of reset
     * @return reset
     */
    public function isReset() {
        return $this->reset;
    }

    /**
     * Get the next page to redirect to
     * @return page
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * Get the resulting HTML
     * @return result
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * Get the resulting HTML
     * @return result
     */
    public function getIsRedirect() {
        return $this->redirect;
    }

    public function buildResult() {
        // get new game and pass to Game.js
        $view = new SteampunkedView($this->model);
        $grid = $view->grid();
        $pipeOptions = $view->pipeOptions();
        $turnMessage = $view->turnMessage();
        $error = $view->getError();
        $winner = $view->getWinner();
        $winnerOptions = $view->winnerOptions();
        $this->result = json_encode(array('grid' => $grid, 'pipeOptions' => $pipeOptions, 'turnMessage' => $turnMessage, 'error' => $error, 'winner' =>$winner, 'winnerOptions'=>$winnerOptions));
    }

}