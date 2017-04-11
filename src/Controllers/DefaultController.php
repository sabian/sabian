<?php
namespace Sabian\Controllers;

use Silex\Application;
use Sabian\Models\Portfolio;
use Symfony\Component\HttpFoundation\Request;

class DefaultController
{
    /**
     * @var Portfolio
     */
    protected $portfolio;

    public function __construct(Portfolio $portfolio)
    {
        $this->portfolio = $portfolio;
    }

    public function actionIndex(Request $request, Application $app)
    {
        return $app['twig']->render('index.twig', [
            'lastItem' => $this->portfolio->getLatestItem(),
            'previousItems' => $this->portfolio->getPreviousItems()
        ]);
    }
}