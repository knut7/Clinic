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

namespace Module\Clinic\Models;

use Ballybran\Database\Drives\AbstractDatabaseInterface;

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

class ArticleModel {

    private $entity;

    public function __construct( AbstractDatabaseInterface $entity) {

        $this->entity = $entity;
    }

    /**
     * @param $id responsavel para buscar artigos pelo id na base de dados
     * @return array
     */
    public function _getArticleById($id) {
        /** @var int $id */
        return $this->entity->selectManager("SELECT * FROM article WHERE id_article =" . $id);
    }

    public function _getAllArticleById($id) {
        return $this->entity->selectManager("SELECT  *  FROM article WHERE id_article=:id_article", ['id_article' => $id]);
    }

    public function _getAllArticle() {
        return $this->entity->selectManager("SELECT  *  FROM article WHERE id_article ORDER BY id_article DESC ");
    }

    /**
     * @param $data
     */
    public function insertArticle($data) {
         $this->entity->insert("article", $data);
    }

    public function deleteArticle($id) {
        return $this->entity->delete('article', "id_article=$id", 1);
    }

    public function editPublish($data, $id) {
        return $this->entity->update('article', $data, "id_article=$id");
    }

    public function _allCategorias() {
        return $this->entity->selectManager("SELECT * FROM categoria  WHERE id_cat ");
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     * */
    public function getComments($id) {
        return $this->entity->selectManager("SELECT * FROM comments INNER JOIN article ON comments.article_id = article.id_article  WHERE comments.article_id = $id ORDER BY comments.id DESC");
    }

    /**
     * @param $data
     * @return bool
     */
    public function insertComments($data) {
        return $this->entity->insert('comments', $data);
    }

    public function deleteComments($id) {
        $this->entity->delete('comments', "id=$id");
    }

}
