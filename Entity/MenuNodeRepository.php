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

use Doctrine\ORM\EntityRepository;


/**
 * Class MenuNodeRepository
 * @package Positibe\Bundle\OrmMenuBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuNodeRepository extends EntityRepository implements MenuNodeRepositoryInterface
{
    use MenuNodeRepositoryTrait;
} 