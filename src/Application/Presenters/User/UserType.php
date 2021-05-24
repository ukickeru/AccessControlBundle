<?php

namespace ukickeru\AccessControlBundle\Application\Presenters\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Security;
use ukickeru\AccessControl\Model\User;
use ukickeru\AccessControl\UseCase\UserDTO;
use ukickeru\AccessControl\Model\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserType extends AbstractType
{

    /** @var Security */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $builder
            ->add('id',HiddenType::class)
        ;

        $builder
            ->add('username', TextType::class, [
                'label' => 'Имя',
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Пароли должны совпадать.',
                'required' => true,
                'first_options'  => ['label' => 'Пароль'],
                'second_options' => ['label' => 'Повторите пароль'],
            ])
            ->add('groups', CollectionType::class, [
                'label' => 'Список групп',
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'label' => 'Группа',
                    'class' => Group::class
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => [
                    'class' => 'collection-field'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserDTO::class,
        ]);
    }
}
