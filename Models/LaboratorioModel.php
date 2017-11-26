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
 * Date: 14/11/17
 * Time: 20:53
 */

namespace Module\Clinica\Models;


use Ballybran\Database\Drives\AbstractDatabaseInterface;

class LaboratorioModel
{

    /**
     * @var AbstractDatabaseInterface
     */
    private $database;

    function __construct(AbstractDatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function getExamesByPaciente($id)
    {
        return $this->database->selectManager("SELECT * FROM Exame INNER JOIN Paciente ON Exame.Paciente_id = Paciente.id WHERE  Paciente.usuarios_id = $id ");

    }

    public function getPacienteById($id) {
        return $this->database->selectManager(" SELECT  Paciente.*, Func_has_Paci.* , usuarios.* FROM Func_has_Paci INNER JOIN Paciente on Func_has_Paci.Paciente_id = Paciente.id INNER JOIN usuarios on Paciente.usuarios_id = usuarios.id WHERE Paciente.usuarios_id =$id ORDER BY Paciente.id DESC");
    }

    public function updateExame($data, $id)
    {
        return $this->database->update("Exame", $data, "id_ex=$id");
    }

    public function getListaDeExames()
    {
        return $this->database->selectManager(" SELECT  Exame.*, Paciente.*, Funcionarios.titulo, usuarios.* FROM Exame INNER JOIN Paciente ON Exame.Paciente_id = Paciente.id INNER JOIN usuarios ON Paciente.usuarios_id = usuarios.id INNER JOIN Func_has_Paci ON Paciente.id = Func_has_Paci.Paciente_id INNER JOIN Funcionarios ON Func_has_Paci.Funcionarios_id = Funcionarios.id WHERE Exame.resultado = '' ORDER BY Exame.id_ex ASC");
    }



}