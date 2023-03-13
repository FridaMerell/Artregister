<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\Taxon\Species;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewCardType extends AbstractType {
	function buildForm(FormBuilderInterface $builder, array $options): void{
		$builder
			->add('Name', TextType::class)
			->add('Template', ChoiceType::class, [
				'choices' => [
					'sverigelistan' => 'Sverigelistan'
				]
			])
			->add('Users', EntityType::class, [
				'class' => User::class,
				'multiple' => true
			])
			->add('Start', DateType::class, [
				'widget' => 'single_text',
				'required' => false
			])
			->add('End', DateType::class, [
				'widget' => 'single_text', 'required' => false
			])
			->add('save', SubmitType::class);
	}
}