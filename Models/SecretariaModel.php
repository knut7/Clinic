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
 * Date: 12/11/17
 * Time: 14:51
 */

namespace Module\Clinic\Models;

use Ballybran\Exception\Exception;
use Ballybran\Database\Drives\AbstractDatabaseInterface;

class SecretariaModel {

    /**
     * @var AbstractDatabaseInterface
     */
    private $entity;

    function __construct(AbstractDatabaseInterface $entity) {
        $this->entity = $entity;
    }

    /**
     * @param $data
     * @return array
     */
    public function getAllUser() {

        return $this->entity->selectManager("SELECT * FROM usuarios WHERE usuarios.role = 'medico' || usuarios.role = 'secretaria'  ");
    }

    public function getAllPaciente() {

        return $this->entity->selectManager("SELECT usuarios.*, Paciente.info FROM usuarios LEFT OUTER JOIN Paciente ON usuarios.id = Paciente.usuarios_id WHERE usuarios.role = 'paciente'  AND Paciente.id IS NULL ");
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
        return $this->entity->selectManager('SELECT * FROM usuarios WHERE  id =' . $id);
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

    public function getConsultas() {
        return $this->entity->selectManager('SELECT Func_has_Paci.*, Paciente.id, Paciente.info, usuarios.firstname, usuarios.lastname, usuarios.telephone, usuarios.role FROM Paciente INNER JOIN usuarios ON Paciente.usuarios_id = usuarios.id  left outer JOIN Func_has_Paci ON Func_has_Paci.Paciente_id = Paciente.id WHERE Func_has_Paci.Funcionarios_id is null AND Paciente.usuarios_id ORDER BY Paciente.id ASC');
    }

    public function getFuncionarios() {
        return $this->entity->selectManager('SELECT Funcionarios.id, usuarios.firstname, usuarios.lastname FROM  Funcionarios INNER JOIN usuarios ON Funcionarios.usuarios_id = usuarios.id');
    }

    public function joinPacienteAndFunc($data) {
        $this->entity->insert('Func_has_Paci', $data);
    }

    public function inserPasciente($data) {
        $this->entity->insert('Paciente', $data);
    }

    public function insertExameFisico($data) {
        $this->entity->insert("Exame_Fisico", $data);
    }

    public function insertEspecialidade($data) {
        $this->entity->insert("Especialidade", $data);
    }

    public function deleteEspecialidade($id) {
        $this->entity->delete("Especialidade", "id=$id", 1);
    }

    public function deleteConvenio($id) {
        $this->entity->delete("Convenio", "id=$id", 1);
    }

    public function createConvenio($data) {
        if (is_array($data)) {
            try {
                $this->entity->insert("Convenio", $data);
            } catch (\Exception $e) {
                echo "meu erro" . $e->getMessage();
            }
        }
    }

    public function deletarAgendamento($id) {
        return $this->entity->delete("Paciente", "id=$id");
    }

}
