<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TipoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plato')
            ->add('guardar', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
                'attr' => [
                    'class' => "btn-success"
                ],
                'label' => 'Guardar '
            ]);

        if (false === $options['nuevo']) {
            $builder
                ->add('borrar', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
                    'attr' => [
                        'class' => "btn-danger"
                    ],
                    'label' => 'Borrar '
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Tipo',
            'nuevo' => true
        ]);
    }
}