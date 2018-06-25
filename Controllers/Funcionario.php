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
 * Time: 12:13
 */

namespace Module\Clinic\Controllers;

use Ballybran\Core\Controller\AbstractController;
use Ballybran\Helpers\Http\Hook;

use Ballybran\Helpers\ {
    Security\Session, Security\Validate, Security\Val
};

class Funcionario extends AbstractController {

    private $form;

    public function __construct() {
        parent::__construct();

        $this->form = new Validate(new Val);
        $this->form->setMethod('POST');
    }

    public function index() {
        Hook::Header('');
    }

    public function pacienteAll() {
        if (Session::exist()) {
            if (Session::get('role') == 'medico') {
                $this->view->pacientes = $this->model->allPacienteByFuncionario(Session::get('ID'));
                $this->view->render($this, 'lista-paciente');
            } else {
                Hook::Header('account/painel');
            }
        } else {
            Hook::Header('');
        }
    }

    public function prontuario($id = null) {
        $this->view->title = "Atendimento";
        if (Session::exist()) {
            if (Session::get('role') == "secretaria" || Session::get('role') == "medico") {
                if (is_null($id)) {
                    Hook::Header(" ");
                }
                $this->view->UserPhoto = $this->model->getImageUser($id);

//                $this->view->getMedicamento = $this->model->getMedicamentoByPaciente($id)[0];
                $this->view->getReceitas = $this->model->getReceitasByPaciente($id);
                $this->view->getExame = $this->model->getExamesByPaciente($id);
                $this->view->paciente = $this->model->getPacienteById($id)[0];
                $this->view->medical = $this->model->getMedicamentosByPaciente($id);
                $this->view->render($this, 'add-atendimento');
            } else {
                Hook::Header("");
            }
        }
    }


    public function internamento($id = null)
    {
       $this->view->title = "Internação";
       $this->view->UserPhoto = $this->model->getImageUser($id);
       $this->view->paciente = $this->model->getPacienteByInternamento($id)[0];
       $this->view->internados = $this->model->getPacientInternados(Session::get("ID"));
       $this->view->problema = $this->model->getProblemaByPaciente($id);

       $this->view->render($this, 'internamento');
    }

    public function inserirInternamento()
    {
        $this->form->post('Paciente_id')->val('maxlength', 1223)
                ->post('Funcionarios_id')->val('maxlength', 400)
                ->post('UnidadeInternacao_id')->val('maxlength', 400)
                ->post('TipoAdmissao_id')->val('maxlength', 400)
                ->post('notaEntrada')->val('maxlength', 50000)
                ->post('interData')->val('maxlength', 400)
                ->post('medicoResponsavel')->val('maxlength', 400)
                ->post('DiagnClinico')->val('maxlength', 400);

                // var_dump($this->form->getPostData()); die;

                $this->model->inserirInternamento($this->form->getPostData());
                Hook::Header("account/cpanel");
        
    }

  
    public function inserirProblema()
    {
        $this->form->post('internamento_id')->numeric()->val('maxlength', 1223)
        ->post('problema')->text()->val('maxlength', 50000)
        ->post('titulo')->text()->val('maxlength', 50000)
        ->post('probData')->val('maxlength', 400);

        $this->model->inserirProblema($this->form->getPostData());
        Hook::Header("funcionario");
    }

    public function insertAnamnese() {
        $this->form->post('Paciente_id')->val('maxlength', 1223)
                ->post('Funcionarios_id')->val('maxlength', 12)
                ->post('queixa_pri')->val('maxlength', 400)
                ->post('historia')->val('maxlength', 5000)
                ->post('prob_renais')->val('maxlength', 123)
                ->post('prob_artic_reum')->val('maxlength', 123)
                ->post('prob_cardiaco')->val('maxlength', 123)
                ->post('prob_respira')->val('maxlength', 123)
                ->post('prob_gastricos')->val('maxlength', 123)
                ->post('alergia')->val('maxlength', 123)
                ->post('hepatite')->val('maxlength', 123)
                ->post('gravides')->val('maxlength', 123)
                ->post('diabete')->val('maxlength', 123)
                ->post('data')->val('maxlength', 123)
                ->post('uso_de_medicam')->val('maxlength', 123)->submit();

        $this->model->insertAnamnese($this->form->getPostData());
        Hook::Header("account/cpanel");
    }

