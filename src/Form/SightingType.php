<?php

namespace App\Form;

use App\Entity\Sighting;
use App\Entity\Taxon\Species;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SightingType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options): void{
		$builder
			->add('Location', TextType::class, [
				'label' => 'Plats'
			])
			->add('DateTime', DateTimeType::class, [
				'widget' => 'single_text',
				'label' => 'Tidpunkt'
			])
			->add('Comment', TextareaType::class, [
				'label' => 'Kommentar'
			])
			->add('Species', EntityType::class, [
				'autocomplete' => true,
				'class' => Species::class,
				'label' => 'Art'
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void{
		$resolver->setDefaults([
								   'data_class' => Sighting::class,
							   ]);
	}
}
