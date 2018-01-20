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
use Ballybran\Helpers\Log\Log;
use Ballybran\Helpers\Log\Logger;
use PDO;

/**
 * Created by PhpStorm.
 * User: artphotografie
 * Date: 2016/02/14
 * Time: 1:34 PM
 */


class UserModel {

    private $entity;

    public function __construct( AbstractDatabaseInterface $entity) {

        $this->entity = $entity;
    }

    /**
     * @param $data
     * @return bool
     */
    public function signUp($data) {
         $this->entity->insert('usuarios', $data);
    }

    /**
     * @param $data
     * @return array
     *  select all from usuarios
     */
    public function signIn($data) {
        return $this->entity->selectManager("SELECT * FROM usuarios WHERE email=:email", $data, PDO::FETCH_ASSOC);
    }

    /**
     * @param $data
     * @return array
     */
    public function selectData($data) {
        return $this->entity->selectManager("SELECT * FROM usuarios WHERE email=:email", $data, PDO::FETCH_ASSOC);
    }

    public function insertSession($data, $status)
    {
        return $this->entity->update('usuarios', $data, "id=$status");

    }

    public function updatePassword($data, $id)
    {
        return $this->entity->update('usuarios', $data, "id=$id");

    }
    public function logAcess($cote, $content)
    {
        $log = new Log('logoAccess.txt');
        $log->write($cote . "\t" .$content);
    }

    public function logPaciente($cote, $content)
    {
        $log = new Log('logOfPaciente.txt');
        $log->write($cote . "\t" .$content);
    }

    public function confirm($data, $confirm)
    {
        $this->entity->update("usuarios", $data, "confirmCod=$confirm");
    }
    public function confirm2($data, $confirm)
    {
        $this->entity->update("usuarios", $data, "email=$confirm");
    }



}
