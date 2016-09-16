<?php
//Script to generate pdf
require('../fpdf/fpdf.php');
require_once ("dbConnection.php");


class PDF extends FPDF
{
// Page header
    function Header()
    {
        // Logo
        $this->Image('image/logo.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(40);
        // Title
        $title = 'My Big Library Books Details';
        //$pdf->Cell(0,10,$title,1,0,'C');
        $this->SetFillColor(200,220,255);
        $this->Cell(0,10,$title,1,0,'C', true);
        // Line break
        $this->Ln(30);
    }

// Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }

// File reader
    public function displayFileContent($heading, $filename){
        // Arial 12
        $this->SetFont('Arial','',12);
        // Background color
        $this->SetFillColor(200,220,255);
        // Title
        $this->Cell(0,6,$heading,0,1,'L',true);
        // Line break
        $this->Ln(4);

        // Read text file
        $txt = file_get_contents($filename);
        // Times 12
        $this->SetFont('Times','',12);
        // Output justified text
        $this->MultiCell(0,5,$txt);
        // Line break
        $this->Ln();

    }

// table
    public function displayTable($header, $content){
        if($header != '' && $content != '') {
            foreach ($header as $col) {
                $this->Cell(40, 7, $col, 1);
            }

            ////Body content
            foreach ($content as $row1) {
                $this->Ln();
                foreach ($row1 as $column)
                    $this->Cell(40, 10, $column, 1);
                //$pdf->SetFont('','U');
                $link = $this->AddLink();

            }
        }
    }
}


$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Ln();


$header = array('Book Name', 'Author', 'Publisher', 'Published Date');
//Get Details
$db = Database::getInstance();
$mysqli = $db->getConnection();
$sql_query = "SELECT name,author, publisher, published_date FROM books";
$result = $mysqli->query($sql_query);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $resultset[] = $row;
    }
}

$pdf->displayTable($header, $resultset);
$pdf->Ln();
$pdf->Ln();

//Read files
$pdf->displayFileContent('Library Details', 'library_details.txt');
$pdf->displayFileContent('Contact US', 'library_address.txt');

//Generate pdf
$pdf->Output();


?>