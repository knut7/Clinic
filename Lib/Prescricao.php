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
 * Date: 16/12/17
 * Time: 19:14
 */

namespace Module\Clinic\Lib;


use Ballybran\Helpers\Time\Timestamp;
use Ballybran\Library\fpdf\FPDF;

class Prescricao extends FPDF {


    private $info;
    private $footer;



    public function __construct($orientation="P", $unit="mm", $size= "A4")
    {
        parent::__construct($orientation, $unit, $size);
    }

    public function getId($info)
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
        $this->Cell(180, 4, 'Receita:' . "\t" . Timestamp::dataTime(), '', '', 'R');
        $this->Ln(4);
        $this->Cell(180, 10, 'CINICA KNUT7', 0, "", 'C');
        $this->SetFont('Arial', '', 9);

        // Line break
        $this->Ln(7);

        $this->SetFont('Arial', 'B', 11);
        $this->Cell(70, 8, "", 0);
        $this->Cell(100, 8, "Receita do Paciente", 0);

        $this->Ln(15);
        // $this->SetFont('Arial', 'B', 10);
        // Move to the right
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 220, 255);
        // $this->Cell(30, 10, "Dados Pessoais", 0, 2, 'L', true);
        // $this->Ln(4);

        foreach ($this->info as $item => $value) {

            // $this->SetXY(10, 40);

            // Title
            $this->Ln(8);
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(12, 8, "Nome:", 0);
            $this->SetFont('Arial', '', 8);
            $this->Cell(140, 8, $value['firstname'] . "\t" . $value['lastname'], 0);

             $this->SetFont('Arial', 'B', 8);
            $this->Cell(18, 8, "N. Consulta:", 0);
            $this->SetFont('Arial', '', 8);
            $this->Cell(70, 8, $value['id'], 0);
            $this->Ln(4);

            $this->SetFont('Arial', 'B', 8);
            $this->Cell(8, 8, "Sexo:", 0);
            $this->SetFont('Arial', '', 8);
            $this->Cell(14, 8, $value['sexo'] == 'f' ? "Feminino" : "Masculino", 0);

            $this->SetFont('Arial', 'B', 8);
            $this->Cell(14, 8, "Telefone:", 0);
            $this->SetFont('Arial', '', 8);
            $this->Cell(24, 8, $value['telephone'], 0);

            $this->SetFont('Arial', 'B', 8);
            $this->Cell(12, 8, "Celular:", 0);
            $this->SetFont('Arial', '', 8);
            $this->Cell(24, 8, $value['telephone2'], 0);

            $this->SetFont('Arial', 'B', 8);
            $this->Cell(10, 8, "E-mail:", 0);
            $this->SetFont('Arial', '', 8);
            $this->Cell(24, 8, $value['email'], 0);
            $this->Ln(15);


            // Prescrição Médica

            // $this->SetFont("Arial", 'B', 8);
            // $this->Cell(30, 8, 'Nome Generico', 1, 0);
            // $this->Cell(30, 8, '0000000000', 1, 0);
            // $this->Ln(8);
            // $this->Cell(30, 8, 'Nome Comercial', 1, 0);
            // $this->Ln(8);
            // $this->Cell(30, 8, 'Dose', 1, 0);
            // $this->Ln(8);
            // $this->Cell(30, 8, utf8_decode("Via de Administração:"), 1, 0);
            //  $this->Ln(8);
            // $this->Cell(30, 8, utf8_decode('Horario da Medicação'), 1, 0);


            $this->SetFont('Arial', 'B', 8);
            $value['generico'] != "" ? $this->Cell(33, 8, utf8_decode("Prescrição Médica"),  0, 0, '', true) : "";
            $this->Ln(8);
            $this->SetFont('Arial', '', 8);
            $this->Ln(8);
            $value['generico'] != "" ? $this->Cell(40, 8, utf8_decode("Nome Genérico:"),1,  0) ." ".$this->Cell(40, 8,  utf8_decode($value['generico']),1, 0): "";
            $this->Ln(8);
            $value['comercial'] != "" ? $this->Cell(40, 8, utf8_decode("Nome Comercial:"), 1, 0) ." ".$this->Cell(40, 8,  utf8_decode($value['comercial']),1, 0): "";
            $this->Ln(8);
            $value['dose'] != "" ? $this->Cell(40, 8, utf8_decode("Dose:"),  1,0) ." ".$this->Cell(40, 8,  utf8_decode($value['dose']),1, 0): "";
            $this->Ln(8);
            $value['via_admin'] != "" ? $this->Cell(40, 8, utf8_decode("Via de Administração:"), 1, 0) ." ".$this->Cell(40, 8,  utf8_decode($value['via_admin']),1, 0): "";
            $this->Ln(8);
            $value['interval'] != "" ? $this->Cell(40, 8, utf8_decode("Horario da Medicação:"),1,  0) ." ".$this->Cell(40, 8,  utf8_decode($value['interval']),1, 0): "";
            $this->Ln(8);
            $value['inicio'] != "" ? $this->Cell(40, 8, utf8_decode("Data Do Inicio do Tratamento:"),  1,0) ." ".$this->Cell(40, 8,  utf8_decode($value['inicio']),1, 0): "";
            $this->Ln(8);
            $value['final'] != "" ? $this->Cell(40, 8, utf8_decode("Data Do fim do Tratamento:"),1,  0) ." ".$this->Cell(40, 8,  utf8_decode($value['final']),1, 0): "";
            $this->Ln(15);


             $this->Cell(33, 8, "Doutor(a):", 0);
            $this->Ln(8);
            $this->Cell(20, 8, "____________________", 0);
            $this->Ln(8);
            $this->Cell(40, 8, $value['titulo'], 0);






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