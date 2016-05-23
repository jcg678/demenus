<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('longitud','Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('latitud','Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('localidad')
            ->add('provincia')
            ->add('telefono')
            ->add('cp')
            ->add('guardar', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
                'attr' => [
                    'class' => "btn-success"
                ],
                'label' => 'Guardar cambios'
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
            'data_class' => 'AppBundle\Entity\Local',
            'nuevo' => true
        ]);
    }
}