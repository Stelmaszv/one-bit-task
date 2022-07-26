<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Forms\Form;
use Symfony\Component\Form\Form as SymfonyForm;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="app_main")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(Form::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            return $this->submit($form);
        }else{
            return $this->no_submit($form);
        }
    }

    private function no_submit(SymfonyForm $form) : Response{
        return $this->render('main/index.html.twig', [
            'controller_name' => 'ConferenceController',
            'form'=> $form->createView(),
            'result'=>'d'
        ]);
    }

    private function submit(SymfonyForm $form) : Response{
        return $this->render('main/index.html.twig', [
            'controller_name' => 'ConferenceController',
            'form'=> $form->createView(),
            'result'=>'F'
        ]);
    }


}
