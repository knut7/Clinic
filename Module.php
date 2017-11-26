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
 * User: artphotografie
 * Date: 08/12/16
 * Time: 20:35
 */

namespace Module\apweb;

use Module\apweb\services\NovoTeste;
use Module\apweb\services\FactoryService;

class Module {

    public function getConfig($name) {

//        return require R . 'Config/Config.moduleTeste';




        $c = new NovoTeste();
        $d = new $name($c);
        return $d;
    }

    public function getAutoloadConfig() {

        return array(
            'FWAP\Config\Autoloadfw' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __NAMESPACE__ . 'Module' . __NAMESPACE__,
                ),
            ),
        );
    }

}
