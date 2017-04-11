<?php
/**
 * @var Silex\Application $app
 */

$app->get('/', 'default.controller:actionIndex')->bind('homepage');

$app->get('/portfolio/', 'portfolio.controller:actionIndex')->bind('portfolio');

$app->get('/portfolio/{id}/', 'portfolio.controller:actionView')->assert('id', '\d+')->bind('portfolio.item');

$app->get('/testimonials/', 'testimonial.controller:actionIndex')->bind('testimonials');

$app->get('/sitemap.xml', 'sitemap.controller:actionIndex')->bind('sitemap');

$app->match('/contacts/', 'contacts.controller:actionIndex')->bind('contacts');

$app->error('error.controller:actionShow');