<?php
namespace Evaluados\UserBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use UsuariosBundle\Entity\Usuario;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'users.fields.email']
            ])
            ->add('firstName', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'users.fields.first_name']
            ])
            ->add('lastName', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'users.fields.last_name']
            ])
            ->add('is_coordinator', CheckboxType::class, [
                'mapped' => false,
                'label' => 'users.fields.option_coordinator',
                'required' => false,
                'attr' => ['class' => 'filled-in chk-col-cyan', 'id' => 'permission']
            ])
            ->add('is_teacher', CheckboxType::class, [
                'mapped' => false,
                'label' => 'users.fields.option_teacher',
                'required' => false,
                'attr' => ['class' => 'filled-in chk-col-cyan', 'id' => 'permission']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'data_class' => Usuario::class,
            'translation_domain' => 'form'
        ));
    }
}