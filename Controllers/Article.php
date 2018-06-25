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

use Ballybran\Helpers\ {
    Http\Hook, Security\Session, vardump\Vardump, Images\Resize
};

use Module\Upload\ImageUpload;
use Module\Lib\SendMail;
use PHPMailer\PHPMailer\PHPMailer;
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

    public $width = 900;
    public $height = 500;
    public $quality = 10;
    public $option = "";

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
    }

    public function art_pre_post($id) {
        $this->view->title = "Artigos";

        /** @var int $id */
        $this->view->public = $this->model->_getArticleById($id);
        $this->view->comments = $this->model->getComments($id);
        if (Session::exist()) {
            $this->view->getUser = $this->model->getUser(Session::get("ID"))[0];
        }
        $this->view->render($this, 'art_pre_post');
    }

    public function publish() {
        if (Session::exist()) {
            if (Session::get('role') == 'owner' || Session::get('role') == 'admin' || Session::get('role') == 'markting') {

                $this->view->title = 'Publish';
                $this->view->cate = $this->model->_allCategorias();
                $this->view->delete = $this->model->_getAllArticle();
            } else {
                Hook::Header('');
            }
            $this->view->render($this, 'publish');
        } else {
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
        if (!empty($_POST['title']) && !empty($_POST['excerpt']) && !empty($_POST['content']) && !empty($_POST['cote']) && !empty($_POST['post_date']) && !empty($_POST['id_cat'])) {
            $this->imagem = new \Ballybran\Helpers\Http\FileSystem(new Resize());
            $this->imagem->setWidth(900);
            $this->imagem->setHeight(500);
            $this->imagem->setOption("crop");
            $this->imagem->setQuality(100);
            $this->imagem->setColor("FFFFFF");
            $this->imagem->setDegree(00);

            $img = new ImageUpload();
            $this->imagem->file('article');
            $img->setName($this->imagem->name);
            $img->setPath($this->imagem->path);
            $img->setSize($this->imagem->size);
            $img->setType($this->imagem->type);
            $data['path'] = $img->getPath();
            $data['name'] = $img->getName();
            $data['type'] = $img->getType();
            $data['size'] = $img->getSize();
            $data['cote'] = $_POST['cote'];
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
        if (!empty($_POST['nome']) && !empty($_POST['post_date']) && !empty($_POST['article_id']) && !empty($_POST['comments']) && !empty($_POST['email'])) {

            $data['nome'] = $_POST['nome'];
            $data['article_id'] = $_POST['article_id'];
            $data['comments'] = $_POST['comments'];
            $data['post_date'] = date('Y-m-d H:i:s');

//
//            $mail = new SendMail(new PHPMailer() );
//            $mail->setFrom("marciozebedeu@gmail.com");
//            $mail->setFromName($_POST['nome']);
//            $mail->setMessage($_POST['comments'] );
//            $mail->setAssunto("Commentarios dos usuarios");
//            $mail->setTo($_POST['email']);  // email d visitante vindo do form
//            $mail->setAddr($_POST['email']); // enviar para mim (secretaria)
//
//            $mail->send();
//            $mail->body();

            $this->model->insertComments($data);
            // Hook::Header('Article/');
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

    public function rest() {

        $data = \Ballybran\Core\REST\RestUtilities::processRequest();
        $view = "";
        if (isset($_GET["id_article"])) {
            $view = $_GET["id_article"];

            switch ($data->getMethod()) {
                case 'get':
                    $property = $this->model->_getAllArticleById($view);
                    $var = \Ballybran\Core\REST\RestUtilities::sendResponse(401, \Ballybran\Core\REST\Encodes::encodeHtml($property), 'application/text');
                    var_dump($property);
                    break;

                default:
                    # code...
                    break;
            }
        } else {
            echo "sem....";
        }
    }

}
