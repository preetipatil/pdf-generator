<?php
//Script to generate pdf
require('../fpdf/fpdf.php');
require_once ("dbConnection.php");

$db = Database::getInstance();
$mysqli = $db->getConnection();
$sql_query = "SELECT name,author, publisher, published_date FROM books";
$result = $mysqli->query($sql_query);
if ($result->num_rows > 0) {
   while($row = $result->fetch_assoc()) {
      $resultset[] = $row;
   }
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
//Title
$title = 'My Big Library Books Details';
$pdf->Cell(100,10,$title,0,'C',true);
$pdf->Ln();
$pdf->Ln();
// Header
$header = array('Book Name', 'Author', 'Publisher', 'Published Date');
foreach($header as $col) {
   $pdf->Cell(40, 7, $col, 1);

}
//Body content
foreach($resultset as $row1) {
   $pdf->Ln();
   foreach($row1 as $column)
      $pdf->Cell(40,10,$column,1);
}
//Generate pdf
$pdf->Output();
?>