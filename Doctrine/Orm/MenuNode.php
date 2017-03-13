<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MenuBundle\Doctrine\Orm;

use Positibe\Bundle\MenuBundle\Entity\MenuNodeBase;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class MenuNode
 * @package Positibe\Bundle\MenuBundle\Doctrine\ORM
 *
 * @ORM\Table(name="positibe_menu")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\MenuBundle\Repository\MenuNodeRepository")
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuNode extends MenuNodeBase
{

}