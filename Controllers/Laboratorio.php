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

namespace Module\Clinic\Controllers;

use Ballybran\Core\Controller\AbstractController;
use Ballybran\Helpers\Http\Hook;
use Ballybran\Helpers\Security\Session;
use Ballybran\Helpers\Security\Validate;

class Laboratorio extends AbstractController
{

    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new Validate();
    }

    public function index()
    {
        if(Session::exist()) {
            if(Session::get('role')== 'laboratorio') {

                $this->view->title = "Laboratorio | Exame";
                $this->view->list = $this->model->getListaDeExames();
                $this->view->render($this, 'index');
            } else {
                Hook::Header("account/cpanel");
            }
        }else {
            Hook::Header("user/login");
        }

    }

    public function exame($id) {
        if(Session::exist()) {
            if(Session::get('role')== 'laboratorio') {

                $this->view->title = "Laboratorio | Exame";
        $this->view->getExame = $this->model->getExamesByPaciente($id);
        $this->view->paciente = $this->model->getPacienteById($id);

        $this->view->render($this, 'exame');
            } else {
                Hook::Header("account/cpanel");
            }
        }else {
            Hook::Header("user/login");
        }
    }


    public function inserirExame()
    {
        $this->form->post('Receitas_id')->val('maxlength', 1223)
            ->post('nome')->val('maxlength', 122)
            ->post('resultado')->val('maxlength', 122)
            ->post('data')->val('maxlength', 12)->submit();

        $this->model->inserirExame($this->form->getPostData());

    }

    public function updateExame()
    {
        if($_POST['id_ex']) {
            $data['resultado'] = $_POST['resultado'];
            $this->model->updateExame($data, $_POST['id_ex']);
            Hook::Header("Laboratorio");
        }
    }

}