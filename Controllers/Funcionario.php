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
use Ballybran\Helpers\Event\Registry;
use Ballybran\Helpers\Http\Hook;
use Ballybran\Helpers\Security\Session;
use Ballybran\Helpers\Security\Validate;
use Ballybran\Helpers\vardump\Vardump;
use Module\Lib\Notificacao;

class Funcionario extends AbstractController
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new Validate();
        $this->form->setMethod('POST');
    }


    public function index() {
        Hook::Header('');

    }

    public function pacienteAll()
    {
        if (Session::exist()) {
            if (Session::get('role') == 'funcionario') {
                $this->view->pacientes = $this->model->allPacienteByFuncionario(Session::get('ID'));
                $this->view->render($this, 'lista-paciente');
            } else {
                Hook::Header('account/painel');
            }
        } else {
            Hook::Header('');
        }

    }


    public function prontuario($id = null)
    {
        if (Session::exist()) {
            if (Session::get('role') == "secretaria" || Session::get('role') == "funcionario") {
                if (is_null($id)) {
                    Hook::Header(" ");

                }
                $this->view->getMedicamento = $this->model->getMedicamentoByPaciente($id)[0];
                $this->view->getReceitas = $this->model->getReceitasByPaciente($id);
                $this->view->getExame = $this->model->getExamesByPaciente($id);
                $this->view->paciente = $this->model->getPacienteById($id);
                $this->view->medical = $this->model->getMedicamentosByPaciente($id);
                $this->view->render($this, 'medicamentos');
            } else {
                Hook::Header("");
            }
        }
    }


    public function inserMedicamento()
    {
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

    public function inserirReceita()
    {
        $this->form->post('dt_receita')->val('maxlength', 123)
            ->post('Medicamento_med_id')->val('maxlength', 123)
            ->post('posologia')->val('maxlength', 123)
            ->post('exame_id')->val('maxlength', 123)
            ->post('observacao')->val('maxlength', 123)->submit();

        $this->model->inserirReceita($this->form->getPostData());
        Hook::Header('');


    }

    public function inserirExame()
    {
        $this->form->post('Paciente_id')->val('maxlength', 1223)
            ->post('nome')->val('maxlength', 122)
//            ->post('resultado')->val('maxlength', 122)
            ->post('data')->val('maxlength', 12)->submit();

        $this->model->inserirExame($this->form->getPostData());

    }

}