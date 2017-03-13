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

use Positibe\Component\ContentAware\Model\ContentAwareRepositoryInterface;

/**
 * Interface HasMenuRepositoryInterface
 * @package Positibe\Bundle\MenuBundle\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface HasMenuRepositoryInterface extends ContentAwareRepositoryInterface
{
    public function findOneByMenuNodesName($menuNodeName);
}