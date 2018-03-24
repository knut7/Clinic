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

use Ballybran\Core\Controller\AbstractController;
use Ballybran\Helpers\Http\Hook;
use Module\Entity\EntyCategory;
use Module\Upload\ImageUpload;
// use Ballybran\Helpers\Event\Registry;
// use Ballybran\Helpers\Http\Hook; 
use Ballybran\Helpers\Images\Resize;
use Ballybran\Helpers\Security\Session;
// use Ballybran\Helpers\Security\Validate;

/**
 *
 * KNUT7 K7F (http://framework.artphoweb.com/)
 * KNUT7 K7F(tm) : Rapid Development Framework (http://framework.artphoweb.com/)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link      http://github.com/zebedeu/artphoweb for the canonical source repository
 * @copyright (c) 2016.  APWEB  Software Technologies AO Inc. (http://www.artphoweb.com)
 * @license   http://framework.artphoweb.com/license/new-bsd New BSD License
 * @author    Marcio Zebedeu - artphoweb@artphoweb.com
 * @version   1.0.0
 */


class Categorias extends AbstractController {
 public $width = 2000;
    public $height = 2000;
    public $quality = 10;
    public $option = "perfil";
    private $form;
    private $imagem;

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->view->Allcat = $this->model->_allCategorias();
        $this->view->render($this, 'index');
    }

    public function getCategoryById($idName, $id = null) {

        $this->view->title = "";
        $nameCat = str_replace("-", " ", $idName);
        $this->view->category = $this->model->category($nameCat);
        $this->view->categoryByNameTitle = $this->model->categoryByNameTitle($idName);
        if (!empty($id)) {
            $this->view->article = $this->model->getArcticleBycategory($id)[0];
        }
        $this->view->render($this, 'Category');
    }

    public function createCategory() {
        if (Session::exist()) {
            if (Session::get('role') == 'owner') {
                $this->view->title = "Add Categoria";
                $this->view->delete = $this->model->_allCategorias();

                $this->view->render($this, 'createCategory');
            }
        } else {
            Hook::Header('');
        }
    }

    public function insertCategory() {

            $this->imagem = new \Ballybran\Helpers\Http\FileSystem( new Resize() );
            $this->imagem->setWidth(2000);
            $this->imagem->setHeight(2000);
            $this->imagem->setOption("exact");
            $this->imagem->setQuality(100);
            $this->imagem->setColor("FFFFFF");
            $this->imagem->setDegree(00);

        $img = new ImageUpload();
        $this->imagem->file('categorias');
        $img->setName($this->imagem->name);
        $img->setPath($this->imagem->path);
        $img->setSize($this->imagem->size);
        $img->setType($this->imagem->type);

        $cate = new EntyCategory();
        $cate->setNome( $_POST['nome']);
        $cate->setDescription( $_POST['description']);
        $data['path'] = $img->getPath();
        $data['name'] = $img->getName();
        $data['type'] = $img->getType();
        $data['size'] = $img->getSize();
        $data['nome'] = $cate->getNome();
        $data['description'] = $cate->getDescription();

        $this->model->createCategory($data);

        Hook::Header('categorias/createcategory');

    }

    public function deleteCategory($id) {
        if (Session::exist()) {
            if (Session::get('role') == 'owner') {
              $file =   $this->model->deleteCategory($id);
                Hook::Header('Categorias/createCategory');
            }
        } else {
            Hook::Header('');
        }
    }

}
