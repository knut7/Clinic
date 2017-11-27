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

namespace Module\Clinic\Controllers;

use Ballybran\Helpers\Http\Hook;
use Ballybran\Helpers\Security\Session;

/**
 * Created by PhpStorm.
 * User: artphotografie
 * Date: 2016/02/18
 * Time: 7:20 AM
 */


class Galery extends AbstractController {

    private $files;
    private $path;
    private $tmp;
    public $name;
    public $size;
    public $type;

    public function __construct() {

        parent::__construct();

        $this->view->Js = array('Cpanel/Js/default.js');
    }

    /**
     *
     */
    public function index() {

        $this->view->galery = $this->model->getImage();

        $this->view->render($this, 'index');
    }

    public function getImage() {

        $this->imagem->file('../Galeria');
        if (!empty($_POST['legenda'])) {

            $data['type'] = $this->imagem->type;
            $data['size'] = $this->imagem->size;
            $data['path'] = $this->imagem->path;
            $data['name'] = $this->imagem->name;
            $data['legenda'] = $_POST['legenda'];
            $this->model->insertImage($data);
        }
        $this->view->render($this, 'getImage');
    }

    public function editImage() {
        
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     * */
    public function delete($id) {
        if (Session::exist()) {
            if (Session::get('role') == 'owner' || Session::get('role') == 'admin') {
                $file = $this->model->unlinkgetImage($id);
                if (is_array($file)) {
                    foreach ($file as $key => $value) {
                        if (is_array($value)) {
                            if (file_exists($value['path'])) {
                                unlink($value['path']);
                            }
                        }
                    }
                }
                $this->model->delete($id);

                Hook::Header('Galery');
            }
            if (Session::get('role') == 'default') {
                Hook::Header('Galery');
            }
        } else {
            Hook::Header('Galery');
        }
    }

}
