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
 * Date: 29/11/17
 * Time: 10:03
 */
namespace Module\Clinic\Models;



use Ballybran\Database\Drives\AbstractDatabaseInterface;
use Ballybran\Helpers\Log\Log;
use Ballybran\Helpers\Log\Logger;

/**
 * Class AccountModel
 * @package Module\Clinic\Models
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
 * @param $id
 * @return array
 */
public function getUser($id) {
return $this->entity->selectManager("SELECT * FROM usuarios WHERE id =:id", ["id" => $id]);
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
public function manageUser($data, $id) : bool {
return $this->entity->update('usuarios', $data, "id=" . $id);
}


public function managePassword($data, $id) : bool{
return $this->entity->update('usuarios', $data, "id=" . $id);
}

public function insertImageUser($data) {
$this->entity->insert('pic_perfil', $data);
}

/**
 * @param $id
 * @return mixed
 */
public function getImageUser($id) {
return $this->entity->selectManager(" SELECT * FROM pic_perfil INNER JOIN usuarios on
            pic_perfil.usuarios_id = usuarios.id WHERE pic_perfil.usuarios_id = $id  ORDER BY pic_perfil.id DESC ");
}

/**
 * @return mixed
 */
public function getImageUserDelete() : array {
return $this->entity->selectManager("SELECT * FROM pic_perfil inner join usuarios on pic_perfil.usuarios_id = usuarios.id WHERE pic_perfil.usuarios_id ORDER BY pic_perfil.id  ");
}

/**
 * @param $id
 * @return mixed
 */
public function deleteImagePerfil($id) {
return $this->entity->delete('pic_perfil', "usuarios_id=$id", 1);
}



public function getHorarioByFunc($id) : array
{
return $this->entity->selectManager("SELECT * FROM Funcionarios INNER JOIN usuarios on Funcionarios.usuarios_id = usuarios.id INNER JOIN Horario ON usuarios.id = Horario.usuarios_id WHERE Horario.usuarios_id =$id");
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
return $this->entity->selectManager("SELECT Paciente.* , usuarios.*, Convenio.convNome, Func_has_Paci.start, Func_has_Paci.Funcionarios_id  FROM Func_has_Paci INNER JOIN Paciente INNER JOIN Funcionarios ON Func_has_Paci.Funcionarios_id = Funcionarios.id AND Func_has_Paci.Paciente_id = Paciente.id INNER JOIN usuarios ON usuarios.id = Paciente.usuarios_id LEFT OUTER JOIN pic_perfil ON pic_perfil.usuarios_id = Paciente.usuarios_id LEFT OUTER JOIN Anamnese ON Paciente.id = Anamnese.Paciente_id INNER JOIN Convenio ON Convenio.id = Paciente.Convenio_id WHERE Anamnese.Paciente_id IS NULL AND Funcionarios.usuarios_id = $id AND Func_has_Paci.start > now() ");
}

public function getPacientForAtendimento($id)
{
return $this->entity->selectManager("SELECT Funcionarios.id, Funcionarios.titulo, Funcionarios.usuarios_id, Especialidade.espNome, Func_has_Paci.Paciente_id,Convenio.convNome, usuarios.firstname, usuarios.lastname, usuarios.sexo, usuarios.dataNascimento  FROM Func_has_Paci INNER JOIN Paciente INNER JOIN Funcionarios ON Func_has_Paci.Funcionarios_id = Funcionarios.id AND Func_has_Paci.Paciente_id = Paciente.id INNER JOIN usuarios ON usuarios.id = Paciente.usuarios_id LEFT OUTER JOIN pic_perfil ON pic_perfil.usuarios_id = Paciente.usuarios_id INNER JOIN Anamnese ON Paciente.id = Anamnese.Paciente_id INNER JOIN Convenio ON Convenio.id = Paciente.Convenio_id  INNER JOIN Especialidade ON Especialidade.id = Funcionarios.Especialidade_id LEFT OUTER JOIN internamento ON Paciente.id = Internamento.Paciente_id WHERE Anamnese.Paciente_id AND Internamento.id is null AND Funcionarios.usuarios_id = $id ");
}

public function getPacientInternados($id)
{
return $this->entity->selectManager("SELECT Especialidade.espNome, Paciente.id,Convenio.convNome, Paciente.usuarios_id, usuarios.firstname, usuarios.lastname, usuarios.sexo, usuarios.dataNascimento, Internamento.notaEntrada, Internamento.interData  FROM Func_has_Paci INNER JOIN Paciente INNER JOIN Funcionarios ON Func_has_Paci.Funcionarios_id = Funcionarios.id AND Func_has_Paci.Paciente_id = Paciente.id INNER JOIN usuarios ON usuarios.id = Paciente.usuarios_id LEFT OUTER JOIN pic_perfil ON pic_perfil.usuarios_id = Paciente.usuarios_id INNER JOIN Anamnese ON Paciente.id = Anamnese.Paciente_id INNER JOIN Convenio ON Convenio.id = Paciente.Convenio_id  INNER JOIN Especialidade ON Especialidade.id = Funcionarios.Especialidade_id INNER JOIN internamento ON Paciente.id = Internamento.Paciente_id WHERE Anamnese.Paciente_id AND  Funcionarios.usuarios_id = $id ORDER BY id  DESC" );

}
/**
 * @param $id | return user \ order
 * @return mixed
 *
 */
public function getPacienteById($id) {
return $this->entity->selectManager("  SELECT  Paciente.* , usuarios.firstname, usuarios.lastname, Situacao.situ_nome,  Credito.CreditoValor, Credito.dtPag, Funcionarios.titulo, Funcionarios.aria FROM Func_has_Paci INNER JOIN Paciente on Func_has_Paci.Paciente_id = Paciente.id INNER JOIN usuarios on Paciente.usuarios_id = usuarios.id INNER JOIN Funcionarios ON Funcionarios.id = Func_has_Paci.Funcionarios_id INNER JOIN Credito ON Credito.Paciente_id = Paciente.id INNER JOIN Situacao ON Situacao.id = Paciente.Situacao_id  WHERE Paciente.usuarios_id = $id ORDER BY Paciente.info ASC");
}




//    admin nd owner

/**
 * @param $data
 * @return array
 */
public function getAllMedicos() {

return $this->entity->selectManager("SELECT usuarios.*, Especialidade.espNome, pic_perfil.path FROM usuarios LEFT OUTER JOIN Funcionarios ON usuarios.id = Funcionarios.usuarios_id LEFT OUTER JOIN Especialidade ON Especialidade.id = Funcionarios.Especialidade_id LEFT OUTER JOIN  pic_perfil ON pic_perfil.usuarios_id = usuarios.id WHERE usuarios.role = 'medico' ");
}

public function getAllFuncionarios($value = '')
{
return $this->entity->selectManager("SELECT usuarios.*, pic_perfil.path FROM usuarios LEFT JOIN pic_perfil ON pic_perfil.usuarios_id = usuarios.id WHERE  usuarios.role = 'secretaria' || usuarios.role = 'laboratorio' || usuarios.role = 'markting'  ");
}

public function getAllPaciente() {

return $this->entity->selectManager("SELECT Paciente.* , usuarios.firstname, usuarios.createTime,usuarios.telephone, usuarios.lastname, usuarios.dataNascimento, usuarios.sexo, Situacao.situ_nome, Especialidade.espValor, Especialidade.espNome, Credito.dtPag, Convenio.convNome , Credito.CreditoValor, pic_perfil.path, Func_has_Paci.start FROM usuarios INNER JOIN  Paciente  ON usuarios.id = Paciente.usuarios_id INNER JOIN Especialidade ON Especialidade.id = Paciente.Especialidade_id INNER JOIN Func_has_Paci ON Paciente.id = Func_has_Paci.Paciente_id   INNER JOIN Funcionarios ON Func_has_Paci.Funcionarios_id = Funcionarios.id INNER JOIN Situacao ON Situacao.id = Paciente.Situacao_id INNER JOIN Credito ON Credito.Paciente_id = Paciente.id INNER JOIN Convenio ON Convenio.id = Paciente.Convenio_id LEFT JOIN pic_perfil ON pic_perfil.usuarios_id = usuarios.id WHERE  usuarios.role = 'paciente' ");
}

public function getRetornoDoPaciente()
{
return $this->entity->selectManager("SELECT usuarios.id, usuarios.telephone, usuarios.createTime, usuarios.firstname, usuarios.lastname, usuarios.dataNascimento, usuarios.sexo, pic_perfil.path FROM usuarios LEFT JOIN pic_perfil ON pic_perfil.usuarios_id = usuarios.id WHERE  usuarios.role = 'paciente' ");

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
public function getUserAdmin($id) {
return $this->entity->selectManager('SELECT * FROM usuarios INNER JOIN Funcionarios  WHERE id ='.$id);
}

public function getusersAdmin($user) {
return $this->entity->selectManager("SELECT * FROM usuarios WHERE id=:id", ["id" => $user]);
}



/**
 * @param $data
 * @param $id
 */
public function updatesAdmin($data, $id) {
return $this->entity->update('usuarios', $data, "id=" . $id);
}

public function deletesAdmin($id) {
$this->entity->delete('usuarios', "id=$id", 1);
}

public function getConsultas()
{
return $this->entity->selectManager("SELECT usuarios.id, usuarios.firstname, usuarios.lastname, usuarios.sexo, usuarios.createTime, usuarios.telephone, usuarios.dataNascimento FROM usuarios LEFT OUTER JOIN Paciente ON usuarios.id = Paciente.usuarios_id WHERE Paciente.usuarios_id IS NULL AND  usuarios.role = 'paciente' ");
}

public function getConvenio()
{
return $this->entity->find('Convenio', '*');
}

public function getFuncionarios($id)
{
return $this->entity->selectManager("SELECT Funcionarios.id, usuarios.firstname, usuarios.lastname FROM   Funcionarios INNER JOIN Especialidade ON Especialidade.id = Funcionarios.Especialidade_id INNER JOIN usuarios ON Funcionarios.usuarios_id = usuarios.id WHERE Especialidade_id = $id");
}

public function getUnidadeInternacao()
{
return $this->entity->find('UnidadeInternacao', '*');
}

public function getTipoAdmissao()
{
return $this->entity->find('TipoAdmissao', '*');
}

public function inserConsulta($data)
{
$this->entity->insert('Paciente', $data);
}

public function getTypeReceita() {

return $this->entity->find("TipoReceita", "*");
}

public function getContaMovimento() {

return $this->entity->find("ContaMovimento", "*");
}

public function getTypePagamento() {

return $this->entity->find("FormaPagamento", "*");
}

public function getTypeSituacao() {

return $this->entity->find("Situacao", "*");
}

public function getEspecialidades()
{
return $this->entity->find("Especialidade", "*");

}


public function getPacienteSemMedico()
{
return $this->entity->selectManager("SELECT Paciente.Especialidade_id, Paciente.info, Paciente.id, usuarios.firstname, usuarios.lastname, usuarios.role, usuarios.telephone, usuarios.sexo, usuarios.dataNascimento, Situacao.situ_nome, Especialidade.espValor,Especialidade.espNome, Credito.dtPag, Convenio.convNome FROM usuarios INNER JOIN  Paciente  ON usuarios.id = Paciente.usuarios_id INNER JOIN Especialidade ON Especialidade.id = Paciente.Especialidade_id LEFT JOIN Func_has_Paci ON Paciente.id = Func_has_Paci.Paciente_id  LEFT OUTER JOIN Funcionarios ON Func_has_Paci.Funcionarios_id = Funcionarios.id INNER JOIN Situacao ON Situacao.id = Paciente.Situacao_id LEFT OUTER JOIN Credito ON Credito.Paciente_id = Paciente.id INNER JOIN Convenio ON Convenio.id = Paciente.Convenio_id WHERE  Func_has_Paci.Funcionarios_id is null AND Credito.Paciente_id is null AND usuarios.role = 'paciente' ");
}

public function getPacienteParaTriagem()
{
return $this->entity->selectManager("  SELECT Paciente.Especialidade_id, Paciente.info, Paciente.id, usuarios.firstname, usuarios.lastname, usuarios.role, usuarios.telephone, usuarios.sexo, usuarios.dataNascimento, Situacao.situ_nome, Especialidade.espValor,Especialidade.espNome, Credito.dtPag, Convenio.convNome FROM usuarios INNER JOIN  Paciente  ON usuarios.id = Paciente.usuarios_id INNER JOIN Especialidade ON Especialidade.id = Paciente.Especialidade_id LEFT JOIN Func_has_Paci ON Paciente.id = Func_has_Paci.Paciente_id  LEFT OUTER JOIN Funcionarios ON Func_has_Paci.Funcionarios_id = Funcionarios.id INNER JOIN Situacao ON Situacao.id = Paciente.Situacao_id LEFT OUTER JOIN Credito ON Credito.Paciente_id = Paciente.id INNER JOIN Convenio ON Convenio.id = Paciente.Convenio_id LEFT OUTER JOIN Exame_Fisico ON Exame_Fisico.Paciente_id = Paciente.id WHERE  Func_has_Paci.Funcionarios_id is null AND Exame_Fisico.Paciente_id is null AND Situacao.situ_nome = 'FECHADO' AND usuarios.role = 'paciente' ");
}

public function getAllInfoForPrint($id)
{
return $this->entity->selectManager(" SELECT * FROM Paciente INNER JOIN usuarios ON usuarios.id= Paciente.usuarios_id
                                                                    INNER JOIN Func_has_Paci ON Func_has_Paci.Paciente_id = Paciente.id
                                                                    INNER JOIN Funcionarios ON Funcionarios.id = Func_has_Paci.Funcionarios_id
                                                                    LEFT OUTER JOIN Convenio ON Convenio.id = Paciente.Convenio_id
                                                                    LEFT OUTER JOIN Anamnese ON Anamnese.Paciente_id = Paciente.id
                                                                    LEFT OUTER JOIN Docum_Atestado ON Docum_Atestado.Paciente_id = Paciente.id
                                                                    LEFT OUTER JOIN Evolucao ON Evolucao.Paciente_id = Paciente.id
                                                                    LEFT OUTER JOIN Exame_Fisico ON Exame_Fisico.Paciente_id = Paciente.id
                                                                    LEFT OUTER JOIN hipotese_diagnostica ON hipotese_diagnostica.Paciente_id = Paciente.id
                                                                    LEFT OUTER JOIN Medicamento ON Medicamento.Paciente_id = Paciente.id WHERE Paciente.id =$id");

}

public function getAllPrescricaoForPrint($id)
{
return $this->entity->selectManager(" SELECT Paciente.id, usuarios.firstname, usuarios.lastname, usuarios.sexo, usuarios.telephone, usuarios.telephone2, usuarios.email,  Func_has_Paci.start, Func_has_Paci.end, Medicamento.dose, Medicamento.comercial, Medicamento.generico, Medicamento.inicio, Medicamento.final, Medicamento.via_admin, Medicamento.interval, Medicamento.Paciente_id, Funcionarios.titulo  FROM Paciente INNER JOIN usuarios ON usuarios.id= Paciente.usuarios_id
                                                                    INNER JOIN Func_has_Paci ON Func_has_Paci.Paciente_id = Paciente.id
                                                                    INNER JOIN Funcionarios ON Funcionarios.id = Func_has_Paci.Funcionarios_id
                                                                    LEFT OUTER JOIN Convenio ON Convenio.id = Paciente.Convenio_id
                                                                    LEFT OUTER JOIN Anamnese ON Anamnese.Paciente_id = Paciente.id
                                                                    LEFT OUTER JOIN Medicamento ON Medicamento.Paciente_id = Paciente.id WHERE Paciente.id =$id");

}

public function getAllProntuarioById($id)
{
return $this->entity->selectManager(" SELECT * FROM Paciente INNER JOIN usuarios ON usuarios.id= Paciente.usuarios_id
                                                                    INNER JOIN Func_has_Paci ON Func_has_Paci.Paciente_id = Paciente.id
                                                                    INNER JOIN Funcionarios ON Funcionarios.id = Func_has_Paci.Funcionarios_id
                                                                    LEFT OUTER JOIN Convenio ON Convenio.id = Paciente.Convenio_id
                                                                    LEFT OUTER JOIN Anamnese ON Anamnese.Paciente_id = Paciente.id
                                                                    LEFT OUTER JOIN Docum_Atestado ON Docum_Atestado.Paciente_id = Paciente.id
                                                                    LEFT OUTER JOIN Evolucao ON Evolucao.Paciente_id = Paciente.id
                                                                    LEFT OUTER JOIN Exame_Fisico ON Exame_Fisico.Paciente_id = Paciente.id
                                                                    LEFT OUTER JOIN hipotese_diagnostica ON hipotese_diagnostica.Paciente_id = Paciente.id
                                                                    LEFT OUTER JOIN Medicamento ON Medicamento.Paciente_id = Paciente.id WHERE usuarios.id =$id");
}

/**
 * @param $cote
 * @param $content
 */
public function logAccess($cote, $content)
{
$log = new Log('logActividadeCecretria.txt', 'Log/');
$log->write($cote . "\t" .$content);
}

public function logPaciente($cote, $content)
{
$log = new Log('logOfPaciente.txt', 'Log/');
$log->write($cote . "\t" .$content);
}

public function logMedico($cote, $content)
{
$log = new Log('logOfMedico.txt', 'Log/');
$log->write($cote . "\t" .$content);
}


public function getEvent($id)
{
return $this->entity->selectManager("SELECT Paciente.info, Func_has_Paci.Paciente_id, Paciente.usuarios_id, Paciente.create_dt, Func_has_Paci.id, Func_has_Paci.start, Func_has_Paci.end, Func_has_Paci.title, usuarios.firstname, usuarios.lastname, usuarios.telephone, usuarios.address_1, usuarios.address_2, usuarios.city, usuarios.bairro, usuarios.telephone2, usuarios.sexo, usuarios.email, pic_perfil.path  FROM Paciente LEFT OUTER JOIN Anamnese on Paciente.id = Anamnese.Paciente_id  INNER JOIN Func_has_Paci on  Paciente.id = Func_has_Paci.Paciente_id INNER JOIN Funcionarios ON Funcionarios.id = Func_has_Paci.Funcionarios_id INNER JOIN usuarios ON usuarios.id = Paciente.usuarios_id LEFT OUTER JOIN pic_perfil ON pic_perfil.usuarios_id = usuarios.id WHERE  Anamnese.Paciente_id is null AND Funcionarios.usuarios_id  = $id");
}

public function getEvent2()
{
return $this->entity->selectManager("SELECT Paciente.info, Func_has_Paci.Paciente_id, Paciente.usuarios_id, Paciente.create_dt, Func_has_Paci.id, Func_has_Paci.start, Func_has_Paci.end, Func_has_Paci.title, usuarios.firstname, usuarios.lastname, usuarios.telephone, usuarios.address_1, usuarios.address_2, usuarios.city, usuarios.bairro, usuarios.telephone2, usuarios.sexo, usuarios.email  FROM Paciente LEFT OUTER JOIN Anamnese on Paciente.id = Anamnese.Paciente_id  INNER JOIN Func_has_Paci on  Paciente.id = Func_has_Paci.Paciente_id INNER JOIN Funcionarios ON Funcionarios.id = Func_has_Paci.Funcionarios_id INNER JOIN usuarios ON usuarios.id = Paciente.usuarios_id WHERE  Anamnese.Paciente_id is null ");
}


public function getNumPacientForMedic($id)
{
return $this->entity->selectManager("SELECT COUNT(*) FROM Func_has_Paci INNER JOIN funcionarios ON Func_has_Paci.funcionarios_id = funcionarios.id WHERE Func_has_Paci.funcionarios_id = " .$id);
}

public function getAllConvenio()
{
return $this->entity->find('Convenio', '*');
}

public function getConsulta($id)
{
return $this->entity->selectManager("SELECT Paciente.*, Situacao.situ_nome FROM Paciente LEFT OUTER JOIN Situacao ON Paciente.Situacao_id = Situacao.id where Paciente.usuarios_id= $id AND Paciente.Situacao_id = 1");
}

}
