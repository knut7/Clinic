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


use Ballybran\Core\Model\Model;
use Ballybran\Database\Drives\AbstractDatabaseInterface;
use Ballybran\Helpers\vardump\Vardump;

class PacienteModel
{
    /**
     * @var AbstractDatabaseInterface
     */
    private $database;

    function __construct(AbstractDatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function getInfoConsulta($id)
    {
        return $this->database->selectManager("SELECT * FROM Paciente INNER JOIN usuarios ON Paciente.usuarios_id = usuarios.id WHERE  Paciente.usuarios_id = $id ORDER  BY info ASC ");

    }
    /**
     * @param $id
     * @return mixed
     */
    public function getImageUser($id) {
        return $this->database->selectManager("SELECT * FROM pic_perfil INNER JOIN usuarios on
            pic_perfil.usuarios_id = usuarios.id WHERE pic_perfil.usuarios_id = $id  ORDER BY pic_perfil.id DESC ");
    }

    public function inserConsulta($data)
    {
        $this->database->insert('Paciente', $data);
    }

    public function getPacienteById($id) {
        return $this->database->selectManager(" SELECT  Paciente.*, Func_has_Paci.* , usuarios.*, Funcionarios.titulo FROM Func_has_Paci INNER JOIN Paciente on Func_has_Paci.Paciente_id = Paciente.id INNER JOIN usuarios on Paciente.usuarios_id = usuarios.id INNER JOIN Funcionarios ON Funcionarios.id = Func_has_Paci.Funcionarios_id WHERE Paciente.usuarios_id =$id ORDER BY Paciente.info ASC");
    }


    public function todos($id)
    {
        return $this->database->selectManager("  SELECT * FROM Paciente INNER JOIN Medicamento ON Paciente.id = Medicamento.Paciente_id  INNER JOIN Funcionarios ON Funcionarios.id = Medicamento.Funcionarios_id INNER JOIN Receitas ON Receitas.Medicamento_med_id = Medicamento.med_id INNER JOIN Exame On Exame.Receitas_id = Receitas.id WHERE Paciente.id = $id  ORDER BY Paciente.info ASC LIMIT 1");
    }

    public function getMedicamentosByPaciente($id) {

        return $this->database->selectManager( "SELECT  Paciente.*, Func_has_Paci.* , usuarios.*, Medicamento.* FROM Func_has_Paci INNER JOIN Paciente on Func_has_Paci.Paciente_id = Paciente.id INNER JOIN usuarios on Paciente.usuarios_id = usuarios.id INNER JOIN Medicamento ON Medicamento.Paciente_id = Paciente.id WHERE Paciente.usuarios_id  =$id");
    }

    public function getExamesByPaciente($id)
    {
        return $this->database->selectManager("SELECT * FROM Exame INNER JOIN Paciente ON Exame.Paciente_id = Paciente.id WHERE Paciente.usuarios_id = $id ");

    }



}