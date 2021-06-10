<?php

namespace ukickeru\AccessControlBundle\Application\Presenters\Group;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use ukickeru\AccessControl\UseCase\AccessControlUseCase;
use ukickeru\AccessControl\UseCase\GroupDTO;
use ukickeru\AccessControlBundle\Application\Presenters\Group\Routes\RoutesTransformer;
use ukickeru\AccessControlBundle\Application\Presenters\Group\Routes\RoutesType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use ukickeru\AccessControl\Model\Routes\ApplicationRoutesContainer;
use ukickeru\AccessControlBundle\Model\Group;
use ukickeru\AccessControlBundle\Model\User;

class GroupType extends AbstractType
{

    /** @var AccessControlUseCase */
    private $accessControlUseCase;

    /** @var RoutesTransformer */
    private $routesTransformer;

    /** @var ApplicationRoutesContainer */
    private $applicationRoutesContainer;

    public function __construct(
        AccessControlUseCase $accessControlUseCase,
        RoutesTransformer $routesTransformer,
        ApplicationRoutesContainer $applicationRoutesContainer
    )
    {
        $this->accessControlUseCase = $accessControlUseCase;
        $this->routesTransformer = $routesTransformer;
        $this->applicationRoutesContainer = $applicationRoutesContainer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('name', TextType::class, [
                'label' => 'Наименование',
            ])
            ->add('parentGroup', ChoiceType::class, [
                'label' => 'Родительская группа',
                'choice_label' => 'name',
                'choice_value' => 'id',
                'choices' => $this->accessControlUseCase->getAllGroups(),
                'required' => false,
                'placeholder' => 'Выберите родительскую группу',
            ])
            ->add('availableRoutes', RoutesType::class, [
                'label' => 'Доступные маршруты',
            ])
            ->add('users', CollectionType::class, [
                'label' => 'Участники',
                'entry_type' => ChoiceType::class,
                'entry_options' => [
                    'label' => 'Пользователь',
                    'choice_label' => 'username',
                    'choice_value' => 'id',
                    'choices' => $this->accessControlUseCase->getAllUsers()
                ],
                'by_reference' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => [
                    'class' => 'collection-field',
                ]
            ])
        ;

        $builder->get('availableRoutes')->addModelTransformer($this->routesTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GroupDTO::class,
            'allow_extra_fields' => true
        ]);
    }
}
