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
 * Time: 06:13
 */

namespace Module\Clinic\Controllers;


use Ballybran\Core\Controller\AbstractController;
use Ballybran\Helpers\{
    Copyright\Copyright, Event\Registry, Http\Hook, Images\Resize, Security\Session, Security\Validate
};
use Module\Clinic\Controllers\CTrait\Prints;


class Finance  extends AbstractController {

use Prints;

    private $form;
    private $form2;
    private $form3;

    public function __construct()
    {
        parent::__construct();
        $this->form = new Validate();
        $this->form->setMethod('POST');
    }

    public function index()
    {
        if(Session::exist() ){
            if(Session::get("role") == "owner" || Session::get("role") == "admin" ) {

        $this->view->typeDesp = $this->model->getTypeDespesa();
        $this->view->getPagamento = $this->model->getAllPagamento();
        $this->view->contasPagas = $this->model->getAllContasPagas();
        $this->view->formaPagamento = $this->model->getFormPagameto();
        $this->view->moviento = $this->model->getContaMovimento();
        $this->view->pagar = $this->model->getPagarmento();
        $this->view->saldo = $this->model->getContaMovimento();
        $this->view->debito = $this->model->getDebito();
        $this->view->receber = $this->model->getContaReceber();
        $this->view->totalCrebito = $this->model->sumTotalContaReceber()[0];
        $this->view->totalDebito = $this->model->sumTotalDebito()[0];
        $this->view->totalAllPagamentos = $this->model->sumAllPagamento()[0];
        $this->view->totatDeAllContasPagas = $this->model->sumAllContasPgas()[0];
        $this->view->render($this, 'index');
        
        }else {
                            Hook::Header( 'account/cpanel');

        }
    }else{
                Hook::Header( '');
            }



    }

    public function contApagar()
    {

        $this->form->post('dt_Venc')->val('maxlength', 100)
            ->post('valor')->val('maxlength', 100)
            ->post('situacao')->val('maxlength', 100)
            ->post('historico')->val('maxlength', 100)
            ->post('TipoDespesa_id')->val('maxlength', 111)->submit();

       $this->model->insertContaApagar($this->form->getPostData());
       Hook::Header( 'finance');
        
    }
    public function contaAreceber()
    {

        $this->form->post('dt_Venc')->val('maxlength', 100)
            ->post('valor')->val('maxlength', 100)
            ->post('situacao')->val('maxlength', 100)
            ->post('Paciente_id')->val('maxlength', 100)
            ->post('TipoReceita_id')->val('maxlength', 111)->submit();


        $this->model->insertContaAreceber($this->form->getPostData());
        Hook::Header( 'finance');

    }

    public function insertCredito()
    {

        $this->form->post('dtPag')->val('maxlength', 100)
            ->post('CreditoValor')->val('maxlength', 100)
            ->post('ContaMovimento_id')->val('maxlength', 100)
            ->post('Paciente_id')->val('maxlength', 100)
            ->post('Especialidade_id')->val('maxlength', 100)
            ->post('FormaPagamento_id')->val('maxlength', 111)->submit();


        $this->form2 = new Validate();
        $this->form2->setMethod('POST');
        $this->form3 = new Validate();
        $this->form3->setMethod('POST');




        if(!empty($_POST['Paciente_id']) && !empty($_POST['Funcionarios_id']) && !empty($_POST['Func_dt']) && !empty($_POST['start']) && !empty($_POST['Situacao_id']) ) {
            $data['Situacao_id'] = $_POST['Situacao_id'];
            $dataa['Funcionarios_id'] = $_POST['Funcionarios_id'];
            $dataa['Func_dt'] = $_POST['Func_dt'];
            $dataa['start'] = $_POST['start'];
            $dataa['Paciente_id'] = $_POST['Paciente_id'];
            $this->model->joinPacienteAndFunc($dataa);
            $this->model->updateSituacao($data, $_POST['Paciente_id']);

        }




        $this->model->insertCredito($this->form->getPostData());

        Hook::Header('account/cpanel');

    }

    public function tipoDeDespesa()
    {
       $this->form->post('Dnome')->val('maxlength', 110)->submit();

        $this->model->TipoDespesa($this->form->getPostData());
        Hook::Header( 'finance');
    }
    public function debitar()
    {
    
        if(!empty($_POST['Pagar_id'])){
            $data['situacao'] = $_POST['situacao'];
            $this->form->post('dtPag')->val('maxlength', 100)
            ->post('FormaPagamento_id')->val('maxlength', 100)
            ->post('Pagar_id')->val('maxlength', 100)
            ->post('ContaMovimento_id')->val('maxlength', 11)->submit();

            // var_dump($data);
            // var_dump($_POST['Pagar_id']); die;
        $this->model->insertDebitar($this->form->getPostData());
        $this->model->updateSituacaoPagar($data, $_POST['Pagar_id']);
    }

        // if(!empty($_POST['id']))
        // $this->model->deletePagar($_POST['id']);
        Hook::Header( 'finance');

    }

    public function insertSituacao()
    {
        $this->form->post('situ_nome')->val('maxlength', 100);
        $this->model->insertSituacao($this->form->getPostData());
    }


}