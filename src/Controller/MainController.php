<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
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

    private function no_submit(SymfonyForm $form) : Response
    {
        return $this->render('main/nosubmit.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    private function submit(SymfonyForm $form) : Response
    {
        $this->rates_from_today=$this->set_data_for_rates_from_today();
        $client = HttpClient::create();
        $data_time=$form->getData()['data']->format('Y-m-d');
        $response = $client->request('GET', 'https://api.frankfurter.app/'.$data_time.'?base=PLN&symbols=EUR,USD,GBP,CZK');
        $this->datas_from_data_Indicated_date=$this->set_data($response->toArray(),$this->set_data_for_rates_from_today());
        return $this->render('main/submit.html.twig', [
            'results'=>$this->datas_from_data_Indicated_date,
            'date'   =>$data_time
        ]);
    }

    private function set_data_for_rates_from_today(){
        $today = new \DateTime();
        $data_time=$today->format('Y-m-d');
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.frankfurter.app/'.$data_time.'?base=PLN&symbols=EUR,USD,GBP,CZK');
        $data=$response->toArray();
        return $data['rates'];
    }

    private function set_data(array $data) :array
    {
        $set_data_for_rates_from_today=$this->set_data_for_rates_from_today();
        foreach($data['rates'] as $rate => $key){
            $get_today_rate=$this->get_today_rate($rate,$set_data_for_rates_from_today);
            $return_data[] = array(
                "Currency"          => $rate, 
                "Rate_from_today"   => $get_today_rate,
                "Indicated_date"    => $key,
                "Percentage_change" => $this->get_percentage_change($get_today_rate,$key)
            );
        }
        return $return_data;
    }

    private function get_percentage_change(float $today_rate,float $rate):int
    {
        $difference= $today_rate-$rate;
        return $difference*100/$today_rate;
    }

    private function get_today_rate(string $key,array $today_rate) :float
    {
        return $today_rate[$key];
    }

}
