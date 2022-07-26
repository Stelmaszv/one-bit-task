<?php
namespace App\Forms;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('data',DateType::class,[
            'years'    => range(date('Y')-22, date('Y')),
            'label'    => 'Podaj Date',
            'required' => true
        ])
        ->add('save',SubmitType::class,['label' => 'Pokaż']);
    }
}