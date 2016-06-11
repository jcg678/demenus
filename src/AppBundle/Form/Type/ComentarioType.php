<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComentarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('texto')
            
            
            ->add('guardar', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
                'attr' => [
                    'class' => "btn-success"
                ],
                'label' => 'Enviar'
            ]);

        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Comentario',
            'nuevo' => true
        ]);
    }
}