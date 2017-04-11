<?php
/**
 * @var Silex\Application $app
 */

use Sabian\Models\Portfolio;
use Sabian\Widgets\RssWidget;
use Sabian\Models\Testimonials;
use Sabian\Controllers\ErrorController;
use Silex\Provider\FormServiceProvider;
use TM\Provider\SitemapServiceProvider;
use Sabian\Controllers\SitemapController;
use Sabian\Controllers\DefaultController;
use Silex\Provider\SessionServiceProvider;
use Sabian\Controllers\ContactsController;
use Sabian\Controllers\PortfolioController;
use Silex\Provider\ValidatorServiceProvider;
use Sabian\Controllers\TestimonialController;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../views',
    'twig.options' => [
        'cache' => __DIR__ . '/../cache',
    ],
]);

$app->register(new ServiceControllerServiceProvider());

$app->register(new SitemapServiceProvider());

$app->register(new UrlGeneratorServiceProvider());

$app->register(new FormServiceProvider());

$app->register(new ValidatorServiceProvider());

$app->register(new TranslationServiceProvider(), [
    'translator.domains' => [],
]);

$app->register(new SessionServiceProvider());

$app->register(new SwiftmailerServiceProvider());


$app['portfolio.repository'] = $app->share(function () {
    return new Portfolio();
});

$app['testimonial.repository'] = $app->share(function () {
    return new Testimonials();
});

$app['default.controller'] = $app->share(function () use ($app) {
    return new DefaultController($app['portfolio.repository']);
});

$app['portfolio.controller'] = $app->share(function () use ($app) {
    return new PortfolioController($app['portfolio.repository']);
});

$app['testimonial.controller'] = $app->share(function () use ($app) {
    return new TestimonialController($app['testimonial.repository']);
});

$app['sitemap.controller'] = $app->share(function () use ($app) {
    return new SitemapController($app['sitemap'], $app['portfolio.repository'], $app['url_generator']);
});

$app['error.controller'] = $app->share(function () use ($app) {
    return new ErrorController($app['twig']);
});

$app['contacts.controller'] = $app->share(function () use ($app) {
    return new ContactsController($app['form.factory'], $app['mailer']);
});

$app['debug'] = false;
$app['asset_path'] = '/assets';
$app['locale'] = 'ru';
$app['swiftmailer.options'] = [
    'host' => '',
    'port' => '',
    'username' => '',
    'password' => '',
    'encryption' => 'ssl',
    'auth_mode' => 'login'
];
$app['swiftmailer.use_spool'] = false;

$skills = [
    'PHP',
    'MySQL',
    'Yii framework',
    'PHPUnit',
    'Codeception',
    'Twig',
    'Smarty',
    'HTML',
    'CSS/SASS',
    'JavaScript',
    'Silex',
    'JQuery',
    'Redis',
    'Memcache',
    'Guzzle',
    'Mercurial',
    'Git',
    'Socket.io/Elephant.io',
    'XML/XSL',
    'Node.js',
    'PostgreSQL',
];

$app['twig']->addGlobal('layout', $app['twig']->loadTemplate('layout.twig'));
$app['twig']->addGlobal('testimonial', $app['testimonial.repository']->random());
$app['twig']->addGlobal('skills', $skills);
$app['twig']->addGlobal('RssWidget', new RssWidget($app, ''));
