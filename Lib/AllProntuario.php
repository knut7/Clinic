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

class AllProntuario extends FPDF {

    private $info;
    private $footer;

    public function __construct($orientation = "P", $unit = "mm", $size = "A4") {
        parent::__construct($orientation, $unit, $size);
    }

    public function getId($info) {
        $this->info = $info;
    }

    public function myFooter($footer) {
        $this->footer = $footer;
    }

    function Header() {

        // Logo
//        $this->Image('logo.png', 10, 6, 30);
        // Arial bold 15
        $this->SetFont('Arial', '', 10);
        // Move to the right
        $this->Cell(18, 10, "", 0);
        // Title
        $this->Image(URL . DIR_FILE . 'Public/images/logo_clinic.jpg', 20, 10, 20, 20, 'JPG');
        $this->Cell(180, 4, 'Prontuario:' . "\t" . Timestamp::dataTime(), '', '', 'R');
        $this->Ln(4);
        $this->Cell(180, 10, 'CINICA KNUT7', 0, "", 'C');
        $this->SetFont('Arial', '', 9);

        // Line break
        $this->Ln(7);

        $this->SetFont('Arial', 'B', 11);
        $this->Cell(70, 8, "", 0);
        $this->Cell(100, 8, "Prontuario do Paciente", 0);

        $this->Ln(15);
        $this->SetFont('Arial', 'B', 10);
        // Move to the right
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(30, 10, "Dados Pessoais", 0, 2, 'L', true);
        $this->SetXY(110, 40);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(50, 10, "Dados Complementares", 0, 2, 'L', true);
        $this->Ln(4);



        foreach ($this->info as $item => $value) {

            $this->SetXY(10, 40);

            // Title
            $this->Ln(8);
            $this->SetFont('Arial', '', 8);
            $this->Cell(33, 8, "Nome:", 0);
            $this->Cell(70, 8, $value['firstname'] . "\t" . $value['lastname'], 0);
            $this->Cell(20, 8, "Naturalidade:", 0);
            $this->Cell(40, 8, $value['naturalidade'], 0);


            $this->Ln(4);
            $this->Cell(33, 8, "Sexo:", 0);
            $this->Cell(70, 8, $value['sexo'] == 'f' ? "Feminino" : "Masculino", 0);
            $this->Cell(20, 8, "Etnia:", 0);
            $this->Cell(40, 8, $value['etnia'], 0);

            $this->Ln(4);

            $this->Cell(33, 8, "Telefone:", 0);
            $this->Cell(70, 8, $value['telephone'], 0);
            $this->Cell(20, 8, "Estado Civil:", 0);
            $this->Cell(40, 8, $value['estaCivil'], 0);


            $this->Ln(4);
            $this->Cell(33, 8, "Celular:", 0);
            $this->Cell(70, 8, $value['telephone2'], 0);
            $this->Cell(20, 8, "Profissso:", 0);
            $this->Cell(40, 8, $value['profissao'], 0);

            $this->Ln(4);

            $this->Cell(33, 8, "E-mail:", 0);
            $this->Cell(70, 8, $value['email'], 0);
            $this->Cell(20, 8, "", 0);
            $this->Cell(40, 8, "", 0);

            $this->Ln(4);
            $this->Cell(33, 8, "C.Postal", 0);
            $this->Cell(70, 8, $value['postcode'], 0);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(20, 8, utf8_decode("Convénio"), 0);
            $this->SetFont('Arial', '', 8);
            $this->Cell(40, 8, "", 0);

            $this->Ln(4);
            $this->Cell(33, 8, "Endereco 1", 0);
            $this->Cell(70, 8, $value['address_1'], 0);
            $this->Cell(20, 8, '', 0);
            $this->Cell(40, 8, '', 0);
            $this->Ln(4);
            $this->Cell(33, 8, "Endereco 2", 0);
            $this->Cell(70, 8, $value['address_2'], 0);
            $this->Cell(20, 8, utf8_decode($value['convNome']), 0);
            $this->Cell(40, 8, "", 0);
            $this->Ln(4);
            $this->Cell(33, 8, "Bairro", 0);
            $this->Cell(70, 8, $value['bairro'], 0);
            $this->Ln(4);
            $this->Cell(33, 8, "Cidade", 0);
            $this->Cell(70, 8, $value['city'], 0);
            $this->Ln(4);
            $this->Cell(33, 8, "Provincia", 0);
            $this->Cell(70, 8, $value['states'], 0);
            $this->Ln(4);
            $this->Cell(33, 8, "Paciente Cadastrado em:", 0);
            $this->Cell(70, 8, $value['createTime'], 0);

            $this->Ln(4);
            $this->Cell(33, 8, "Ultima Consulta:", 0);
            $this->Cell(70, 8, $value['create_dt'], 0);

            $this->Ln(4);
            $this->Cell(33, 8, "N. Conaulta", 0);
            $this->Cell(70, 8, $value['Paciente_id'], 0);
            $this->Ln(15);

            // Annamenese
            $this->SetFont('Arial', 'B', 8);
            $value['queixa_pri'] != "" ? $this->Cell(100, 8, "Annamenese", 0, 0, '', true) : "";
            $value['altura'] != "" ? $this->Cell(33, 8, utf8_decode("Exame Físico"), 0, 0, '', true) : "";
            $this->SetFont('Arial', '', 8);

            $this->Ln(8);
            $value['queixa_pri'] != "" ? $this->Cell(33, 8, utf8_decode("Quiexa Principal:"), 0) . " " . $this->Cell(70, 8, utf8_decode($value['queixa_pri']), 0) : "";
            $value['altura'] != "" ? $this->Cell(20, 8, "Altura:", 0) . " " . $this->Cell(40, 8, $value['altura'] . "\t m", 0) . $this->Ln(4) : "";

            $value['historia'] != "" ? $this->Cell(33, 8, utf8_decode("História:"), 0) . " " . $this->Cell(70, 8, utf8_decode($value['historia']), 0) : "";
            $value['peso'] != "" ? $this->Cell(20, 8, "Peso:", 0) . " " . $this->Cell(40, 8, $value['peso'] . "\t Kg", 0) . $this->Ln(4) : "";

            $value['prob_renais'] != "" ? $this->Cell(33, 8, utf8_decode("Problemas Renais:"), 0) . " " . $this->Cell(70, 8, utf8_decode($value['prob_renais']), 0) : "";
            $value['freq_cardiaca'] != "" ? $this->Cell(30, 8, utf8_decode("Frequencia Cardiaca:"), 0) . " " . $this->Cell(40, 8, $value['freq_cardiaca'] . "\t Batimento por minutos", 0) . $this->Ln(4) : "";

            $value['prob_artic_reum'] != "" ? $this->Cell(39, 8, utf8_decode("Problemas Art ou Rematismo:"), 0) . " " . $this->Cell(64, 8, $value['prob_artic_reum'], 0) : "";
            $value['press_arte_sistolica'] != "" ? $this->Cell(33, 8, utf8_decode("P A A:"), 0) . " " . $this->Cell(40, 8, $value['press_arte_sistolica'] . "\t mmHg", 0) . $this->Ln(4) : "";

            $value['prob_cardiaco'] != "" ? $this->Cell(33, 8, utf8_decode("Problemas Cardiaco"), 0) . " " . $this->Cell(70, 8, utf8_decode($value['prob_cardiaco']), 0) : "";
            $value['press_arte_diastolica'] != "" ? $this->Cell(33, 8, utf8_decode("P A D:"), 0) . " " . $this->Cell(40, 8, utf8_decode($value['press_arte_diastolica']) . "\t mmHg", 0) . $this->Ln(4) : "";

            $value['prob_respira'] != "" ? $this->Cell(33, 8, utf8_decode("Problemas Respiratprio:"), 0) . " " . $this->Cell(70, 8, utf8_decode($value['prob_respira']), 0) : "";
            $value['obs_gerais'] != "" ? $this->Cell(20, 8, utf8_decode("Obs Gerais:"), 0) . " " . $this->Cell(40, 8, utf8_decode($value['obs_gerais']), 0) . $this->Ln(4) : "";

            $value['prob_gastricos'] != "" ? $this->Cell(33, 8, utf8_decode("Problemas Gatricos:"), 0) . " " . $this->Cell(70, 8, utf8_decode($value['prob_gastricos']), 0) : "";
            $this->Cell(40, 8, "", 0) . $this->Ln(4);


            $value['alergia'] != "" ? $this->Cell(33, 8, utf8_decode("Alergias:"), 0) . " " . $this->Cell(70, 8, utf8_decode($value['alergia']), 0) . $this->Ln(4) : "";

            $value['hepatite'] != "" ? $this->Cell(33, 8, utf8_decode("Epatite:"), 0) . " " . $this->Cell(70, 8, utf8_decode($value['hepatite']), 0) . $this->Ln(4) : "";

            $value['gravides'] != "" ? $this->Cell(33, 8, utf8_decode("Gravidés:"), 0) . " " . $this->Cell(70, 8, utf8_decode($value['gravides']), 0) . $this->Ln(4) : "";

            $value['uso_de_medicam'] != "" ? $this->Cell(33, 8, utf8_decode("Uso d Medicamentos:"), 0) . " " . $this->Cell(70, 8, utf8_decode($value['uso_de_medicam']), 0) : "";


            // Hipotese Diagnóstico || Exame Físico
            $this->Ln(15);
            $this->SetFont('Arial', 'B', 8);
            $value['diagnostico'] != "" ? $this->Cell(180, 8, utf8_decode("Hipotese Diagnóstico"), 0, 0, '', true) : "";
            $this->SetFont('Arial', '', 8);
            $this->Ln(8);

            $value['diagnostico'] != "" ? $this->Cell(33, 8, utf8_decode("Diagnóstico:"), 0) . " " . $this->Cell(70, 8, utf8_decode($value['diagnostico']), 0) . $this->Ln(4) : "";

            $value['diag_obs'] != "" ? $this->Cell(25, 8, utf8_decode("Observções:"), 0) . $this->Ln(8) . " " . $this->MultiCell(120, 4, utf8_decode($value['diag_obs']), 0, 2) : "";

            // Evolucao
            $this->Ln(8);
            $this->SetFont('Arial', 'B', 8);
            $value['Evolucao'] != "" ? $this->Cell(100, 8, utf8_decode("Evolução"), 0, 0, '', true) : "";
            $this->SetFont('Arial', '', 8);
            $this->Ln(8);

            $value['Evolucao'] != "" ? $this->Cell(33, 8, utf8_decode("Evolução:"), 0) . " " . $this->Cell(70, 8, utf8_decode($value['Evolucao']), 0) : "";

            // Prescrição Médica
            $this->Ln(8);
            $this->SetFont('Arial', 'B', 8);
            $value['generico'] != "" ? $this->Cell(33, 8, utf8_decode("Prescrição Médica"), 0, 0, '', true) : "";
            $this->SetFont('Arial', '', 8);
            $this->Ln(8);
            $value['generico'] != "" ? $this->Cell(40, 8, utf8_decode("Nome Genérico:"), 0) . " " . $this->Cell(40, 8, utf8_decode($value['generico']), 0) . $this->Ln(4) : "";
            $value['comercial'] != "" ? $this->Cell(40, 8, utf8_decode("Nome Comercial:"), 0) . " " . $this->Cell(40, 8, utf8_decode($value['comercial']), 0) . $this->Ln(4) : "";
            $value['dose'] != "" ? $this->Cell(40, 8, utf8_decode("Dose:"), 0) . " " . $this->Cell(40, 8, utf8_decode($value['dose']), 0) . $this->Ln(4) : "";
            $value['via_admin'] != "" ? $this->Cell(40, 8, utf8_decode("Via de Administração:"), 0) . " " . $this->Cell(40, 8, utf8_decode($value['via_admin']), 0) . $this->Ln(4) : "";
            $value['interval'] != "" ? $this->Cell(40, 8, utf8_decode("Horario da Medicação:"), 0) . " " . $this->Cell(40, 8, utf8_decode($value['interval']), 0) . $this->Ln(4) : "";
            $value['inicio'] != "" ? $this->Cell(40, 8, utf8_decode("Data Do Inicio do Tratamento:"), 0) . " " . $this->Cell(40, 8, utf8_decode($value['inicio']), 0) . $this->Ln(4) : "";
            $value['final'] != "" ? $this->Cell(40, 8, utf8_decode("Data Do fim do Tratamento:"), 0) . " " . $this->Cell(40, 8, utf8_decode($value['final']), 0) . $this->Ln(4) : "";


            $this->PrintChapter($item + 1, "prontuario", "rrr");
        }
    }

    function PrintChapter($num, $title, $file) {
        // Add chapter
        $this->AddPage();
        $this->ChapterTitle($num, $title);
        $this->ChapterBody("yttty");
    }

// Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(10, 10, "Page " . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }

}
