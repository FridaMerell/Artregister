<?php

namespace App\Form;

use App\Entity\Taxon\Genus;
use App\Entity\Taxon\OrganismGroup;
use App\Entity\Taxon\Species;
use App\Form\Taxon\SpeciesAutocompleteField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FindSpeciesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Art', SpeciesAutocompleteField::class, [
                'placeholder' => 'Namn'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Species::class,
        ]);
    }
}
