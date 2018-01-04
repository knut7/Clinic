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
 * Date: 11/11/17
 * Time: 12:15
 */

namespace Module\Clinic\Models;


use Ballybran\Database\Drives\AbstractDatabaseInterface;
use Ballybran\Helpers\vardump\Vardump;

class FuncionarioModel
{

    /**
     * @var AbstractDatabaseInterface
     */
    private $database;

    function __construct(AbstractDatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function allPacienteByFuncionario($id) {
        return $this->database->selectManager("SELECT Func_has_Paci.Funcionarios_id FROM Func_has_Paci INNER JOIN Paciente INNER JOIN Funcionarios ON Func_has_Paci.Funcionarios_id = Funcionarios.id AND Func_has_Paci.Paciente_id = Paciente.id INNER JOIN usuarios ON usuarios.id = Paciente.usuarios_id LEFT OUTER JOIN Anamnese ON Anamnese.Paciente_id = Paciente.id  WHERE Anamnese.Funcionarios_id is null  and Funcionarios.usuarios_id = $id" );
    }

    public function getMedicamentoByPaciente($id)
    {
        return $this->database->selectManager("SELECT * FROM Medicamento INNER JOIN Paciente ON Medicamento.Paciente_id = Paciente.id WHERE Paciente.usuarios_id = $id ORDER BY Medicamento.med_id DESC");
    }

    public function getReceitasByPaciente($id)
    {
        return $this->database->selectManager(" SELECT Receitas.id FROM Receitas INNER JOIN Medicamento ON Receitas.Medicamento_med_id = Medicamento.med_id INNER JOIN Paciente ON Medicamento.Paciente_id = Paciente.id WHERE Paciente.id = $id ORDER BY Receitas.id DESC ");
    }

    public function getPacienteById($id) {
        return $this->database->selectManager(" SELECT  Paciente.*, Func_has_Paci.* , usuarios.* FROM Func_has_Paci INNER JOIN Paciente on Func_has_Paci.Paciente_id = Paciente.id INNER JOIN usuarios on Paciente.usuarios_id = usuarios.id WHERE Paciente.usuarios_id =$id ORDER BY Paciente.id DESC");
    }

    public function getMedicamentosByPaciente($id) {

        return $this->database->selectManager( "SELECT  Paciente.*, Func_has_Paci.* , usuarios.*, Medicamento.* FROM Func_has_Paci INNER JOIN Paciente on Func_has_Paci.Paciente_id = Paciente.id INNER JOIN usuarios on Paciente.usuarios_id = usuarios.id INNER JOIN Medicamento ON Medicamento.Paciente_id = Paciente.id WHERE Paciente.id  =$id");
    }

    public function getExamesByPaciente($id)
    {
        return $this->database->selectManager("SELECT * FROM Exame INNER JOIN Paciente ON Exame.Paciente_id = Paciente.id WHERE Paciente.id = $id ");

    }
    public function getImageUser($id) {
        return $this->database->selectManager("SELECT * FROM pic_perfil INNER JOIN usuarios on pic_perfil.usuarios_id = usuarios.id WHERE pic_perfil.usuarios_id = $id  ORDER BY pic_perfil.id DESC ");
    }



    public function insertAnamnese($data) {
        $this->database->insert("Anamnese", $data);
    }

    public function insertExameFisico($data) {
        $this->database->insert("Exame_Fisico", $data);
    }

    public function insertHipoteseDiagnostico($data) {
        $this->database->insert("hipotese_diagnostica", $data);
    }

    public function insertEvolucao($data) {
        $this->database->insert("Evolucao", $data);
    }
    public function inserMedicamento($data)
    {
        $this->database->insert('Medicamento', $data);
    }

    public function inserirReceita($data)
    {
        $this->database->insert('Receitas', $data);
    }
    public function inserirExame($data)
    {
        $this->database->insert('Exame', $data);
    }

}