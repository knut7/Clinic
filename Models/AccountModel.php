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

namespace Module\Clinica\Models;

use Ballybran\Database\Drives\AbstractDatabaseInterface;
use Ballybran\Helpers\Security\Session;

/**
 * Class AccountModel
 * @package Module\Clinica\Models
 */
class AccountModel {

    /**
     * @var AbstractDatabaseInterface
     */
    private $entity;

    /**
     * AccountModel constructor.
     * @param AbstractDatabaseInterface $entity
     */
    public function __construct(AbstractDatabaseInterface $entity) {

        $this->entity = $entity;
    }

    /**
     * @param $id id for user selected by session
     * @return array
     */
    public function getUser($id) {
        return $this->entity->selectManager('SELECT * FROM usuarios WHERE id =:id', ["id" => $id]);
    }

    public function getusers($user) {
        return $this->entity->selectManager("SELECT * FROM usuarios WHERE username=:username", ["username" => $user]);
    }

    public function getAllUser() {

        return $this->entity->selectManager("SELECT * FROM  usuarios INNER JOIN pic_perfil on
            pic_perfil.usuarios_id = usuarios.id  ORDER BY usuarios.status DESC" );
    }

    /**
     * @param $data
     * @param $id
     */
    public function updates($data, $id) {
         $this->entity->update('usuarios', $data, "id=$id");
    }

    public function deletes($id) {
        $this->entity->delete('usuarios', "id=$id", 1);
    }

    public function insertImage($data) {
        return $this->entity->insert('photographer', $data);
    }

    public function getImage($id) {

        return $this->entity->selectManager('SELECT * FROM usuarios inner join photographer on usuarios.id= photographer.id_user AND  photographer.id_user = usuarios.id ');
    }

    /**
     * update the role of users
     *
     * @return data, id
     * @author 
     * */
    public function manageUser($data, $id) {
        return $this->entity->update('usuarios', $data, "id=" . $id);
    }

    /**
     * update the role of users
     *
     * @return data, id
     * @author 
     * */
    public function managePassword($data, $id) {
        return $this->entity->update('usuarios', $data, "id=" . $id);
    }

    public function insertImageUser($data) {
        return $this->entity->insert('pic_perfil', $data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getImageUser($id) {
        return $this->entity->selectManager("SELECT * FROM pic_perfil INNER JOIN usuarios on
            pic_perfil.usuarios_id = usuarios.id WHERE pic_perfil.usuarios_id = $id  ORDER BY pic_perfil.id DESC ");
    }

    /**
     * @return mixed
     */
    public function getImageUserDelete() {
        return $this->entity->selectManager("SELECT * FROM pic_perfil inner join usuarios on pic_perfil.usuarios_id = usuarios.id WHERE pic_perfil.usuarios_id ORDER BY pic_perfil.id  ");
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteImagePerfil($id) {
         return $this->entity->delete('pic_perfil', "usuarios_id=$id", 1);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getMural($id) {
        return $this->entity->selectManager("SELECT * FROM mural INNER JOIN usuarios ON
                mural.usuarios_id = usuarios.id WHERE mural.usuarios_id = $id ORDER BY mural.id DESC");
    }

    /**
     * @return mixed
     */
    public function getResMural() {
        return $this->entity->selectManager("SELECT * FROM res_mural INNER JOIN mural on res_mural.id_m =mural.id WHERE mural.id ");
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getMuralById($id) {
        return $this->entity->selectManager("SELECT mural.id FROM mural inner join usuarios on mural.usuarios_id = usuarios.id WHERE usuarios.id = $id");
    }

    /**
     * @param $data
     * @return bool
     */
    public function insertMural($data) {
        return $this->entity->insert('mural', $data);
    }

    /**
     * @param $data
     * @return bool
     */
    public function insertRespo($data) {
        return $this->entity->insert('res_mural', $data);
    }

    /**
     *  Times and Date
     * @param $id
     * @return mixed
     *
     */
    public function getHorarioByFunc($id)
    {
       return  $this->entity->selectManager("SELECT * FROM Funcionarios INNER JOIN usuarios on Funcionarios.usuarios_id = usuarios.id INNER JOIN Horario ON usuarios.id = Horario.usuarios_id WHERE Horario.usuarios_id =$id");
    }

    /**
     * @param $data
     * @param $id
     */
    public function updateHorario($data, $id) {
        $this->entity->update('Horario', $data, "id=$id");
    }

    /**
     * @param $data
     */
    public function insertHorario($data)
    {
        $this->entity->insert('Horario', $data);

    }

    /**
     * @param $id
     */
    public function deteleHoraroById($id) {

        $this->entity->delete('Horario', "id=$id", 1);
    }

    public function getPacientForConsulta($id)
    {
        return $this->entity->selectManager("SELECT * FROM Func_has_Paci INNER JOIN Paciente INNER JOIN Funcionarios ON Func_has_Paci.Funcionarios_id = Funcionarios.id AND Func_has_Paci.Paciente_id = Paciente.id INNER JOIN usuarios ON usuarios.id = Paciente.usuarios_id WHERE Funcionarios.usuarios_id = $id");
    }

    public function getPacienteById($id) {
        return $this->entity->selectManager(" SELECT  Paciente.*, Func_has_Paci.* , usuarios.*, Funcionarios.titulo FROM Func_has_Paci INNER JOIN Paciente on Func_has_Paci.Paciente_id = Paciente.id INNER JOIN usuarios on Paciente.usuarios_id = usuarios.id INNER JOIN Funcionarios ON Funcionarios.id = Func_has_Paci.Funcionarios_id WHERE Paciente.usuarios_id =$id ORDER BY Paciente.info ASC");
    }



}
