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

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Translatable\TranslatableListener;
use Positibe\Bundle\MenuBundle\Model\MenuNode;
use Symfony\Component\HttpFoundation\RequestStack;


/**
 * Class MenuNodeRepository
 * @package Positibe\Bundle\MenuBundle\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuNodeRepository extends EntityRepository implements MenuNodeRepositoryInterface
{
    use MenuNodeRepositoryTrait;
    private $locale;

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function setRequestStack(RequestStack $requestStack)
    {
        $this->locale = $requestStack->getMasterRequest()->getLocale();
    }

    public function getQuery(QueryBuilder $qb)
    {
        $query = $qb->getQuery();
        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );
        if ($this->locale) {
            $query->setHint(
                TranslatableListener::HINT_TRANSLATABLE_LOCALE,
                $this->locale // take locale from session or request etc.
            );

            $query->setHint(
                TranslatableListener::HINT_FALLBACK,
                1 // fallback to default values in case if record is not translated
            );
        }

        return $query;
    }

    /**
     * @param $name
     * @param int $level
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException|MenuNode
     */
    public function findOneByName($name, $level = 1)
    {
        $alias = 'mc';
        $qb = $this->createFindOneByName($name, $level, $alias);

        return $this->getQuery($qb)->getOneOrNullResult();
    }

    public function findOneByNameAndParent($name, $parent, $level = 1)
    {
        $alias = 'mc';

        $qb = $this->createFindOneByName($name, $level, $alias);

        $qb->join('m.parent', 'p')
            ->andWhere('p.name = :parent_name')
            ->setParameter(
                'parent_name',
                $parent
            );

        return $this->getQuery($qb)->getOneOrNullResult();
    }

    /**
     * @param $name
     * @param $level
     * @param $alias
     * @return QueryBuilder
     */
    public function createFindOneByName($name, $level, $alias)
    {
        $qb = $this->createQueryBuilder('m')->where('m.name = :name')->setParameter('name', $name)
            ->leftJoin('m.children', $alias)
            ->addSelect($alias)
            ->orderBy($alias . '.position', 'ASC');

        while ($level > 0) {
            $nextAlias = $alias . $level;
            $qb->addSelect($nextAlias)
                ->leftJoin($alias . '.children', $nextAlias);
            $alias = $nextAlias;
            $level--;
        }

        return $qb;
    }
} 