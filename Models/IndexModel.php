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
 * Date: 29/11/17
 * Time: 16:39
 */

namespace Module\Clinic\Models;


use Ballybran\Database\Drives\AbstractDatabaseInterface;
use Module\Service\AbstractModel;

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

class IndexModel
{
    /**
     * IndexModel constructor.
     * @param AbstractDatabaseInterface $entity
     */

    public function __construct( AbstractDatabaseInterface $entity)
    {

        $this->entity = $entity;
    }

    /**
     * select que vai pegar tudo da base de dados na ordem desc por id
     * @return array
     */
    public function exibirAricle()
    {
        return $this->entity->selectManager("SELECT  *  FROM article ORDER BY id_article  DESC  limit 3 ");
    }

    /**
     * @return mixed
     */
    public function exibiAllTitle()
    {
        return $this->entity->selectManager("SELECT  *  FROM article ORDER BY id_article  DESC  ");
    }

    /**
     * @return mixed
     */
    public function getAllUser()
    {

        return $this->entity->selectManager("SELECT * FROM  usuarios INNER JOIN pic_perfil on
            pic_perfil.usuarios_id = usuarios.id  ORDER BY usuarios.id DESC");
    }

    /**
     * @return mixed
     */
    public function _allCategorias()
    {
        return $this->entity->selectManager("SELECT * FROM categoria  WHERE id_cat ORDER BY id_cat DESC  LIMIT 3");
    }

    /**
     * @return mixed
     */
    public function sidebarCategorias()
    {
        return $this->entity->selectManager("SELECT * FROM categoria   ORDER BY RAND()   LIMIT 3");
    }
    /**
     * @return mixed
     */
    public function crearTable()
    {
        return $this->entity->get_Data_definition('clinica');

    }

    public function userCont()
    {
        return $this->entity->find('usuarios', 'sum(status)' );
    }

}
