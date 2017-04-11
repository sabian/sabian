<?php
namespace Sabian\Controllers;

use Silex\Application;
use Sabian\Models\Testimonials;
use Symfony\Component\HttpFoundation\Request;

class TestimonialController
{
    protected $testimonials;

    public function __construct(Testimonials $testimonials)
    {
        $this->testimonials = $testimonials;
    }

    public function actionIndex(Request $request, Application $app)
    {
        return $app['twig']->render('testimonials.twig', [
            'items' => $this->testimonials->getAllItems()
        ]);
    }
}