    public function insertExameFisico() {

        if (!empty($_POST['Paciente_id'])) {
            $this->form->post('altura')->numeric()->val('maxlength', 1223)
                    ->post('peso')->numeric()->val('maxlength', 123)
                    ->post('Paciente_id')->numeric()->val('maxlength', 123)
                    ->post('Funcionarios_id')->numeric()->val('maxlength', 123)
                    ->post('freq_cardiaca')->val('maxlength', 123)
                    ->post('press_arte_sistolica')->numeric()->val('maxlength', 123)
                    ->post('press_arte_diastolica')->numeric()->val('maxlength', 123)
                    ->post('obs_gerais')->text()->val('maxlength', 5000)->submit();
            $this->model->insertExameFisico($this->form->getPostData());
        }
        Hook::Header("account/cpanel");
    }

    public function insertHipoteseDiagnostico() {
        $this->form->post('Paciente_id')->val('maxlength', 1223)
                ->post('Funcionarios_id')->val('maxlength', 12)
                ->post('diagnostico')->val('maxlength', 12)
                ->post('diag_obs')->val('maxlength', 5000)->submit();


        $this->model->insertHipoteseDiagnostico($this->form->getPostData());
        Hook::Header("account/cpanel");
    }

    public function insertEvolucao() {
        $this->form->post('Paciente_id')->val('maxlength', 1223)
                ->post('Funcionarios_id')->val('maxlength', 12)
                ->post('evolucao')->val('maxlength', 5000)->submit();

        $this->model->insertEvolucao($this->form->getPostData());
        Hook::Header("account/cpanel");
    }

    public function inserMedicamento() {
        $this->form->post('Funcionarios_id')->val('maxlength', 1223)
                ->post('Paciente_id')->val('maxlength', 12)
                ->post('generico')->val('maxlength', 123)
                ->post('comercial')->val('maxlength', 123)
                ->post('dose')->val('maxlength', 123)
                ->post('via_admin')->val('maxlength', 123)
                ->post('interval')->val('maxlength', 123)
                ->post('inicio')->val('maxlength', 123)
                ->post('final')->val('maxlength', 123)->submit();


        $this->model->inserMedicamento($this->form->getPostData());
        Hook::Header("account/cpanel");
    }

    public function inserirReceita() {
        $this->form->post('dt_receita')->val('maxlength', 123)
                ->post('Medicamento_med_id')->val('maxlength', 123)
                ->post('posologia')->val('maxlength', 123)
                ->post('exame_id')->val('maxlength', 123)
                ->post('observacao')->val('maxlength', 5000)->submit();

        $this->model->inserirReceita($this->form->getPostData());
        Hook::Header('');
    }

    public function inserirExame() {
        $this->form->post('Paciente_id')->val('maxlength', 1223)
                ->post('nome')->val('maxlength', 122)
//            ->post('resultado')->val('maxlength', 122)
                ->post('data')->val('maxlength', 12)->submit();

        $this->model->inserirExame($this->form->getPostData());
    }

    // public function joinPacienteAndFunc()
    // {
    //       if(!empty($_POST['Paciente_id']) && !empty($_POST['Funcionarios_id']) && !empty($_POST['Func_dt']) && !empty($_POST['start']) ) ) {
    //         $dataa['Funcionarios_id'] = $_POST['Funcionarios_id'];
    //         $dataa['Func_dt'] = $_POST['Func_dt'];
    //         $dataa['start'] = $_POST['start'];
    //         $dataa['Paciente_id'] = $_POST['Paciente_id'];
    //           $this->form->post('altura')->val('maxlength', 1223)
    //         ->post('Funcionarios_id')->val('maxlength', 2222)
    //         ->post('Paciente_id')->val('maxlength', 2222)
    //         ->post('peso')->val('maxlength', 123)
    //         ->post('freq_cardiaca')->val('maxlength', 123)
    //         ->post('press_arte_sistolica')->val('maxlength', 123)
    //         ->post('press_arte_diastolica')->val('maxlength', 123)
    //         ->post('obs_gerais')->val('maxlength', 123)->submit();
    //         var_dump($this->form->getPostData());
    //         $c[] = $this->model->insertExameFisico($this->form->getPostData() );
    //         $[] =$this->model->joinPacienteAndFunc($dataa);
    //         var_dump($c);
    // }
}
