<?php

namespace ukickeru\AccessControlBundle\Application\Presenters\Group;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use ukickeru\AccessControlBundle\Application\Presenters\Group\Routes\RoutesTransformer;
use ukickeru\AccessControlBundle\Application\Presenters\Group\Routes\RoutesType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use ukickeru\AccessControl\Model\Group;
use ukickeru\AccessControl\Model\Routes\ApplicationRoutesContainer;
use ukickeru\AccessControl\Model\User;

class GroupType extends AbstractType
{

    /** @var RoutesTransformer */
    private $routesTransformer;

    /** @var ApplicationRoutesContainer */
    private $applicationRoutesContainer;

    public function __construct(
        RoutesTransformer $routesTransformer,
        ApplicationRoutesContainer $applicationRoutesContainer
    )
    {
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
            ->add('parentGroup', EntityType::class, [
                'label' => 'Родительская группа',
                'class' => Group::class,
                'required' => false,
                'placeholder' => 'Выберите родительскую группу',
            ])
            ->add('availableRoutes', RoutesType::class, [
                'label' => 'Доступные маршруты',
            ])
            ->add('users', CollectionType::class, [
                'label' => 'Участники',
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => User::class,
                    'label' => 'Пользователь',
                    'placeholder' => 'Выберите пользователя',
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
            'data_class' => \ukickeru\AccessControl\UseCase\GroupDTO::class,
            'allow_extra_fields' => true
        ]);
    }
}
