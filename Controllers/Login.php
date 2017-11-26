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

namespace Module\Clinica\Controllers;

use Ballybran\Core\Controller\AbstractController;


class Login extends AbstractController {

    public function __construct() {
        parent::__construct();

        $this->view->Js = array('Login/Js/default.js');
    }

    public function index() {
        $this->view->title = 'ENTRAR';
        $this->view->render($this, 'index');
    }

}
