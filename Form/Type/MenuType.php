<?php

namespace Positibe\Bundle\MenuBundle\Form\Type;

use Positibe\Bundle\MenuBundle\Menu\Factory\ContentAwareFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    private $menuNodeClass;

    public function __construct($menuNodeClass)
    {
        $this->menuNodeClass = $menuNodeClass;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                null,
                array(
                    'label' => 'menu_node.form.name_label',
                    'translation_domain' => 'PositibeMenuBundle'
                )
            )
            ->add(
                'display',
                null,
                array(
                    'label' => 'menu_node.form.display_label',
                    'required' => false
                )
            )
            ->add(
                'displayChildren',
                null,
                array('label' => 'menu_node.form.display_children_label', 'required' => false)
            )
            ->add(
                'childrenAttributes',
                'sonata_type_immutable_array',
                array(
                    'keys' => array(
                        array('id', 'text', array('required' => false)),
                        array('class', 'text', array('required' => false)),
                    ),
                    'label' => 'menu_node.form.children_attributes_label',
                )
            )
            ->add(
                'linkType',
                'hidden',
                array(
                    'data' => ContentAwareFactory::LINK_TYPE_ROOT
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => $this->menuNodeClass,
                'translation_domain' => 'PositibeMenuBundle'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'positibe_menu';
    }
}
