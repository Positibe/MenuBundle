<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MenuBundle\Repository;

use Doctrine\ORM\QueryBuilder;

/**
 * Class HasMenuRepositoryTrait
 * @package Positibe\Bundle\MenuBundle\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
trait HasMenuRepositoryTrait
{
    public function findOneByMenuNodesName($menuNodeName)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('o')
            ->join('o.menuNodes', 'm')
            ->where('m.name = :menu')
            ->setParameter('menu', $menuNodeName);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }
} 