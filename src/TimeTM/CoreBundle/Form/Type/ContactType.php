<?php

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Form for Contact CRUD
 *
 * @author André Friedli <a@frian.org>
 */
class ContactType extends AbstractType
{
    /**
     * create the form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname',  TextType::class)
            ->add('firstname', TextType::class,     array('required' => false))
            ->add('email',     TextType::class,     array('required' => false))
            ->add('phone',     TextType::class,     array('required' => false))
            ->add('company',   CheckboxType::class, array('required' => false))
            ->add('client',    CheckboxType::class, array('required' => false))
        ;
    }

    /**
     * configure OptionsResolverInterface
     *
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TimeTM\CoreBundle\Entity\Contact'
        ));
    }

    /**
     * get form name
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'timetm_contactbundle_contact';
    }
}
