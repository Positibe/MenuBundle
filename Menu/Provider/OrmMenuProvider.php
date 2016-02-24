<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMenuBundle\Menu\Provider;

use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Positibe\Bundle\OrmMenuBundle\Entity\MenuNodeRepositoryInterface;
use Positibe\Bundle\OrmMenuBundle\Menu\Factory\ContentAwareFactory;


/**
 * Class OrmMenuProvider
 * @package Positibe\Bundle\OrmMenuBundle\Menu\Provider
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class OrmMenuProvider implements MenuProviderInterface
{
    /**
     * @var ContentAwareFactory
     */
    private $factory;
    private $manager;
    private $menuNodeClass;

    /**
     * @param FactoryInterface $factory the menu factory used to create the menu item
     * @param EntityManager $manager
     * @param $menuNodeClass
     */
    public function __construct(FactoryInterface $factory, EntityManager $manager, $menuNodeClass)
    {
        $this->factory = $factory;
        $this->manager = $manager;
        $this->menuNodeClass = $menuNodeClass;
    }

    /**
     * Retrieves a menu by its name
     *
     * @param string $name
     * @param array $options
     *
     * @return \Knp\Menu\ItemInterface
     * @throws \InvalidArgumentException if the menu does not exists
     */
    public function get($name, array $options = array())
    {
        /** @var MenuNodeRepositoryInterface $repository */
        if (!$repository = $this->manager->getRepository($this->menuNodeClass)) {
            return null;
        }

        $menu = $repository->findOneByName($name);

        if ($menu === null) {
            return $this->factory->createItem('');
        }

        return $this->factory->createFromNode($menu);
    }

    /**
     * Checks whether a menu exists in this provider
     *
     * @param string $name
     * @param array $options
     *
     * @return boolean
     */
    public function has($name, array $options = array())
    {
        return true;
    }
} 