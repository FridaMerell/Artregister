<?php

namespace App\Form\Taxon;

use App\Entity\Taxon\Species;
use App\Repository\Taxon\SpeciesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class SpeciesAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(defaults: [
            'class' => Species::class,
            'placeholder' => 'Choose a Species',
            'searchable_fields' => [
                'VernacularName',
                'ScientificName'
            ],
            'query_builder' => function (SpeciesRepository $speciesRepository) {
                return $speciesRepository->createQueryBuilder('species');
            },
            //'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
