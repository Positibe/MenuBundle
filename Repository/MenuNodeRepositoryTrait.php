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

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Positibe\Bundle\MenuBundle\Model\MenuNodeInterface;

/**
 * Class MenuNodeRepositoryTrait
 * @package Positibe\Bundle\MenuBundle\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
trait MenuNodeRepositoryTrait
{
    /**
     * @param $name
     * @param int $level
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException|MenuNodeInterface
     */
    public function findOneByName($name, $level = 1)
    {
        $alias = 'mc';
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('m')->where('m.name = :name')->setParameter('name', $name)
            ->leftJoin('m.children', $alias)
            ->addSelect($alias)
            ->orderBy($alias . '.position', 'ASC');

        while ($level > 0) {
            $nextAlias = $alias . $level;
            $qb->addSelect($nextAlias)->leftJoin($alias . '.children', $nextAlias);
            $alias = $nextAlias;
            $level--;
        }

        $query = $this->getQuery($qb);

        return $query->getOneOrNullResult();
    }
} 