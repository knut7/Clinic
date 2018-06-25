<?php

/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 08/05/18
 * Time: 16:17
 */

namespace Module\Clinic\Controllers;

use Ballybran\Core\Controller\AbstractController;

class Convenio extends AbstractController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->view->title = "Convenio";
        $this->view->convenio = $this->model->getAllConvenio();
        $this->view->render($this, 'index');
    }

}
