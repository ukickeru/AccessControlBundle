<?php

namespace ukickeru\AccessControlBundle\Application\Presenters\Group\Routes;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use ukickeru\AccessControlBundle\Model\Routes\ApplicationRoutesContainer;
use ukickeru\AccessControlBundle\Model\Routes\Route;

class RoutesType extends AbstractType
{

    /** @var ApplicationRoutesContainer */
    private $applicationRoutesContainer;

    /** @var ApplicationRoutesTransformer */
    private $applicationRoutesTransformer;

    public function __construct(
        ApplicationRoutesContainer $applicationRoutesContainer,
        ApplicationRoutesTransformer $applicationRoutesTransformer
    )
    {
        $this->applicationRoutesContainer = $applicationRoutesContainer;
        $this->applicationRoutesTransformer = $applicationRoutesTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('applicationRoutes',ChoiceType::class, [
                'label' => 'Существующие машруты приложения',
                'choices' => $this->applicationRoutesContainer->getRoutes(),
                'choice_label' => function(?Route $route) {
                    return $route ? $route->getName().' ("'.$route->getPath().'")' : '';
                },
                'choice_value' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('customRoutes', CollectionType::class, [
                'label' => 'Иные маршруты',
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => [
                    'label' => 'Маршрут',
                ],
                'empty_data' => [],
                'attr' => [
                    'class' => 'collection-field',
                ]
            ])
        ;

        $builder->get('applicationRoutes')->addModelTransformer($this->applicationRoutesTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RoutesDTO::class,
        ]);
    }
}
