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
 * Date: 16/12/17
 * Time: 12:01
 */

namespace Module\Clinic\Controllers\CTrait;

use Module\Clinic\{
    Controllers\CTrait\Updates, Lib\PacienteAtendido, Lib\PrintListFuncionario, Lib\Prontuario, Lib\PrintListPacient, Lib\PrintListOfAllPacient, Lib\PrintListPacientPayment, Lib\PrintConsult, Lib\AllProntuario, Lib\Prescricao, Lib\ContasPagas, Lib\ContasAPagar, Lib\ContasAReceber
};
use Ballybran\Helpers\{
    Copyright\Copyright,  Security\Session
    };
trait Prints
{

 public function pacienteAtendido()
    {
     $pdf = new PacienteAtendido();
        $pdf->getList($this->model->getPacientForAtendimento(Session::get("ID")));
        $pdf->AliasNbPages();
        $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC" ));
        $pdf->AddPage();
        $pdf->body();
        $pdf->Output();
        $this->model->logAccess( Session::get("U_NAME"), ":: Imprimiu uma lista de Pacientes consultados ");

    }

    public function printConsulta()
    {
        $pdf = new PrintConsult();
        $pdf->getList($this->model->getConsultas());
        $pdf->AliasNbPages();
        $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC" ));
        $pdf->AddPage();
        $pdf->body();
        $pdf->Output();
    }

    public function printProntuario($id)
    {

        $pdf = new  Prontuario();
        $pdf->getId($this->model->getAllInfoForPrint($id));
        $pdf->AliasNbPages();
        $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC" ));
        $pdf->AddPage();
        $pdf->Output();
        $this->model->logPaciente( Session::get("U_NAME"), ":: Imprimiu um Prontuario.");

    }

public function printProntuarios()
    {

        $pdf = new  AllProntuario();
      
        $pdf->getId($this->model->getAllProntuarioById(Session::get("ID")));
        $pdf->AliasNbPages();
        $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC" ));
        $pdf->Output();
        $this->model->logPaciente( Session::get("U_NAME"), ":: Imprimiu todos os Prontuarios.");

    }

    public function printPrescricao($id) {

        $pdf = new  Prescricao();
        $pdf->getId($this->model->getAllPrescricaoForPrint($id));
        $pdf->AliasNbPages();
        $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC" ));
        $pdf->AddPage();
        $pdf->Output();
        $this->model->logPaciente( Session::get("U_NAME"), ":: Imprimiu um Prontuario.");
    }
    public function printListPaciente()
    {

        $pdf = new  PrintListPacient();

        $pdf->getList($this->model->getPacientForAtendimento(Session::get("ID")));
        $pdf->AliasNbPages();
        $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC" ));
        $pdf->AddPage();
        $pdf->body();
        $pdf->Output();
        $this->model->logAccess( Session::get("U_NAME"), ":: Imprimiu uma lista de Pacientes consultados ");

    }

    public function printListPacienteForConsult()
    {

        $pdf = new  PrintListPacient();
        $pdf->getList($this->model->getPacientForConsulta(Session::get("ID")));
        $pdf->AliasNbPages();
        $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC" ));
        $pdf->AddPage();
        $pdf->body($this->model->getUser(Session::get("ID"))[0]);
        $pdf->Output();
        $this->model->logAccess( Session::get("U_NAME"), ":: Imprimiu uma lista de Pacientes sem Consulta Agendadas.");

    }

    public function printAllPacient()
    {
        $pdf = new  PrintListOfAllPacient();
        $pdf->getList($this->model->getPacienteSemMedico());
        $pdf->AliasNbPages();
        $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC" ));
        $pdf->AddPage();
        $pdf->body();
        $pdf->Output();
        $this->model->logAccess( Session::get("U_NAME"), ":: Imprimiu uma lsta de Pacientes sem Consulta Agendadas.");


    }

    public function printAllPacientPayment()
    {
        $pdf = new  PrintListPacientPayment();
        $pdf->getList($this->model->getAllPaciente());
        $pdf->AliasNbPages();
        $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC" ));
        $pdf->AddPage();
        $pdf->body();
        $pdf->Output();
        $this->model->logAccess( Session::get("U_NAME"), ":: Imprimiu uma lsta de Pacientes com a Consulta paga. ");


    }

    public function printlistFuncionario()
    {
        if(Session::exist() ) {
            if (Session::get('role') == 'owner' || Session::get('role') == 'admin') {
                $pdf = new  PrintListFuncionario();
                $pdf->getList($this->model->getAllUserAdmin());
                $pdf->AliasNbPages();
                $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC"));
                $pdf->AddPage();
                $pdf->body();
                $pdf->Output();

                $this->model->logAccess( Session::get("U_NAME"), ":: Imprimiu uma lsta de Funcionarios.");
            } else {
                Hook::Header("account/cpanel");
            }
        }else {
            Hook::Header("");
        }
    }

    public function printContasPagas()
    {
         if(Session::exist() ) {
            if (Session::get('role') == 'owner' || Session::get('role') == 'admin') {
                $pdf = new  ContasPagas();
                $pdf->getAllContasPagas($this->model->getAllContasPagas());
                $pdf->totatDeAllContasPagas($this->model->sumAllContasPgas()[0]);
                $pdf->AliasNbPages();
                $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC"));
                $pdf->AddPage();
                $pdf->body();
                $pdf->Output();

                $this->model->logAccess( Session::get("U_NAME"), ":: Imprimiu uma lsta de Funcionarios.");
            } else {
                Hook::Header("account/cpanel");
            }
        }else {
            Hook::Header("");
        }

    }

        public function printContasAPagar()
    {
         if(Session::exist() ) {
            if (Session::get('role') == 'owner' || Session::get('role') == 'admin') {
                $pdf = new  ContasAPagar();
                $pdf->getPagamento($this->model->getAllPagamento());
                $pdf->totalAllPagamentos($this->model->sumAllPagamento()[0]);
                $pdf->AliasNbPages();
                $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC"));
                $pdf->AddPage();
                $pdf->body();
                $pdf->Output();

                $this->model->logAccess( Session::get("U_NAME"), ":: Imprimiu uma lsta de Funcionarios.");
            } else {
                Hook::Header("account/cpanel");
            }
        }else {
            Hook::Header("");
        }

    }

    public function printContasAreber()
    {
         if(Session::exist() ) {
            if (Session::get('role') == 'owner' || Session::get('role') == 'admin') {
                $pdf = new  ContasAReceber();
                $pdf->receber($this->model->getContaReceber());
                $pdf->totalCrebito($this->model->sumTotalContaReceber()[0]);
                $pdf->totalDebito($this->model->sumTotalDebito()[0]);
                $pdf->AliasNbPages();
                $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC"));
                $pdf->AddPage();
                $pdf->body();
                $pdf->Output();

                $this->model->logAccess( Session::get("U_NAME"), ":: Imprimiu uma lsta de Funcionarios.");
            } else {
                Hook::Header("account/cpanel");
            }
        }else {
            Hook::Header("");
        }

    }

}