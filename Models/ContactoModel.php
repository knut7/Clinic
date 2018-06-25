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
/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 25/12/17
 * Time: 15:03
 */

namespace Module\Clinic\Models;

use Ballybran\Database\Drives\AbstractDatabaseInterface;

class ContactoModel {

    /**
     * @var AbstractDatabaseInterface
     */
    private $database;

    function __construct(AbstractDatabaseInterface $database) {
        $this->database = $database;
    }

    public function getContacto() {

        return $this->database->find("Contacto");
    }

    public function getSocial() {

        return $this->database->find("Social");
    }

    public function updateContacto($data, $id) {
        $this->database->update("Contacto", $data, "id=$id");
    }

    public function updateSocial($data, $id) {
        $this->database->update("Social", $data, "id=$id");
    }

    public function getMailSetting() {
        return $this->database->find("MailConfig", "*");
    }

    public function updateMailConfig($data, $id) {
        $this->database->update("MailConfig", $data, "id=$id");
    }

    public function createSidebar($data) {
        $this->database->insert("Sidebar", $data);
    }

}
