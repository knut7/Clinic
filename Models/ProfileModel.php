<?php

/**
 * KNUT7 K7F (http://framework.artphoweb.com/)
 * KNUT7 K7F (tm) : Rapid Development Framework (http://framework.artphoweb.com/)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link      http://github.com/zebedeu/artphoweb for the canonical source repository
 * @copyright (c) 2015.  KNUT7  Software Technologies AO Inc. (http://www.artphoweb.com)
 * @license   http://framework.artphoweb.com/license/new-bsd New BSD License
 * @author    Marcio Zebedeu - artphoweb@artphoweb.com
 * @version   1.0.2
 */

namespace Module\Clinic\Models;

use Ballybran\Database\Drives\AbstractDatabaseInterface;

/**
 * Created by PhpStorm.
 * User: artphotografie
 * Date: 2016/06/22
 * Time: 3:13 PM
 */

class ProfileModel {

    private $entity;

    public function __construct( AbstractDatabaseInterface $entity) {

        $this->entity = $entity;
    }

    /**
     * @return mixed
     */
    public function openProfile() {
        return $tnt = $this->entity->selectManager("SELECT archive FROM Profile");
        var_dump($tnt);
    }

    public function insertProfile($data) {
        return $this->entity->insert("Profile", $data);
    }

}
