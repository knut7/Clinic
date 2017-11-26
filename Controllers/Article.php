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
use Ballybran\Helpers\Http\Hook;
use Ballybran\Helpers\Security\Session;
use Ballybran\Helpers\vardump\Vardump;
use function date;

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


class Article extends AbstractController {

    private $path;
    public $name;
    public $size;
    public $type;
    /**
     * Article constructor.
     * call file js default
     */
    public $public;

    public function __construct() {
        parent::__construct();

        $this->view->Js = array('Article/Js/default.js');
    }

    /**
     * @param $id responsavel para buscar artigos pelo id na base de dados
     */
    public function index() {

        $this->view->title = "Artigos";

        /** @var int $id */
        $this->view->public = $this->model->_getAllArticle();
        $this->view->render($this, 'index');
//        $background = 'img/background.jpg';
//        $image = new \FWAP\Library\Images\Image($background, 100, 100);
//        $d = $image->saveImage('Image/teste.gif', 100);
//
//        var_dump($d);
    }

    public function art_pre_post($id) {
        $this->view->title = "Artigos";

        /** @var int $id */
        $this->view->public = $this->model->_getArticleById($id);
        $this->view->comments = $this->model->getComments($id);
        $this->view->render($this, 'art_pre_post');
    }

    public function publish() {
      if( Session::exist() ){
            if (Session::get('role') == 'owner' || Session::get('role') == 'admin') {

                $this->view->title = 'Publish';
                $this->view->cate = $this->model->_allCategorias();
                $this->view->delete = $this->model->_getAllArticle();

            } else {
                Hook::Header('');
            }
        $this->view->render($this, 'publish');
            }else {
          Hook::Header('');
      }

    }

    public function deleteArticle($id) {
        if (Session::exist()) {
            if (Session::get('role') == 'owner') {
                $this->model->deleteArticle($id);
                Hook::Header('article/publish');
            }
        } else {
            Hook::Header('');
        }
    }

    public function insertArticle() {
        $this->imagem->file('article');
        if (!empty($_POST['title']) && !empty($_POST['excerpt']) && !empty($_POST['content']) && !empty($_POST['post_date']) && !empty($_POST['id_cat'])) {
//            $data['type'] = $this->imagem->type;
//            $data['size'] = $this->imagem->size;
//            $data['path'] = $this->imagem->path;
//            $data['name'] = $this->imagem->name;
            $data['title'] = $_POST['title'];
            $data['excerpt'] = $_POST['excerpt'];
            $data['content'] = $_POST['content'];
            $data['post_date'] = date('Y-m-d H:i:s');
            $data['id_cat'] = $_POST['id_cat'];


            $this->model->insertArticle($data);
            Hook::Header('');
        } else {
            Hook::Header('');
        }
    }

    public function editPublish() {
        if ($_POST['id_article']) {
            $data['title'] = $_POST['title'];
            $data['excerpt'] = $_POST['excerpt'];
            $data['content'] = $_POST['content'];
            $data['post_date'] = date('Y-m-d H:i:s');
            $data['id_cat'] = $_POST['id_cat'];

            return $this->model->editPublish($data, $_POST['id_article']);
        } else {
            echo "nao foi editado o poste";
        }
    }

    public function editar($id) {
        $this->view->publico = $this->model->_getAllArticleById($id)[0];
        $c = $this->view->cate = $this->model->_allCategorias();
        $this->view->render($this, 'edit_post');
    }

    public function insertComments() {
        if (!empty($_POST['nome']) && !empty($_POST['post_date']) && !empty($_POST['article_id']) && !empty($_POST['comments'])) {

            $data['nome'] = $_POST['nome'];
            $data['article_id'] = $_POST['article_id'];
            $data['comments'] = $_POST['comments'];
            $data['post_date'] = date('Y-m-d H:i:s');
            $this->model->insertComments($data);
            Hook::Header('Article/');
        } else {
            Hook::Header('Article');
        }
    }

    public function deleteComments($id) {
        if (Session::exist()) {
            if (Session::get('role') == 'owner' || Session::get('role') == 'admin' || Session::get('role') == 'default') {
                $this->model->deleteComments($id);
                Hook::Header('Article');
            } else {
                Hook::Header('user/signUp');
            }
        }
    }

    public function rest(){

        $data = RestUtilities::processRequest();
     $view = "";
     if(isset($_GET["id_article"])) {
         $view = $_GET["id_article"];

       switch ($data->getMethod()) {
           case 'get':
                $property = $this->model->_getAllArticleById($view);
               $var = RestUtilities::sendResponse(401, Encodes::encodeJson($property), 'application/json');
               var_dump($property);
               break;
           
           default:
               # code...
               break;
       }
}else {
    echo "sem....";

    }
}

}
