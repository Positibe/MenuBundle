<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMenuBundle\Form\EventListener;

use Positibe\Bundle\OrmMenuBundle\Menu\Factory\ContentAwareFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


/**
 * Class ContentFieldListener
 * @package Positibe\Bundle\OrmMenuBundle\Form\EventListener
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ContentFieldListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
            FormEvents::POST_SUBMIT => 'postSubmit',
        );
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $menu = $event->getData();
        $form = $event->getForm();
        if ($menu && $menu->getLinkType() === ContentAwareFactory::LINK_TYPE_CONTENT && $menu->getContentClass()) {
            $form->add(
                'content',
                'genemu_jquerychosen_entity',
                array(
                    'class' => $menu->getContentClass(),
                    'attr' => array('class' => 'chosen-select'),
                    'required' => false,
                    'label' => 'menu_node.form.content_label',
                    'placeholder' => 'chosen.form.select_option',
                    'translation_domain' => 'PositibeOrmMenuBundle'
                )
            );
        } else {
            $form->add(
                'content',
                null,
                array(
                    'attr' => array(
                        'disabled' => 'disabled'
                    ),
                    'label' => 'menu_node.form.content_label',
                    'translation_domain' => 'PositibeOrmMenuBundle'
                )
            );
        }
    }

    /**
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $menu = $event->getData();
        $form = $event->getForm();

        if ($menu['linkType'] === ContentAwareFactory::LINK_TYPE_CONTENT) {
            if (empty($menu['contentClass'])) {
                $form->addError(
                    new FormError('Si el tipo de vínculo es Contenido entonces debe poseer un contenido')
                );
            } else {
                $form->add(
                    'content',
                    'entity',
                    array(
                        'class' => $menu['contentClass']

                    )
                );
            }

        }
    }

    /**
     * @param FormEvent $event
     */
    public function postSubmit(FormEvent $event)
    {
        $menu = $event->getData();
        if ($menu->getLinkType() === ContentAwareFactory::LINK_TYPE_CONTENT && $menu->getContent()) {
            $isNew = true;
            foreach ($menu->getContent()->getMenuNodes() as $currentMenu) {
                if ($currentMenu->getId() === $menu->getId()) {
                    $isNew = false;
                    break;
                }
            }
            if ($isNew) {
                $menu->getContent()->addMenuNode($menu);
            }
        }
    }
} 