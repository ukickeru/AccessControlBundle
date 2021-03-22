<?php

namespace ukickeru\AccessControlBundle\Application\Presenters\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ukickeru\AccessControlBundle\UseCase\AccessControlUseCase;
use ukickeru\AccessControlBundle\UseCase\ChangeAdminPermissionsDTO;
use ukickeru\AccessControlBundle\UseCase\UserDTO;
use ukickeru\AccessControlBundle\UseCase\UserRepositoryInterface;

class ChangeAdminPermissionsType extends AbstractType
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var AccessControlUseCase */
    private $useCase;

    public function __construct(UserRepositoryInterface $userRepository, AccessControlUseCase $useCase)
    {
        $this->userRepository = $userRepository;
        $this->useCase = $useCase;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newAdminId', EntityType::class, [
                'label' => 'Пользователь',
                'placeholder' => 'Выберите пользователя, которому будут переданы права',
                'class' => UserDTO::class,
                'choice_loader' => $this->useCase->getAllUsers(),
                'choice_value' => 'id',
                'choice_label' => 'userName'
            ])
            ->add('confirmed', CheckboxType::class, [
                'label' => 'Подтвердите передачу прав'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangeAdminPermissionsDTO::class,
        ]);
    }

}