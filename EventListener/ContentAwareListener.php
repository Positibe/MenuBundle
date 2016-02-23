<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMenuBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\MappingException;
use Positibe\Bundle\OrmMenuBundle\Entity\HasMenuRepositoryInterface;
use Positibe\Bundle\OrmMenuBundle\Menu\Factory\ContentAwareFactory;
use Positibe\Bundle\OrmMenuBundle\Model\MenuNodeInterface;


/**
 * Class ContentAwareListener
 * @package Positibe\Bundle\OrmMenuBundle\EventListener
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ContentAwareListener
{
    public function postLoad(MenuNodeInterface $menuNode, LifecycleEventArgs $event)
    {
        if ($menuNode->getLinkType() === ContentAwareFactory::LINK_TYPE_CONTENT &&
            $menuNode->getContent() === null &&
            $menuNode->getContentClass() !== null
        ) {
            try {
                $repository = $this->getContentRepository($event->getEntityManager(), $menuNode);
                if ($repository && $content = $repository->findOneByMenuNodes($menuNode)) {
                    $menuNode->setContent($content);
                }
            } catch (MappingException $e) {
            }
        }
    }

    /**
     * @param EntityManager $manager
     * @param MenuNodeInterface $menuNode
     * @return \Doctrine\ORM\EntityRepository|null|HasMenuRepositoryInterface
     */
    public function getContentRepository(EntityManager $manager, MenuNodeInterface $menuNode)
    {
        $repository = $manager->getRepository($menuNode->getContentClass());
        if (!$repository instanceof HasMenuRepositoryInterface) {
            return null;
        }

        return $repository;
    }
} 