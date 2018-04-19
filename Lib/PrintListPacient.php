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
 * Date: 19/12/17
 * Time: 06:55
 */

namespace Module\Clinic\Lib;


use Ballybran\Library\fpdf\FPDF;
USE Ballybran\Helpers\Time\Timestamp;
class PrintListPacient extends FPDF {



    private $info;
    private $footer;


    public function __construct($orientation="P", $unit="mm", $size= "A4")
    {
        parent::__construct($orientation, $unit, $size);
    }

    public function getList($info)
    {
        $this->info = $info;
    }

    public function myFooter($footer)
    {
        $this->footer = $footer;
    }
    function Header()
    {

        // Logo
//        $this->Image('logo.png', 10, 6, 30);
        // Arial bold 15
        $this->SetFont('Arial', '', 10);
        // Move to the right
        $this->Cell(18, 10, "", 0);
        // Title
        $this->Image(URL . DIR_FILE . 'Lib/knut7.jpg', 20, 10, 20, 20, 'JPG');
        $this->Cell(180, 4, 'Prontuario:' . "\t" . Timestamp::dataTime(), '', '', 'R');
        $this->Ln(4);
        $this->Cell(180, 10, 'CINICA KNUT7', 0, "", 'C');
        $this->SetFont('Arial', '', 9);

        // Line break
        $this->Ln(7);

        $this->SetFont('Arial', 'B', 11);
        $this->Cell(70, 8, "", 0);
        $this->Cell(60, 8, "Lista  dos Paciente Para o atendimento", 0);
        $this->Ln(20);

    }

    public function infoDoctor($data)
    {      
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 8, "Doutor(a): ");
        $this->Cell(15, 8, utf8_decode($data['firstname']."\t" .$data['lastname']));
        $this->Ln(8);

    }


    public function body($data)
    {
        $this->infoDoctor($data);

        $this->SetFont("Arial", "B", 8);
        $this->SetFillColor(243, 200, 100);
        $this->Cell(15, 8, "Item", 1, '', '', true);
        $this->Cell(45, 8, "Nome", 1,'', '', true);
        $this->Cell(9, 8, "idade", 1,'', '', true);
        $this->Cell(8, 8, "sexo", 1,'', '', true);
        $this->Cell(25, 8, "Convenio", 1,'', '', true);
        // $this->Cell(25, 8, "Diag", 1,'', '', true);
        // $this->Cell(15, 8, "H. Cons", 1, '', '', true);
        // $this->Cell(25, 8, "H. Obs", 1, '', '', true);
        $this->Cell(25, 8, utf8_decode("N incrição"), 1, '', '', true);
        $this->Ln(8);
        $this->SetFont("Arial", "",8);


        foreach ($this->info as $item => $value) {

            $this->SetFont("Arial", "B", 8);
            $this->Cell(15, 8, $item+1, 1);
            $this->Cell(45, 8, utf8_decode($value["firstname"] . "\t " .$value["lastname"]), 1);
            $this->Cell(9, 8, (intval(Timestamp::dataTime("Y") - intval($value['dataNascimento']) ) ), 1);
            $this->Cell(8, 8, ucfirst($value['sexo']), 1);
            $this->Cell(25, 8, $value['convNome'], 1);
            // $this->Cell(25, 8, "", 1);
            // $this->Cell(15, 8, "", 1);
            // $this->Cell(25, 8, "", 1);
            $this->Cell(25, 8, $value['id'], 1);
            $this->Ln(8);
        }

    }
    
    


// Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(10, 10,  "Page " . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }
}