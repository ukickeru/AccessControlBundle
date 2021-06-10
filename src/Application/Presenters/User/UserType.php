<?php

namespace ukickeru\AccessControlBundle\Application\Presenters\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use ukickeru\AccessControl\UseCase\AccessControlUseCase;
use ukickeru\AccessControl\UseCase\GroupDTO;
use ukickeru\AccessControl\UseCase\UserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use ukickeru\AccessControlBundle\Model\Group;

class UserType extends AbstractType
{
    private $accessControlUseCase;

    public function __construct(AccessControlUseCase $accessControlUseCase)
    {
        $this->accessControlUseCase = $accessControlUseCase;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id',HiddenType::class)
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
                'entry_type' => ChoiceType::class,
                'entry_options' => [
                    'label' => 'Группа',
                    'choice_label' => 'name',
                    'choice_value' => 'id',
                    'choices' => $this->accessControlUseCase->getAllGroups()
                ],
                'by_reference' => true,
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
