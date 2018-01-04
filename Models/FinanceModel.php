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

    public function __construct(AbstractDatabaseInterface  $database)
    {
        $this->database = $database;
    }

    public function getTypeDespesa(){
        return $this->database->find('TipoDespesa', '*');
    }
    public function getFormPagameto(){
        return $this->database->find('FormaPagamento', '*');
    }

    public function getPagarmento(){
        return $this->database->find('Pagar', '*');
    }

    public function insertContaApagar($data)
    {
        $this->database->insert('Pagar', $data);
    }
    public function insertContaAreceber($data)
    {
        $this->database->insert('Receber', $data);
    }

    public function getAllPagamento(){
        return $this->database->selectManager("SELECT Pagar.*, TipoDespesa.* FROM Pagar INNER JOIN TipoDespesa ON Pagar.TipoDespesa_id = TipoDespesa.id" );
    }
    public function getContaMovimento(){
        return $this->database->find('ContaMovimento', '*');

    }

    public function insertDebitar($data)
    {
        $this->database->insert('Debito', $data);

    }

    public function insertCredito($data)
    {
        $this->database->insert('Credito', $data);

    }
    public function joinPacienteAndFunc($data)
    {
        $this->database->insert('Func_has_Paci', $data);
    }


    public function insertSituacao()
    {
        $this->database->insert("Situacao");

    }

    public function updateSituacao($data, $id)
    {
        $this->database->update( 'Paciente', $data, "id=$id");
    }

    public function getDebito()
    {
        return $this->database->selectManager("SELECT * FROM Debito INNER JOIN FormaPagamento ON Debito.FormaPagamento_id = FormaPagamento.id INNER JOIN ContaMovimento ON Debito.ContaMovimento_id = ContaMovimento.id RIGHT OUTER JOIN Pagar ON Debito.Pagar_id = Pagar.id INNER JOIN TipoDespesa ON TipoDespesa.id = Pagar.TipoDespesa_id");

    }

}