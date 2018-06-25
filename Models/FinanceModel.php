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
 * Date: 06/12/17
 * Time: 06:15
 */

namespace Module\Clinic\Models;

use Ballybran\Database\Drives\AbstractDatabaseInterface;

class FinanceModel {

    /**
     * @var AbstractDatabaseInterface
     */
    private $database;

    public function __construct(AbstractDatabaseInterface $database) {
        $this->database = $database;
    }

    public function getTypeDespesa() {
        return $this->database->find('TipoDespesa', '*');
    }

    public function getFormPagameto() {
        return $this->database->find('FormaPagamento', '*');
    }

    public function getPagarmento() {
        return $this->database->find('Pagar', '*', "Pagar.situacao = 'ABERTO' ");
    }

    public function insertContaApagar($data) {
        $this->database->insert('Pagar', $data);
    }

    public function insertContaAreceber($data) {
        $this->database->insert('Receber', $data);
    }

    public function getAllPagamento() {
        return $this->database->selectManager("SELECT Pagar.*, TipoDespesa.* FROM Pagar INNER JOIN TipoDespesa ON Pagar.TipoDespesa_id = TipoDespesa.id WHERE Pagar.situacao = 'ABERTO' ORDER BY Pagar.id DESC");
    }

    public function getAllContasPagas() {
        return $this->database->selectManager("SELECT Pagar.*, TipoDespesa.* FROM Pagar INNER JOIN TipoDespesa ON Pagar.TipoDespesa_id = TipoDespesa.id WHERE Pagar.situacao = 'FECHADO' ORDER BY Pagar.id DESC");
    }

    public function getContaMovimento() {
        return $this->database->find('ContaMovimento', '*');
    }

    public function getContaReceber() {
        return $this->database->selectManager("SELECT Credito.id, usuarios.firstname, usuarios.lastname, Credito.CreditoValor, Credito.dtPag, ContaMovimento.Cnome, FormaPagamento.FPnome, Especialidade.espNome, Especialidade.espValor, Situacao.situ_nome, TipoReceita.TRNome FROM Credito INNER JOIN Paciente ON Credito.Paciente_id = Paciente.id INNER JOIN ContaMovimento ON ContaMovimento.id = Credito.ContaMovimento_id INNER JOIN FormaPagamento ON FormaPagamento.id = Credito.FormaPagamento_id INNER JOIN Especialidade on Especialidade.id = Credito.Especialidade_id INNER JOIN TipoReceita ON TipoReceita.id = Especialidade.TipoReceita_id INNER JOIN Situacao ON Situacao.id = Paciente.Situacao_id INNER JOIN usuarios ON usuarios.id = Paciente.usuarios_id ORDER BY id DESC");
    }

    public function sumTotalContaReceber() {
        return $this->database->selectManager("SELECT  SUM(Especialidade.espValor) as total FROM Credito INNER JOIN Paciente ON Credito.Paciente_id = Paciente.id INNER JOIN ContaMovimento ON ContaMovimento.id = Credito.ContaMovimento_id INNER JOIN FormaPagamento ON FormaPagamento.id = Credito.FormaPagamento_id INNER JOIN Especialidade ON Especialidade.id = Credito.Especialidade_id INNER JOIN Situacao ON Situacao.id = Paciente.Situacao_id INNER JOIN usuarios ON usuarios.id = Paciente.usuarios_id ");
    }

    public function insertDebitar($data) {
        $this->database->insert('Debito', $data);
    }

    public function TipoDespesa($data) {
        $this->database->insert('TipoDespesa', $data);
    }

    public function insertCredito($data) {
        $this->database->insert('Credito', $data);
    }

    public function joinPacienteAndFunc($data) {
        $this->database->insert('Func_has_Paci', $data);
    }

    public function insertSituacao() {
//        return $this->database->insert("Situacao");
    }

    public function updateSituacaoPagar($data, $id) {
        $this->database->update("Pagar", $data, "id=$id");
    }

    public function updateSituacao($data, $id) {
        $this->database->update('Paciente', $data, "id=$id");
    }

    public function getDebito() {
        return $this->database->selectManager("SELECT Debito.id, Debito.db_valor, Debito.dtPag, FormaPagamento.FPnome, ContaMovimento.Cnome, Pagar.dt_Venc, Pagar.historico, Pagar.situacao, Pagar.valor FROM Debito INNER JOIN ContaMovimento ON Debito.ContaMovimento_id = ContaMovimento.id  INNER JOIN FormaPagamento ON FormaPagamento.id = Debito.FormaPagamento_id  INNER JOIN Pagar ON Pagar.id = Debito.Pagar_id INNER JOIN TipoDespesa ON Pagar.TipoDespesa_id = TipoDespesa.id ORDER BY id DESC ");
    }

    public function sumTotalDebito() {
        return $this->database->selectManager("SELECT  SUM(Pagar.valor) as total FROM Debito INNER JOIN ContaMovimento ON Debito.ContaMovimento_id = ContaMovimento.id  INNER JOIN FormaPagamento ON FormaPagamento.id = Debito.FormaPagamento_id  INNER JOIN Pagar ON Pagar.id = Debito.Pagar_id INNER JOIN TipoDespesa ON Pagar.TipoDespesa_id = TipoDespesa.id");
    }

    public function sumAllPagamento() {
        return $this->database->selectManager("SELECT  SUM(Pagar.valor) as total FROM Pagar INNER JOIN TipoDespesa ON Pagar.TipoDespesa_id = TipoDespesa.id WHERE Pagar.situacao = 'ABERTO' ");
    }

    public function sumAllContasPgas() {
        return $this->database->selectManager("SELECT  SUM(Pagar.valor) as total FROM Pagar INNER JOIN TipoDespesa ON Pagar.TipoDespesa_id = TipoDespesa.id WHERE Pagar.situacao = 'FECHADO' ");
    }

    public function deletePagar($id) {
        $this->database->delete('Pagar', $id, 1);
    }

    public function criarBanco($data) {
        return $this->database->insert("ContaMovimento", $data);
    }

}
