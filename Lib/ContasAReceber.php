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
use Ballybran\Helpers\Time\Timestamp;
use Ballybran\Helpers\Ucfirst;
class ContasAReceber extends FPDF {



    private $contas;
    private $credito;
    private $debito;
    private $footer;


    public function __construct($orientation="P", $unit="mm", $size= "A4")
    {
        parent::__construct($orientation, $unit, $size);
    }

    public function receber($contas)
    {
        $this->contas = $contas;
    }

 public function totalCrebito($credito)
    {
        $this->credito = $credito;
    }

 public function totalDebito($debito)
    {
        $this->debito = $debito;
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
        $this->Cell(180, 4, 'print in' . "\t" . Timestamp::dataTime(), '', '', 'R');
        $this->Ln(4);
        $this->Cell(180, 10, 'CINICA KNUT7', 0, "", 'C');
        $this->SetFont('Arial', '', 9);

        // Line break
        $this->Ln(7);

        $this->SetFont('Arial', 'B', 11);
        $this->Cell(70, 8, "", 0);
        $this->Cell(100, 8, utf8_decode("Relatórios de Contas A Receber"), 0);
        $this->Ln(20);

    }

    public function body()
    {

        $this->SetFont("Arial", "B", 8);
        $this->Cell(15, 8, "Item", 1);
        $this->Cell(65, 8, "Cliente", 1);
        $this->Cell(15, 8, utf8_decode("T.Conta"), 1);
        $this->Cell(20, 8, utf8_decode("Categoria"), 1);
        $this->Cell(15, 8, utf8_decode("V.a Pagar"), 1);
        // $this->Cell(35, 8, utf8_decode("D.Pagamento"), 1);
        $this->Cell(15, 8, utf8_decode("Stuacão"), 1);
        $this->Cell(15, 8, utf8_decode("F. de Pag"), 1);
        $this->Cell(15, 8, utf8_decode("v.Pagar"), 1);
        $this->Cell(15, 8, "Em Falta", 1);
//        $this->Cell(20, 8, "", 1);
//        $this->Cell(20, 8, "", 1);
        $this->Ln(8);
        $this->SetFont("Arial", "",8);

        foreach ($this->contas as $item => $value) {
            $this->SetFont("Arial", "B", 8);
            $this->Cell(15, 8, $item+1, 1);
            $this->Cell(65, 8, utf8_decode($value['firstname'] . "\t" .$value['lastname']),  1);
            $this->Cell(15, 8, ucfirst($value['Cnome']), 1);
            $this->Cell(20, 8, ucfirst::abbreviate($value['espNome'], 9), 1);
            $this->Cell(15, 8, ucfirst($value['espValor']), 1);
            // $this->Cell(35, 8, $value["dtPag"], 1);
            $this->Cell(15, 8, ucfirst($value['situ_nome']), 1);
            $this->Cell(15, 8, ucfirst($value['FPnome']), 1);
            $this->Cell(15, 8, ucfirst($value['CreditoValor']), 1);
            $totaEmFalta =  $value['espValor'] - $value['CreditoValor'];
            if($totaEmFalta == 0) {
                $this->SetDrawColor(0, 80, 180);
                $this->SetFillColor(230, 230, 0);
                $this->SetTextColor(0, 0, 0);                
                $this->Cell(15, 8, $totaEmFalta, 1);  
            }else {
                $this->SetDrawColor(0, 80, 180);
                $this->SetFillColor(230, 230, 0);
                $this->SetTextColor(220, 50, 50);                
                $this->Cell(15, 8, $totaEmFalta, 1);
                $this->SetTextColor(0, 0, 0);                
  
   
            }                  


            $this->Ln(8);
                    
           

        }
                $this->SetX(170);
                $this->SetDrawColor(0, 80, 180);
                $this->SetFillColor(230, 230, 0);
                $this->SetTextColor(0, 0, 0);                
                $this->Cell(15, 8, "Credito", 1);
                $this->Cell(15, 8, $this->credito['total'], 1);
                $this->Ln(8);

                $this->SetX(170);
                $this->SetDrawColor(0, 80, 180);
                $this->SetFillColor(230, 230, 0);
                $this->SetTextColor(220, 50, 50);                
                $this->Cell(15, 8, "Debito", 1);
                $this->Cell(15, 8, "-". $this->debito['total'], 1);
                $this->Ln(8);

                $this->SetX(170);

                $total = $this->credito['total'] - $this->debito['total'];

                if($total > 0) {
                $this->SetDrawColor(0, 80, 180);
                $this->SetFillColor(230, 230, 0);
                $this->SetTextColor(0, 0, 0);                
                $this->Cell(15, 8, "Total", 1);

                $this->Cell(15, 8, $total, 1);
            }else {
                $this->SetDrawColor(0, 80, 180);
                $this->SetFillColor(230, 230, 0);
                $this->SetTextColor(220, 50, 50);                
                $this->Cell(15, 8, "Total", 1);

                $this->Cell(15, 8, $total, 1);
            }
                $this->Ln(8);

                $this->SetTextColor(00, 00, 0);
                $this->Cell(15, 8, "Assinatura", 0);
                $this->Ln(8);
                $this->Cell(15, 8, "__________________", 0);


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