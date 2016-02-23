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
 * Class HasMenuRepositoryTrait
 * @package Positibe\Bundle\OrmMenuBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
trait HasMenuRepositoryTrait
{
    /**
     * @param MenuNodeInterface $menuNode
     * @return mixed
     */
    public function findOneByMenuNodes(MenuNodeInterface $menuNode)
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.menuNodes', 'm')
            ->where('m = :menu')
            ->setParameter('menu', $menuNode);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }
} 