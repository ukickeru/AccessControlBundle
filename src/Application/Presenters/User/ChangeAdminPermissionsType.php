<?php

namespace ukickeru\AccessControlBundle\Application\Presenters\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ukickeru\AccessControl\Model\User;
use ukickeru\AccessControl\UseCase\AccessControlUseCase;
use ukickeru\AccessControl\UseCase\ChangeAdminPermissionsDTO;
use ukickeru\AccessControl\UseCase\UserDTO;
use ukickeru\AccessControl\UseCase\UserRepositoryInterface;

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
        $useCase = $this->useCase;

        $builder
            ->add('newAdmin', EntityType::class, [
                'label' => 'Пользователь',
                'placeholder' => 'Выберите пользователя, которому будут переданы права',
                'class' => User::class,
                'choice_loader' => new CallbackChoiceLoader(function() use ($useCase) {
                    return $useCase->getAllUsers();
                }),
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