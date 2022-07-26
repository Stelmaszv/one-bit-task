<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Forms\Form;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="app_main")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(Form::class, null);
        return $this->render('main/index.html.twig', [
            'controller_name' => 'ConferenceController',
            'form'=> $form->createView()
        ]);
    }
}
