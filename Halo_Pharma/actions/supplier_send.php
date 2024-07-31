<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../vendor/autoload.php';
include_once '../controllers/general_controller.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
$user_id = $_SESSION['user_id']; // Get the current user's ID

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../assets/images/halo.png', 10, 10, 30);
        $this->Cell(40);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(100, 10, 'Restock List', 0, 1, 'C');
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
        $this->Cell(0, 10, 'Generated & Sent By Halo Pharma Assist', 0, 0, 'R');
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
            $this->Cell($widths[0], 6, $row['product_name'], 1);
            $this->Cell($widths[1], 6, $row['quantity'], 1, 0, 'C');
            $this->Cell($widths[2], 6, isset($row['expiry_date']) ? $row['expiry_date'] : '-', 1, 0, 'C');
            $this->Ln();
        }
    }
}

function generatePDF($low_stock_products, $soon_to_expire_products, $additional_products)
{
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    if (!empty($low_stock_products)) {
        $header = array('Product Name', 'Quantity', 'Expiry Date');
        $widths = array(120, 20, 40);
        $pdf->ChapterTitle('Low Stock Products');
        $pdf->Table($header, $low_stock_products, $widths);
        $pdf->Ln(10);
    }

    if (!empty($soon_to_expire_products)) {
        $header = array('Product Name', 'Quantity', 'Expiry Date');
        $widths = array(120, 20, 40);
        $pdf->ChapterTitle('Soon to Expire Products');
        $pdf->Table($header, $soon_to_expire_products, $widths);
        $pdf->Ln(10);
    }

    if (!empty($additional_products)) {
        $header = array('Product Name', 'Quantity', 'Expiry Date');
        $widths = array(120, 20, 40);
        $pdf->ChapterTitle('Additional Products');
        $pdf->Table($header, $additional_products, $widths);
        $pdf->Ln(10);
    }

    return $pdf;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['previewPdf'])) {
    $selected_low_stock_products = isset($_POST['low_stock_products']) ? $_POST['low_stock_products'] : [];
    $selected_soon_to_expire_products = isset($_POST['soon_to_expire_products']) ? $_POST['soon_to_expire_products'] : [];
    $selected_additional_products = isset($_POST['additional_products']) ? $_POST['additional_products'] : [];

    if (empty($selected_low_stock_products) && empty($selected_soon_to_expire_products) && empty($selected_additional_products)) {
        echo "<script>alert('No products selected.'); window.location.href = '../restock.php';</script>";
        exit;
    }

    $low_stock_products = getProductsByIds($selected_low_stock_products);
    $soon_to_expire_products = getProductsByIds($selected_soon_to_expire_products);
    $additional_products = getProductsByIds($selected_additional_products);

    $pdf = generatePDF($low_stock_products, $soon_to_expire_products, $additional_products);
    $pdf->Output('D', 'Restock_List.pdf');
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['savePdf'])) {
    $selected_low_stock_products = isset($_POST['low_stock_products']) ? $_POST['low_stock_products'] : [];
    $selected_soon_to_expire_products = isset($_POST['soon_to_expire_products']) ? $_POST['soon_to_expire_products'] : [];
    $selected_additional_products = isset($_POST['additional_products']) ? $_POST['additional_products'] : [];

    if (empty($selected_low_stock_products) && empty($selected_soon_to_expire_products) && empty($selected_additional_products)) {
        echo "<script>alert('No products selected.'); window.location.href = '../restock.php';</script>";
        exit;
    }

    $low_stock_products = getProductsByIds($selected_low_stock_products);
    $soon_to_expire_products = getProductsByIds($selected_soon_to_expire_products);
    $additional_products = getProductsByIds($selected_additional_products);

    $pdf = generatePDF($low_stock_products, $soon_to_expire_products, $additional_products);

    $timestamp = time();
    $pdfFilePath = "../pdfs/low_stock_and_soon_to_expire_products_$timestamp.pdf";
    $pdf->Output('F', $pdfFilePath);

    $_SESSION['pdfFilePath'] = $pdfFilePath;
    echo "<script>alert('PDF saved successfully.'); window.location.href = '../restock.php';</script>";
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sendEmail'])) {
    if ($_SESSION['role'] !== 'Manager') {
        echo "<script>alert('Access denied. Only managers can send the email.'); window.location.href = '../restock.php';</script>";
        exit;
    }

    $pdfFilePath = isset($_SESSION['pdfFilePath']) ? $_SESSION['pdfFilePath'] : '';
    if (!file_exists($pdfFilePath)) {
        echo "<script>alert('PDF file does not exist. Please generate and save the PDF first.'); window.location.href = '../restock.php';</script>";
        exit;
    }

    $supplier_email = 'angeljcaptan@gmail.com';
    $subject = "Restock List";
    $message = "Please find the attached PDF for the list of Products Needing Restocking.";

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.mailgun.org';
        $mail->SMTPAuth = true;
        $mail->Username = 'postmaster@sandbox466cab17ec1142d398d8c19b5a5e5e8f.mailgun.org';
        $mail->Password = '88eca10b1401673d1380e0d23f1efb07-0f1db83d-bdf86c5b';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('noreply@yourdomain.com', 'Abeer Pharmacy Limited- Kakata Branch');
        $mail->addAddress($supplier_email);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->addAttachment($pdfFilePath);

        $mail->send();
        log_action($_SESSION['user_id'], 'send_restock_email', 'Products', null);
        echo "<script>alert('Email sent successfully.'); window.location.href = '../restock.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Failed to send email. Error: {$mail->ErrorInfo}'); window.location.href = '../restock.php';</script>";
    }
} else {
    echo "Unexpected Error";
}