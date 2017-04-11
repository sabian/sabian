<?php
namespace Sabian\Controllers;

use Silex\Application;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ContactsController
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    public function __construct(FormFactory $form, \Swift_Mailer $mailer)
    {
        $this->formFactory = $form;
        $this->mailer = $mailer;
    }

    public function actionIndex(Request $request, Application $app)
    {
        $data = [];

        $form = $this->formFactory->createBuilder('form', $data)
            ->add('name', 'text', [
                'label' => 'Имя',
                'required' => false,
                'attr' => [
                    'class' => 'form-group form-control',
                ],
                'constraints' => [new Assert\NotBlank(), new Assert\Length(['min' => 3])]
            ])
            ->add('email', 'text', [
                'label' => 'E-mail',
                'required' => false,
                'attr' => [
                    'class' => 'form-group form-control'
                ],
                'constraints' => [new Assert\NotBlank(), new Assert\Email()],
            ])
            ->add('comment', 'textarea', [
                'label' => 'Сообщение',
                'required' => false,
                'attr' => [
                    'class' => 'form-group form-control',
                    'placeholder' => 'Пожалуйста, опишите задачу, которую необходимо решить или приложите ссылку на ТЗ',
                    'rows' => 5,
                ],
                'constraints' => [new Assert\NotBlank(), new Assert\Length(['min' => 10])]
            ])
            ->add('address', 'hidden', [
                'constraints' => new Assert\Blank()
            ])
            ->add('submit', 'submit', [
                'label' => 'Отправить',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $message = \Swift_Message::newInstance()
                ->setSubject('')
                ->setFrom([''])
                ->setTo([''])
                ->setBody($app['twig']->render('_feedback_email.twig', [
                    'data' => $form->getData(),
                ]), 'text/html');

            if ($this->mailer->send($message)) {
                $app['session']->getFlashBag()->add('feedback-success', 'Ваше сообщение успешно отправлено.');
                return $app->redirect($app['url_generator']->generate('contacts'));
            } else {
                $app['session']->getFlashBag()->add('feedback-error', 'Извините, что-то пошло не так. Попробуйте отправить сообщение позднее.');
            }
        }

        return $app['twig']->render('contacts.twig', ['form' => $form->createView()]);
    }
}