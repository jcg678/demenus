<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

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
            ->add('longitud',
                'Symfony\Component\Form\Extension\Core\Type\TextType',
                array(
                'constraints'=>array(
        new Regex(array(
            'pattern'=>'/^-?[0-9]+([,\.][0-9]*)?$/',
            'message'=>'Solo números  y el caracter -'
        ))))

            )
            ->add('latitud',
                'Symfony\Component\Form\Extension\Core\Type\TextType',
                array(
                    'constraints'=>array(
                        new Regex(array(
                            'pattern'=>'/^-?[0-9]+([,\.][0-9]*)?$/',
                            'message'=>'Solo números  y el caracter -'
                        ))))

            )
            
            
            ->add('localidad')
            ->add('provincia')
            ->add('telefono')
            ->add('cp',NumberType::class, array(
                    'label' => 'Código postal'
                )
                )
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