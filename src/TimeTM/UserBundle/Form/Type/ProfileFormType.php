<?php

namespace TimeTM\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProfileFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {

		// add your custom field

        $builder->add('theme', EntityType::class, array(
            // query choices from this entity
            'class' => 'TimeTMCoreBundle:Theme',

            // use the User.username property as the visible option string
            'choice_label' => 'name',

            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            'expanded' => true,

            'label' => 'profile.theme'
        ));

        $builder->add('language', EntityType::class, array(
            // query choices from this entity
            'class' => 'TimeTMCoreBundle:Language',

            // use the User.username property as the visible option string
            'choice_label' => 'name',

            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            'expanded' => true,

            'label' => 'profile.language'
        ));


	}

	public function getParent()
	{
		return 'FOS\UserBundle\Form\Type\ProfileFormType';
	}

	public function getBlockPrefix() {

		return 'timetm_user_profile';
	}
}
