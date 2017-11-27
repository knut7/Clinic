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

class DashboardModel {

    /**
     * @var iDatabase
     */
    private $entity;

    public function __construct( AbstractDatabaseInterface $entity) {
        $this->entity = $entity;
    }

    /**
     * @param $data
     * @return array
     */
    public function getAllUser() {

        return $this->entity->selectManager("SELECT * FROM usuarios WHERE usuarios.role = 'funcionario' || usuarios.role = 'secretaria' || usuarios.role = 'laboratorio'  ");
    }

    public function getAllPaciente() {

        return $this->entity->selectManager("SELECT usuarios.*, Paciente.info FROM usuarios LEFT OUTER JOIN Paciente ON usuarios.id = Paciente.usuarios_id WHERE usuarios.role = 'paciente' ");
    }

    /**
     * @param $data
     * @return bool
     */
    public function add($data) {
        return $this->entity->insert('usuarios', $data);
    }

    /**
     * @param $id id for user selected by session
     * @return array
     */
    public function getUser($id) {
        return $this->entity->selectManager('SELECT * FROM usuarios WHERE id ='.$id);
    }

    public function getusers($user) {
        return $this->entity->selectManager("SELECT * FROM usuarios WHERE id=:id", ["id" => $user]);
    }


    /**
     * @param $data
     * @param $id
     */
    public function updates($data, $id) {
        return $this->entity->update('usuarios', $data, "id=" . $id);
    }

    public function deletes($id) {
        $this->entity->delete('usuarios', "id=$id", 1);
    }

}
