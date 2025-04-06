<?php
session_start();
require 'connection.php';
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

// Get URL parameters
$id = isset($_GET['id']) ? $_GET['id'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$bank_id = isset($_GET['bank_id']) ? $_GET['bank_id'] : '';

// Debug: Check the type value
echo "<!-- Debug: Type received: '$type', Bank ID: '$bank_id' -->";

// Fetch residence data
$sql = "SELECT residence.*, airports.countryName AS nationality, position.posiiton_name AS profession
        FROM residence 
        LEFT JOIN airports ON airports.airport_id = residence.nationality
        LEFT JOIN position ON position.position_id = residence.positionID
        WHERE residenceID = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$residence = $stmt->fetch();

// Fetch company data
$company = null;
if ($residence) {
    $sql = "SELECT * FROM company WHERE company_id = :company_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':company_id' => $residence['company']]);
    $company = $stmt->fetch();
}

// Fetch bank data
$bank = null;
if ($bank_id) {
    $sql = "SELECT * FROM banks WHERE id = :bank_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':bank_id' => $bank_id]);
    $bank = $stmt->fetch();
}

// Prepare content
$title = "";
$content = "";

if ($type === 'salary_certificate' && $bank_id) {
    $title = "Salary Certificate";
    $content = '
        <div class="date">Date: ' . date("M, d Y") . '</div>
        <h1>' . $title . '</h1>
        <p><strong>THE MANAGER<br>' . ($bank['bank_name'] ?? 'N/A') . '<br>Dubai, UAE</strong></p>
        <p><strong>Dear Sir/Madam,</strong><br>Subject: Application for Bank Account Opening</p>
        <p style="line-height: 1.3;">
            <strong>Employee Name: </strong>' . ($residence['passenger_name'] ?? 'N/A') . '<br>
            <strong>Designation: </strong>' . ($residence['profession'] ?? 'N/A') . '<br>
            <strong>Date of Joining: </strong>' . date("M d, Y", strtotime($residence['datetime'] ?? 'now')) . '<br>
            <strong>Salary: </strong>' . number_format($residence['salary_amount'] ?? 0) . ' AED<br>
            <strong>Gratuity/Termination Benefits: </strong>AS PER UAE LABOUR LAW<br>
            <strong>Visa Status: </strong>STAMP<br>
            <strong>Passport No: </strong>' . ($residence['passportNumber'] ?? 'N/A') . '<br>
            <strong>Nationality: </strong>' . ($residence['nationality'] ?? 'N/A') . '
        </p>
        <p>This is to certify that the above person is employed by us. We are under instruction from this employee to credit his salary with you every month and will continue to do so until we receive a clearance from you.</p>
        <p class="signature-stamp-label"><strong>Manager Director</strong></p>
    ';
} elseif ($type === 'noc') {
    $title = "No Objection Certificate";
    $content = '
        <div class="date">Date: ' . date("M, d Y") . '</div>
        <h1>' . $title . '</h1>
        <p><strong>Dear Sir/Madam,</strong></p>
        <p>This is to certify that <strong>' . ($residence['passenger_name'] ?? 'N/A') . '</strong> with <strong>' . ($residence['nationality'] ?? 'N/A') . '</strong> Nationality and Holding Passport No# <strong>' . ($residence['passportNumber'] ?? 'N/A') . '</strong> Profession <strong>' . ($residence['profession'] ?? 'N/A') . '</strong> is our employee as in <strong>' . ($company['company_name'] ?? 'N/A') . '</strong>.</p>
        <p>We do not have any objection for him/her to obtain any kind of employment with another company.</p>
        <p>He/She is allowed to work in another company on a contract or part-time basis.<br>The management has no objection to his/her working with another company.</p>
        <p>Thanks & Regards,</p>
        <p><strong>Signatory Designation<br>Signatory Person</strong></p>
        <p><strong>' . ($company['company_name'] ?? 'N/A') . '</strong></p>
        <p class="signature-stamp-label">Sign & Stamp<br><strong>Manager Director</strong></p>
    ';
} else {
    $title = "Error";
    $content = '<div class="date">Date: ' . date("M, d Y") . '</div><h1>' . $title . '</h1><p>Invalid type specified. Please use "noc" or "salary_certificate" with a bank ID if applicable.</p>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,700&display=swap" rel="stylesheet">
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f0f0;
        }
        .container {
            width: 210mm; /* A4 width */
            text-align: center;
        }
        .letter {
            width: 210mm;
            height: 281mm; /* Your adjusted A4 height */
            padding: 15mm; /* Left and right padding */
            padding-top: 58mm; /* Push content down */
            padding-bottom: 0mm; /* Reduced bottom padding to fit */
            background-size: contain; /* Fit letterhead */
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden; /* Prevent overflow */
            <?php if (!empty($company['letterhead'])) { ?>
                background-image: url('/letters/<?php echo $company['letterhead']; ?>');
            <?php } ?>
        }
        .content {
            font-family: 'Noto Sans', sans-serif;
            font-size: 11pt;
            color: #000;
            text-align: left;
        }
        .date {
            text-align: right;
            font-size: 10pt;
            margin-bottom: 8mm;
        }
        h1 {
            font-size: 16pt;
            font-weight: 700;
            text-align: center;
            margin: 0 0 8mm 0;
        }
        p {
            margin: 0 0 4mm 0; /* Tight spacing */
            line-height: 1.3;
        }
        .signature-stamp-label {
            margin: 0 0 2mm 0; /* Minimal space before signature/stamp */
        }
        .signature-stamp {
            display: flex;
            justify-content: flex-start; /* Align left */
            gap: 10mm; /* Space between signature and stamp */
        }
        .signature, .stamp {
            width: 150px; /* Your adjusted size */
            height: auto;
        }
        .print-btn {
            display: block;
            margin: 15mm auto;
            padding: 12px 35px;
            font-size: 14pt;
            font-family: 'Noto Sans', sans-serif;
            color: #fff;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .print-btn:hover {
            background-color: #218838;
        }
        @media print {
            body {
                background-color: #fff;
                display: block;
            }
            .container {
                margin: 0;
                padding: 0;
            }
            .letter {
                box-shadow: none;
                page-break-inside: avoid; /* Keep on one page */
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="print-btn" onclick="window.print()">Print <?php echo $title; ?></button>
        <div class="letter">
            <div class="content">
                <?php echo $content; ?>
                <?php if (!empty($company['signature']) || !empty($company['stamp'])) { ?>
                    <div class="signature-stamp">
                        <?php if (!empty($company['signature'])) { ?>
                            <img src="/letters/<?php echo $company['signature']; ?>" class="signature">
                        <?php } ?>
                        <?php if (!empty($company['stamp'])) { ?>
                            <img src="/letters/<?php echo $company['stamp']; ?>" class="stamp">
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>