<?php
namespace Sabian\Controllers;

use Silex\Application;
use Sabian\Models\Portfolio;
use Symfony\Component\HttpFoundation\Request;

class PortfolioController
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
        return $app['twig']->render('portfolio.twig', [
            'items' => $this->portfolio->getAllItems()
        ]);
    }

    public function actionView(Request $request, Application $app, $id)
    {
        $item = $this->portfolio->getItem($id);

        if(!$item) {
            $app->abort(404, 'Страница не найдена');
        }

        return $app['twig']->render('portfolio-item.twig', [
            'item' => $item
        ]);
    }
}