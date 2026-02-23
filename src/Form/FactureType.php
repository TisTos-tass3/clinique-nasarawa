<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Entity\Facture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant')
            ->add('dateEmission', null, [
                'widget' => 'single_text',
            ])
            ->add('datePaiement', null, [
                'widget' => 'single_text',
            ])
            ->add('statutPaiement')
            ->add('modePaiement')
            ->add('consultation', EntityType::class, [
                'class' => Consultation::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
