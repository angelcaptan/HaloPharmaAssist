<?php

require '../vendor/autoload.php';
include_once '../controllers/general_controller.php';
require '../vendor/setasign/fpdf/fpdf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;

    // Ensure the end date includes the entire day
    if ($end_date) {
        $end_date = date('Y-m-d 23:59:59', strtotime($end_date));
    }

    // Fetch audit logs based on the inputted dates
    $audit_logs = get_audit_logs($start_date, $end_date);

    class PDF extends FPDF
    {
        function Header()
        {
            $this->Image('../assets/images/halo.png', 10, 10, 30);
            $this->Cell(40);
            $this->SetFont('Arial', 'B', 16);
            $this->Cell(100, 10, 'Audit Logs', 0, 1, 'C');
            $this->Ln(10);
            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 10, 'From Abeer Pharmacy Limited (ADJEL Kakata)', 0, 0, 'L');
            $this->Cell(0, 10, 'Date: ' . date('Y-m-d'), 0, 1, 'R');
            $this->Ln(10);
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Generated By Halo Pharma Assist', 0, 0, 'R');
        }

        function ChapterTitle($label)
        {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, $label, 0, 1, 'L');
            $this->Ln(4);
        }

        function Table($header, $data, $widths)
        {
            $this->SetFont('Arial', 'B', 12);
            for ($i = 0; $i < count($header); $i++) {
                $this->Cell($widths[$i], 7, $header[$i], 1, 0, 'C');
            }
            $this->Ln();
            $this->SetFont('Arial', '', 12);
            foreach ($data as $row) {
                $this->Cell($widths[0], 6, $row['log_id'], 1);
                $this->Cell($widths[1], 6, $row['last_name'], 1);
                $this->Cell($widths[2], 6, $row['action'], 1);
                $this->Cell($widths[3], 6, $row['table_name'], 1);
                $this->Cell($widths[4], 6, $row['record_id'], 1);
                $this->Cell($widths[5], 6, $row['timestamp'], 1);
                $this->Ln();
            }
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    $header = array('ID', 'User', 'Action', 'Table', 'Rec ID', 'Timestamp');
    $widths = array(10, 25, 50, 40, 15, 42);

    $pdf->ChapterTitle('Audit Logs');
    $pdf->Table($header, $audit_logs, $widths);

    // Output the PDF to download
    $pdf->Output('D', 'Audit_Logs_' . date('Y-m-d') . '.pdf');
}
?>