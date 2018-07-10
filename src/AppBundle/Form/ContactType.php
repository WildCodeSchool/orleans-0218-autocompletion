<?php

namespace AppBundle\Form;

use AppBundle\Entity\Town;
use AppBundle\Form\DataTransformer\TownStringToEntityTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    private $townTransformer;

    public function __construct(TownStringToEntityTransformer $townTransformer)
    {
        $this->townTransformer = $townTransformer;
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName')
            ->add('firstName')
            ->add('address')
            ->add('postalCode')
            ->add('town', TextType::class, [
                'attr'         => ['autocomplete' => 'off'],
                'invalid_message' => 'That is not a valid town',
            ]);

        $builder->get('town')
            ->addModelTransformer($this->townTransformer);
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contact',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_contact';
    }


}
