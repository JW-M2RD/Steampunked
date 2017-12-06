<?php
/**
 * Created by PhpStorm.
 * User: hewhite
 * Date: 2/12/16
 * Time: 3:43 PM
 */

require __DIR__ . "/../../vendor/autoload.php";

class IntroductionViewTest extends \PHPUnit_Framework_TestCase
{
    public function test_construct()
    {
        $model = new Steampunked\SteampunkedModel();
        $view = new Steampunked\IntroductionView($model);

        $this->assertInstanceOf('Steampunked\IntroductionView', $view);
    }


    public function test_buttons()
    {
        $model = new Steampunked\SteampunkedModel();
        $view = new Steampunked\IntroductionView($model);

        $buttons = $view->introOptions();
        $this->assertContains("Back to Home", $buttons);
    }



}