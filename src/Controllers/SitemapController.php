<?php
namespace Sabian\Controllers;

use Silex\Application;
use Sabian\Models\Portfolio;
use TM\Service\SitemapGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

class SitemapController
{
    /**
     * @var Portfolio
     */
    protected $portfolio;

    /**
     * @var SitemapGenerator
     */
    protected $sitemap;

    /**
     * @var UrlGenerator
     */
    protected $url;

    public function __construct(SitemapGenerator $sitemap, Portfolio $portfolio, UrlGenerator $url)
    {
        $this->sitemap = $sitemap;
        $this->portfolio = $portfolio;
        $this->url = $url;
    }

    public function actionIndex(Request $request, Application $app)
    {
        $host = $request->getSchemeAndHttpHost();

        $this->sitemap->addEntry($host . $this->url->generate('homepage'), 1, 'yearly');
        $this->sitemap->addEntry($host . $this->url->generate('testimonials'), 0.8, 'monthly');
        $this->sitemap->addEntry($host . $this->url->generate('contacts'), 0.8, 'monthly');

        foreach ($this->portfolio->getAllItems() as $entity) {
            $entityLoc = $this->url->generate('portfolio.item', ['id' => $entity['id']]);
            $this->sitemap->addEntry($host . $entityLoc, 0.8, 'monthly');
        }

        return $this->sitemap->generate();
    }
}