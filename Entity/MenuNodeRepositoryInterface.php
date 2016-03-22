<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMenuBundle\Entity;

use Positibe\Bundle\OrmMenuBundle\Model\MenuNodeInterface;

/**
 * Interface MenuNodeRepositoryInterface
 * @package Positibe\Bundle\OrmMenuBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface MenuNodeRepositoryInterface
{
    /**
     * @param $name
     * @param int $level
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException|MenuNodeInterface
     */
    public function findOneByName($name, $level = 1);
} 