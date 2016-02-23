<?php

namespace Positibe\Bundle\OrmMenuBundle\Form\Type;

use Positibe\Bundle\OrmMenuBundle\Form\EventListener\ContentFieldListener;
use Positibe\Bundle\OrmMenuBundle\Menu\Factory\ContentAwareFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class MenuNodeType
 * @package Positibe\Bundle\OrmMenuBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuNodeType extends AbstractType
{
    private $locales;
    private $publicRoutes;
    private $contentClass;
    private $menuNodeClass;

    public function __construct($menuNodeClass, $locales)
    {
        $this->menuNodeClass = $menuNodeClass;
        $this->locales = $locales;
    }

    /**
     * @param mixed $contentClass
     */
    public function setContentClass($contentClass)
    {
        $this->contentClass = $contentClass;
    }

    /**
     * @param mixed $locales
     */
    public function setLocales($locales)
    {
        $this->locales = $locales;
    }

    /**
     * @param mixed $publicRoutes
     */
    public function setPublicRoutes($publicRoutes)
    {
        $this->publicRoutes = $publicRoutes;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new ContentFieldListener());

        $builder
            ->add(
                'label',
                null,
                array(
                    'label' => 'menu_node.form.label_label',
                    'translation_domain' => 'PositibeOrmMenuBundle'
                )
            )
            ->add(
                'locale',
                'choice',
                array(
                    'choices' => array_combine($this->locales, $this->locales),
                    'label' => 'menu_node.form.locale_label',
                    'translation_domain' => 'PositibeOrmMenuBundle'
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
                'linkAttributes',
                'sonata_type_immutable_array',
                array(
                    'keys' => array(
                        array('class', 'text', array('required' => false)),
                        array('target', 'text', array('required' => false)),
                    ),
                    'label' => 'menu_node.form.link_attributes_label',
                )
            )
            ->add(
                'labelAttributes',
                'sonata_type_immutable_array',
                array(
                    'keys' => array(
                        array('class', 'text', array('required' => false)),
                    ),
                    'label' => 'menu_node.form.label_attributes_label',
                )
            )
            ->add(
                'linkType',
                'choice',
                array(
                    'choices' => array(
                        ContentAwareFactory::LINK_TYPE_URI => 'menu_node.form.link_type_choices.uri',
                        ContentAwareFactory::LINK_TYPE_ROUTE => 'menu_node.form.link_type_choices.route',
                        ContentAwareFactory::LINK_TYPE_CONTENT => 'menu_node.form.link_type_choices.content',
                    ),
                    'label' => 'menu_node.form.link_type_label',
                    'translation_domain' => 'PositibeOrmMenuBundle'
                )
            )
            ->add(
                'uri',
                null,
                array(
                    'label' => 'menu_node.form.uri_label',
                    'translation_domain' => 'PositibeOrmMenuBundle',
                    'required' => false
                )
            )
            ->add(
                'route',
                'choice',
                array(
                    'label' => 'menu_node.form.route_label',
                    'translation_domain' => 'PositibeOrmMenuBundle',
                    'choices' => array_combine($this->publicRoutes, $this->publicRoutes),
                    'required' => false
                )
            )
            ->add(
                'contentClass',
                'choice',
                array(
                    'choices' => array_combine($this->contentClass, $this->contentClass),
                    'label' => 'menu_node.form.content_class_label',
                    'required' => false
                )
            )
            ->add(
                'iconClass',
                null,
                array(
                    'label' => 'menu_node.form.icon_class_class_label',
                    'required' => false
                )
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
//            ->add('routeParameters')
//            ->add('extras')
//            ->add('routeAbsolute')
//            ->add(
//                'attributes',
//                'sonata_type_immutable_array',
//                array(
//                    'label' => 'menu_node.form.attributes_label',
//                    'translation_domain' => 'PositibeOrmMenuBundle',
//                    'keys' => array(
//                        array('id',      'text', array('required' => false)),
//                        array('class',   'text',  array('required' => false)),
//                    )
//                )
//            )
//            ->add(
//                'childrenAttributes',
//                'sonata_type_immutable_array',
//                array(
//                    'keys' => array(
//                        array('id', 'text', array('required' => false)),
//                        array('class', 'text', array('required' => false)),
//                    ),
//                    'label' => 'menu_node.form.children_attributes_label',
//                )
//            )
        ;


    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => $this->menuNodeClass,
                'translation_domain' => 'PositibeOrmMenuBundle'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'positibe_menu_node';
    }
}
