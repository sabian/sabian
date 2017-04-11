<?php
namespace Sabian\Widgets;

use SimpleXMLElement;
use Silex\Application;

class RssWidget
{
    private $app;
    private $feedUrl;

    public function __construct(Application $app, $feedUrl)
    {
        $this->app = $app;
        $this->feedUrl = $feedUrl;
    }

    public function show()
    {
        if ($this->checkUrlStatus() && $feedData = $this->getFeedData()) {
            echo $this->app['twig']->render('rss-widget.twig', ['feedData' => $feedData]);
        }
    }

    /**
     * Check url status code
     *
     * @return bool
     */
    private function checkUrlStatus()
    {
        $request = curl_init($this->feedUrl);

        curl_setopt($request, CURLOPT_HEADER, true);
        curl_setopt($request, CURLOPT_NOBODY, true);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_TIMEOUT, 10);

        curl_exec($request);
        $httpCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);

        if ($httpCode >= 200 && $httpCode < 400) {
            return true;
        }

        return false;
    }

    /**
     * Get feed data
     *
     * @return bool|SimpleXMLElement
     */
    private function getFeedData()
    {
        libxml_use_internal_errors(true);

        $request = curl_init($this->feedUrl);

        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);

        $feed = curl_exec($request);

        curl_close($request);

        $data = simplexml_load_string($feed);

        if (count(libxml_get_errors()) > 0) {
            return false;
        }

        return $data;
    }
}