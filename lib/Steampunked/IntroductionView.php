<?php
/**
 * Created by PhpStorm.
 * User: hewhite
 * Date: 2/24
 * Time: 1:14 PM
 */

namespace Steampunked;


class IntroductionView
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

    public function intro(){
        $html = <<<HTML

    <p>The object of the game is to place pipe that will connect a steam source to a steam engine so it can power your airship. Of course, you want to do this before your opponent does. The winner is the first one to connect steam to their engine with no leaks and turn on the valve.</p>
    <p>You always have five available pipes. Each turn you can select one of the pipes using the radio button next to it. You can rotate it as many times as you like, then click on an open space that extends your pipe. You can only add a pipe where it will connect to their pipes. No other locations will work.</p>
    <p>A viable spot for your pipe will turn green when you hover over it with your mouse.</p>
    <p>If you press discard, the selected pipe section is discarded and your turn ends.</p>
    <p>If you press give up, the turn ends and you surrender to your opponent.</p>
    <p>If you have a completely connected pipe, you may press Open Valve to turn on the steam. If there is a leak in the pipe, you lose. If you have successfully connected the source to the destination, you win.</p>
HTML;

        return $html;
    }

    public function introOptions() {
        $html = '<p><a href="index.php" name="new-game" value="Back to Home">Back to Home</a></p>';
        return $html;
    }

    public function team() {
        $html = '<p class="team-title">Team Gizmologists</p>';
        $html .= '<p>Jie Wan</p>';
        $html .= '<p>Aasiruddin Walajahi</p>';
        $html .= '<p>Hannah White</p>';
        return $html;
    }

    private $steampunked;	// Steampunked object
}