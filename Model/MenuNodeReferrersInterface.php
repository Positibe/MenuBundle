<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MenuBundle\Model;

use Knp\Menu\NodeInterface;
use Positibe\Bundle\OrmContentBundle\Entity\MenuNode;

/**
 * Interface to be implemented by content that exposes editable menu referrers.
 * This is used with the Sonata MenuAwareExtension.
 *
 * Interface MenuNodeReferrersInterface
 * @package Positibe\Bundle\MenuBundle\Model
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface MenuNodeReferrersInterface
{
    /**
     * Get all menu nodes that point to this content.
     *
     * @return NodeInterface[]|MenuNode[] Menu nodes that point to this content
     */
    public function getMenuNodes();

    /**
     * Add a menu node for this content.
     *
     * @param NodeInterface|MenuNode $menu
     */
    public function addMenuNode(NodeInterface $menu);

    /**
     * Remove a menu node for this content.
     *
     * @param NodeInterface|MenuNode $menu
     */
    public function removeMenuNode(NodeInterface $menu);

    /**
     * Set a collection of MenuNode
     *
     * @param $menus
     * @return mixed
     */
    public function setMenuNodes($menus);
}
