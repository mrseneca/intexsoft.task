<?php

namespace App\Form;

use App\Entity\Organization;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, [
                'label'=>'Фамилия'
            ])
            ->add('firstname', TextType::class, [
                'label'=>'Имя'
            ])
            ->add('middlename', TextType::class, [
                'label'=>'Отчество'
            ])
            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'Дата рождения'
            ])
            ->add('inn', TextType::class, [
                'label'=>'ИНН'
            ])
            ->add('snils', TextType::class, [
                'label'=>'СНИЛС'
            ])
            ->add('organization', EntityType::class, [
                'class'=>Organization::class
            ])
            ->add('save', SubmitType::class,[
                'attr'=>['class'=>'brn btn-success float-right'],
                'label'=>'Сохранить'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'org'=>''
        ]);
    }
}
