<?php

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Implementation of FOS\UserBundle\Model\User
 *
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 *
 * @author André Friedli <a@frian.org>
 */
class User extends BaseUser {

  /**
   * id
   *
   * @var integer
   *
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * stringify
   *
   * @return string
   *
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Get id
   *
   * @return integer
   */
  public function getId() {
    return $this->id;
  }
}
