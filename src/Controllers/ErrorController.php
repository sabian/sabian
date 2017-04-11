<?php
namespace Sabian\Controllers;

class ErrorController
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function actionShow(\Exception $e, $code)
    {
        return $this->twig->render('error.twig', [
            'code' => $code,
            'error' => $e,
        ]);
    }
}