<?php
session_start();
error_reporting(E_ALL);
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
}
include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Residence' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$insert = $records[0]['insert'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
if ($select == 0) {
    echo "<script>window.location.href='pageNotFound.php'</script>";
}

if (isset($_POST['Select_Customer'])) {
    $selectQuery = $pdo->prepare("SELECT customer_id, customer_name, customer_ref FROM `customer` ORDER BY customer_name ASC ");
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $supplier = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($supplier);
} else if (isset($_POST['GetNationalities'])) {
    $selectQuery = $pdo->prepare("SELECT DISTINCT countryName AS mainCountryName, (SELECT airport_id FROM airports WHERE 
        countryName = mainCountryName LIMIT 1) AS airport_id FROM airports ORDER BY countryName ASC");
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $nationality = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($nationality);
} else if (isset($_POST['GetCompanies'])) {
    $selectQuery = $pdo->prepare("SELECT company_id, company_name, (SELECT IFNULL(residence.company,0) FROM residence WHERE
        residenceID=:residenceID) AS selectedCompay FROM `company` ORDER BY company_name ASC ");
    $selectQuery->bindParam(':residenceID', $_POST['ID']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $company = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($company);
} else if (isset($_POST['SELECT_Supplier'])) {
    $selectQuery = $pdo->prepare("SELECT supp_id, supp_name FROM supplier ORDER by supp_name ASC");
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $supplier = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($supplier);
} else if (isset($_POST['Select_Accounts'])) {
    $selectQuery = $pdo->prepare("SELECT account_ID, account_Name FROM accounts ORDER by account_Name ASC");
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $account_Name = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($account_Name);
} else if (isset($_POST['CurrencyTypes'])) {
    if ($_POST['Type'] == "salaryCur") {
        $selectQuery =  $pdo->prepare("SELECT currencyID,currencyName, (SELECT IFNULL(salaryCurID,0) FROM 
            residence WHERE residenceID = :residenceID) AS salaryCurID FROM currency ORDER BY currencyName ASC");
        $selectQuery->bindParam(':residenceID', $_POST['SelectedCurrency']);
    } else if ($_POST['Type'] == "offerLCostCur") {
        $selectQuery =  $pdo->prepare("SELECT currencyID,currencyName, (SELECT IFNULL(offerLetterCostCur,0) FROM 
            residence WHERE residenceID = :residenceID) AS offerLetterCostCur FROM currency ORDER BY currencyName ASC");
        $selectQuery->bindParam(':residenceID', $_POST['SelectedCurrency']);
    } else if ($_POST['Type'] == "laborCardFeeCur") {
        $selectQuery =  $pdo->prepare("SELECT currencyID,currencyName, (SELECT IFNULL(laborCardCur,0) FROM 
            residence WHERE residenceID = :residenceID) AS laborCardCur FROM currency ORDER BY currencyName ASC");
        $selectQuery->bindParam(':residenceID', $_POST['SelectedCurrency']);
    } else if ($_POST['Type'] == "EvisaTying") {
        $selectQuery =  $pdo->prepare("SELECT currencyID,currencyName, (SELECT IFNULL(eVisaCur,0) FROM 
            residence WHERE residenceID = :residenceID) AS eVisaCur FROM currency ORDER BY currencyName ASC");
        $selectQuery->bindParam(':residenceID', $_POST['SelectedCurrency']);
    } else if ($_POST['Type'] == "changeStatus") {
        $selectQuery =  $pdo->prepare("SELECT currencyID,currencyName, (SELECT IFNULL(changeStatusCur,0) FROM 
            residence WHERE residenceID = :residenceID) AS changeStatusCur FROM currency ORDER BY currencyName ASC");
        $selectQuery->bindParam(':residenceID', $_POST['SelectedCurrency']);
    } else if ($_POST['Type'] == "medicalTyping") {
        $selectQuery =  $pdo->prepare("SELECT currencyID,currencyName, (SELECT IFNULL(medicalTCur,0) FROM 
            residence WHERE residenceID = :residenceID) AS medicalTCur FROM currency ORDER BY currencyName ASC");
        $selectQuery->bindParam(':residenceID', $_POST['SelectedCurrency']);
    } else if ($_POST['Type'] == "emiratesIDTyping") {
        $selectQuery =  $pdo->prepare("SELECT currencyID,currencyName, (SELECT IFNULL(emiratesIDCur,0) FROM 
            residence WHERE residenceID = :residenceID) AS emiratesIDCur FROM currency ORDER BY currencyName ASC");
        $selectQuery->bindParam(':residenceID', $_POST['SelectedCurrency']);
    } else if ($_POST['Type'] == "visaStamping") {
        $selectQuery =  $pdo->prepare("SELECT currencyID,currencyName, (SELECT IFNULL(visaStampingCur,0) FROM 
            residence WHERE residenceID = :residenceID) AS visaStampingCur FROM currency ORDER BY currencyName ASC");
        $selectQuery->bindParam(':residenceID', $_POST['SelectedCurrency']);
    } else if ($_POST['Type'] == "insuranceCur") {
        $selectQuery =  $pdo->prepare("SELECT currencyID,currencyName, (SELECT IFNULL(insuranceCur,0) FROM 
            residence WHERE residenceID = :residenceID) AS insuranceCur FROM currency ORDER BY currencyName ASC");
        $selectQuery->bindParam(':residenceID', $_POST['SelectedCurrency']);
    } else {
        $selectQuery =  $pdo->prepare("SELECT currencyID,currencyName FROM currency ORDER BY currencyName ASC");
    }
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($currencies);
} else if (isset($_POST['GetChargedEnitity'])) {
    if ($_POST['Type'] == "offerLetter") {
        if ($_POST['Handler'] == 'load') {
            $decisionFlag = $pdo->prepare("SELECT IFNULL(offerLetterSupplier,0) AS offerLetterSupplier, 
                IfNULL(offerLetterAccount,0) AS  offerLetterAccount FROM residence WHERE residenceID=
                :residenceID");
            $decisionFlag->bindParam(':residenceID', $_POST['ID']);
            $decisionFlag->execute();
            $result = $decisionFlag->fetchColumn();
            $secondResult = $decisionFlag->fetchColumn(1);
            if ($result != 0) {
                $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(offerLetterSupplier,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                    supp_name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            } else {
                $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(offerLetterAccount,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                    account_Name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            }
        } else if ($_POST['Handler'] == 'change' && $_POST['ChargedON'] == 2) {

            $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(offerLetterSupplier,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                supp_name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        } else {

            $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(offerLetterAccount,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                account_Name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        }
    } else if ($_POST['Type'] == "LaborCard") {
        if ($_POST['Handler'] == 'load') {
            $decisionFlag = $pdo->prepare("SELECT IFNULL(laborCardSupplier,0) AS laborCardSupplier, 
                IfNULL(laborCardAccount,0) AS  laborCardAccount FROM residence WHERE residenceID=
                :residenceID");
            $decisionFlag->bindParam(':residenceID', $_POST['ID']);
            $decisionFlag->execute();
            $result = $decisionFlag->fetchColumn();
            $secondResult = $decisionFlag->fetchColumn(1);
            if ($result != 0) {
                $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(laborCardSupplier,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                    supp_name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            } else {
                $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(laborCardAccount,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                    account_Name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            }
        } else if ($_POST['Handler'] == 'change' && $_POST['ChargedON'] == 2) {

            $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(laborCardSupplier,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                supp_name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        } else {

            $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(laborCardAccount,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                account_Name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        }
    } else if ($_POST['Type'] == "EVisaTyping") {
        if ($_POST['Handler'] == 'load') {
            $decisionFlag = $pdo->prepare("SELECT IFNULL(eVisaSupplier,0) AS eVisaSupplier, 
                IfNULL(eVisaAccount,0) AS  eVisaAccount FROM residence WHERE residenceID=
                :residenceID");
            $decisionFlag->bindParam(':residenceID', $_POST['ID']);
            $decisionFlag->execute();
            $result = $decisionFlag->fetchColumn();
            $secondResult = $decisionFlag->fetchColumn(1);
            if ($result != 0) {
                $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(eVisaSupplier,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                    supp_name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            } else {
                $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(eVisaAccount,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                    account_Name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            }
        } else if ($_POST['Handler'] == 'change' && $_POST['ChargedON'] == 2) {

            $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(eVisaSupplier,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                supp_name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        } else {

            $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(eVisaAccount,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                account_Name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        }
    } else if ($_POST['Type'] == "changeStatus") {
        if ($_POST['Handler'] == 'load') {
            $decisionFlag = $pdo->prepare("SELECT IFNULL(changeStatusSupplier,0) AS changeStatusSupplier, 
                IfNULL(changeStatusAccount,0) AS  changeStatusAccount FROM residence WHERE residenceID=
                :residenceID");
            $decisionFlag->bindParam(':residenceID', $_POST['ID']);
            $decisionFlag->execute();
            $result = $decisionFlag->fetchColumn();
            $secondResult = $decisionFlag->fetchColumn(1);
            if ($result != 0) {
                $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(changeStatusSupplier,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                    supp_name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            } else {
                $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(changeStatusAccount,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                    account_Name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            }
        } else if ($_POST['Handler'] == 'change' && $_POST['ChargedON'] == 2) {

            $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(changeStatusSupplier,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                supp_name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        } else {

            $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(changeStatusAccount,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                account_Name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        }
    } else if ($_POST['Type'] == "medicalTyping") {
        if ($_POST['Handler'] == 'load') {
            $decisionFlag = $pdo->prepare("SELECT IFNULL(medicalSupplier,0) AS medicalSupplier, 
                IfNULL(medicalAccount,0) AS  medicalAccount FROM residence WHERE residenceID=
                :residenceID");
            $decisionFlag->bindParam(':residenceID', $_POST['ID']);
            $decisionFlag->execute();
            $result = $decisionFlag->fetchColumn();
            $secondResult = $decisionFlag->fetchColumn(1);
            if ($result != 0) {
                $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(medicalSupplier,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                    supp_name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            } else {
                $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(medicalAccount,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                    account_Name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            }
        } else if ($_POST['Handler'] == 'change' && $_POST['ChargedON'] == 2) {

            $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(medicalSupplier,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                supp_name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        } else {

            $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(medicalAccount,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                account_Name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        }
    } else if ($_POST['Type'] == "emiratesIDTyping") {
        if ($_POST['Handler'] == 'load') {
            $decisionFlag = $pdo->prepare("SELECT IFNULL(emiratesIDSupplier,0) AS emiratesIDSupplier, 
                IfNULL(emiratesIDAccount,0) AS  emiratesIDAccount FROM residence WHERE residenceID=
                :residenceID");
            $decisionFlag->bindParam(':residenceID', $_POST['ID']);
            $decisionFlag->execute();
            $result = $decisionFlag->fetchColumn();
            $secondResult = $decisionFlag->fetchColumn(1);
            if ($result != 0) {
                $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(emiratesIDSupplier,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                    supp_name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            } else {
                $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(emiratesIDAccount,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                    account_Name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            }
        } else if ($_POST['Handler'] == 'change' && $_POST['ChargedON'] == 2) {

            $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(emiratesIDSupplier,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                supp_name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        } else {

            $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(emiratesIDAccount,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                account_Name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        }
    } else if ($_POST['Type'] == "visaStamping") {
        if ($_POST['Handler'] == 'load') {
            $decisionFlag = $pdo->prepare("SELECT IFNULL(visaStampingSupplier,0) AS visaStampingSupplier, 
                IfNULL(visaStampingAccount,0) AS  visaStampingAccount FROM residence WHERE residenceID=
                :residenceID");
            $decisionFlag->bindParam(':residenceID', $_POST['ID']);
            $decisionFlag->execute();
            $result = $decisionFlag->fetchColumn();
            $secondResult = $decisionFlag->fetchColumn(1);
            if ($result != 0) {
                $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(visaStampingSupplier,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                    supp_name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            } else {
                $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(visaStampingAccount,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                    account_Name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            }
        } else if ($_POST['Handler'] == 'change' && $_POST['ChargedON'] == 2) {

            $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(visaStampingSupplier,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                supp_name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        } else {

            $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(visaStampingAccount,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                account_Name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        }
    } else if ($_POST['Type'] == "insuranceCur") {
        if ($_POST['Handler'] == 'load') {
            $decisionFlag = $pdo->prepare("SELECT IFNULL(insuranceSupplier,0) AS insuranceSupplier, 
                IfNULL(insuranceAccount,0) AS  insuranceAccount FROM residence WHERE residenceID=
                :residenceID");
            $decisionFlag->bindParam(':residenceID', $_POST['ID']);
            $decisionFlag->execute();
            $result = $decisionFlag->fetchColumn();
            $secondResult = $decisionFlag->fetchColumn(1);
            if ($result != 0) {
                $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(insuranceSupplier,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                    supp_name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            } else {
                $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(insuranceAccount,0) FROM 
                    residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                    account_Name ASC");
                $selectQuery->bindParam(':residenceID', $_POST['ID']);
            }
        } else if ($_POST['Handler'] == 'change' && $_POST['ChargedON'] == 2) {

            $selectQuery =  $pdo->prepare("SELECT supp_id,supp_name, (SELECT IFNULL(insuranceSupplier,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedSupplier, 2 AS chargedON FROM supplier ORDER BY 
                supp_name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        } else {

            $selectQuery =  $pdo->prepare("SELECT account_ID,account_Name, (SELECT IFNULL(insuranceAccount,0) FROM 
                residence WHERE residenceID = :residenceID) AS selectedAccount, 1 AS chargedON FROM accounts ORDER BY 
                account_Name ASC");
            $selectQuery->bindParam(':residenceID', $_POST['ID']);
        }
    }
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($currencies);
} else if (isset($_POST['GetPositions'])) {
    $selectQuery =  $pdo->prepare("SELECT position_id,posiiton_name, (SELECT IFNULL(positionID,0) FROM 
            residence WHERE residenceID = :residenceID) AS PositionID FROM position ORDER BY posiiton_name ASC");
    $selectQuery->bindParam(':residenceID', $_POST['ID']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $positions = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($positions);
} else if (isset($_POST['SelectVisaType'])) {
    $selectQuery = $pdo->prepare("SELECT `country_id`, `country_names` FROM `country_name` ORDER BY country_names ASC");

    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $visaType = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($visaType);
} else if (isset($_POST['GetSalaryAndCostAmounts'])) {
    $selectQuery = $pdo->prepare("SELECT IFNULL(salary_amount,0) AS salary_amount, mb_number, IFNULL(offerLetterCost,0) AS 
        offerLetterCost,IFNULL((SELECT IFNULL(ResidenceDocID,0)  FROM `residencedocuments` WHERE ResID = :residenceID AND  
        fileType = 2 LIMIT 1),0) AS ResidenceDocID FROM `residence` WHERE residenceID = :residenceID ");
    $selectQuery->bindParam(':residenceID', $_POST['ID']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $offLSalaryAdCost = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($offLSalaryAdCost);
} else if (isset($_POST['GetInsuranceCost'])) {
    $selectQuery = $pdo->prepare("SELECT IFNULL(insuranceCost,0) AS insuranceCost, IFNULL((SELECT IFNULL(ResidenceDocID,0)  FROM 
        `residencedocuments` WHERE ResID = :residenceID AND  fileType = 3 LIMIT 1),0) AS ResidenceDocID 
        FROM `residence` WHERE residenceID = :residenceID ");
    $selectQuery->bindParam(':residenceID', $_POST['ID']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $insuranceCost = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($insuranceCost);
} else if (isset($_POST['GetLabourCrdIDAndFee'])) {
    $selectQuery = $pdo->prepare("SELECT IFNULL(laborCardID,'') AS laborCardID,IFNULL(laborCardFee,0) AS laborCardFee, mb_number,
        IFNULL((SELECT IFNULL(ResidenceDocID,0)  FROM `residencedocuments` WHERE ResID = :residenceID AND  fileType = 4 LIMIT 1
        ),0) AS ResidenceDocID FROM `residence` WHERE residenceID = :residenceID ");
    $selectQuery->bindParam(':residenceID', $_POST['ID']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $offLSalaryAdCost = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($offLSalaryAdCost);
} else if (isset($_POST['GetVisaTypingFee'])) {
    $selectQuery = $pdo->prepare("SELECT IFNULL(eVisaCost,0) AS eVisaCost, IFNULL((SELECT IFNULL(ResidenceDocID,0)  FROM 
        `residencedocuments` WHERE ResID = :residenceID AND  fileType = 5 LIMIT 1),0) AS ResidenceDocID 
        FROM `residence` WHERE residenceID = :residenceID ");
    $selectQuery->bindParam(':residenceID', $_POST['ID']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $evisaCost = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($evisaCost);
} else if (isset($_POST['GetChangeStatusFee'])) {
    $selectQuery = $pdo->prepare("SELECT IFNULL(changeStatusCost,0) AS changeStatusCost, IFNULL((SELECT IFNULL(ResidenceDocID,0)  FROM 
        `residencedocuments` WHERE ResID = :residenceID AND  fileType = 6 LIMIT 1),0) AS ResidenceDocID 
        FROM `residence` WHERE residenceID = :residenceID ");
    $selectQuery->bindParam(':residenceID', $_POST['ID']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $changeStatusCost = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($changeStatusCost);
} else if (isset($_POST['GetMedicalFee'])) {
    $selectQuery = $pdo->prepare("SELECT IFNULL(medicalTCost,0) AS medicalTCost, IFNULL((SELECT IFNULL(ResidenceDocID,0)  FROM 
        `residencedocuments` WHERE ResID = :residenceID AND  fileType = 7 LIMIT 1),0) AS ResidenceDocID 
        FROM `residence` WHERE residenceID = :residenceID ");
    $selectQuery->bindParam(':residenceID', $_POST['ID']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $medicalTCost = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($medicalTCost);
} else if (isset($_POST['GetEmiratesIDTCost'])) {
    $selectQuery = $pdo->prepare("SELECT IFNULL(emiratesIDCost,0) AS emiratesIDCost, IFNULL((SELECT IFNULL(ResidenceDocID,0)  FROM 
        `residencedocuments` WHERE ResID = :residenceID AND  fileType = 8 LIMIT 1),0) AS ResidenceDocID 
        FROM `residence` WHERE residenceID = :residenceID ");
    $selectQuery->bindParam(':residenceID', $_POST['ID']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $medicalTCost = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($medicalTCost);
} else if (isset($_POST['GetVisaStamping'])) {
    $selectQuery = $pdo->prepare("SELECT IFNULL(visaStampingCost,0) AS visaStampingCost, IFNULL(expiry_date,CURRENT_DATE()) AS
        expiry_date, IFNULL(LabourCardNumber,'') AS LabourCardNumber,IFNULL((SELECT IFNULL(ResidenceDocID,0)  FROM 
        `residencedocuments` WHERE ResID = :residenceID AND  fileType = 9 LIMIT 1),0) AS ResidenceDocID FROM `residence` WHERE
        residenceID = :residenceID ");
    $selectQuery->bindParam(':residenceID', $_POST['ID']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $GetVisaStamping = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($GetVisaStamping);
} else if (isset($_POST['GetContractSubmmision'])) {
    $selectQuery = $pdo->prepare("SELECT IFNULL(EmiratesIDNumber,'') AS EmiratesIDNumber, IFNULL((SELECT IFNULL(ResidenceDocID,0)  FROM 
        `residencedocuments` WHERE ResID = :residenceID AND  fileType = 10 LIMIT 1),0) AS ResidenceDocID 
        FROM `residence` WHERE residenceID = :residenceID ");
    $selectQuery->bindParam(':residenceID', $_POST['ID']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $GetContractSubmmision = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($GetContractSubmmision);
} else if (isset($_POST['GetUploadedFiles'])) {
    $selectQuery = $pdo->prepare("SELECT ResidenceDocID, original_name FROM `residencedocuments` WHERE ResID = 
        :ResID AND fileType = :fileType ORDER BY ResidenceDocID DESC");
    $selectQuery->bindParam(':ResID', $_POST['ID']);
    $selectQuery->bindParam(':fileType', $_POST['Type']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $uploadedDocuments = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($uploadedDocuments);
} else if (isset($_POST['DeleteFile'])) {
    try {
        // First of all, let's begin a transaction
        $pdo->beginTransaction();
        // Update status of ticket
        $sql = "SELECT file_name FROM residencedocuments WHERE ResidenceDocID = :ResidenceDocID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ResidenceDocID', $_POST['ID']);
        $stmt->execute();
        $file =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $file =  $file[0]['file_name'];
        if (file_exists('residence/' . $file)) {
            unlink('residence/' . $file);
        } else {
        }
        $deleteSelectedFileSql = "DELETE FROM residencedocuments WHERE ResidenceDocID = :ResidenceDocID";
        $deleteSelectedFileStmt = $pdo->prepare($deleteSelectedFileSql);
        $deleteSelectedFileStmt->bindParam(':ResidenceDocID', $_POST['ID']);
        $deleteSelectedFileStmt->execute();
        $pdo->commit();
        echo "Success";
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['EditBasicData'])) {
    $selectQuery = $pdo->prepare("
        SELECT customer_id,passenger_name,Nationality,VisaType,sale_price,saleCurID, passportNumber, passportExpiryDate, InsideOutside, uid, salary_amount, positionID, gender, dob,
        IFNULL((SELECT IFNULL(ResidenceDocID,0)  FROM `residencedocuments` WHERE ResID = :residenceID AND  fileType = 1 LIMIT 1),0) AS ResidenceDocID ,
        IFNULL((SELECT IFNULL(ResidenceDocID,0)  FROM `residencedocuments` WHERE ResID = :residenceID AND  fileType = 11 LIMIT 1),0) AS ResidenceDocIDPhoto ,
        IFNULL((SELECT IFNULL(ResidenceDocID,0)  FROM `residencedocuments` WHERE ResID = :residenceID AND  fileType = 12 LIMIT 1),0) AS ResidenceDocIDIDFront ,
        IFNULL((SELECT IFNULL(ResidenceDocID,0)  FROM `residencedocuments` WHERE ResID = :residenceID AND  fileType = 13 LIMIT 1),0) AS ResidenceDocIDIDBack
        FROM residence WHERE residenceID = :residenceID
        ");
    $selectQuery->bindParam(':residenceID', $_POST['GRID']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($data);
} else if (isset($_POST['InsertCompanyName'])) {
    try {
        $sql = "INSERT INTO 
                `company`(`company_name`,`company_number`,`starting_quota`,`local_name`,`company_expiry`,`company_type`) 
                VALUES(:company_name,:company_number,:starting_quota,:local_name,:company_expiry,:company_type)
                ";
        // create prepared statement
        $stmt = $pdo->prepare($sql);
        // bind parameters to statement
        $stmt->bindParam(':company_name', $_POST['CompanyName']);
        $stmt->bindParam(':company_number', $_POST['CompanyNumber']);
        $stmt->bindParam(':starting_quota', $_POST['StartingQuota']);
        $stmt->bindParam(':local_name', $_POST['LocalName']);
        $stmt->bindParam(':company_expiry', $_POST['CompanyExpiry']);
        $stmt->bindParam(':company_type', $_POST['CompanyType']);
        // execute the prepared statement
        $stmt->execute();
        echo "Success";
    } catch (PDOException $e) {
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['InsertPositionName'])) {
    try {
        $sql = "INSERT INTO `position`(`posiiton_name`) VALUES(:posiiton_name)";
        // create prepared statement
        $stmt = $pdo->prepare($sql);
        // bind parameters to statement
        $stmt->bindParam(':posiiton_name', $_POST['Position_Name']);
        // execute the prepared statement
        $stmt->execute();
        echo "Success";
    } catch (PDOException $e) {
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['InsertBasicData'])) {
    try {
        $image = '';
        if ($_FILES['basicInfoFile']['name'] != '') {
            $image = uploadFile('basicInfoFile');
            if ($image == '') {
                $image = 'Error';
            }
        }

        $imagePhoto = '';
        if ($_FILES['basicInfoFilePhoto']['name'] != '') {
            $imagePhoto = uploadFile('basicInfoFilePhoto');
            if ($imagePhoto == '') {
                $imagePhoto = 'Error';
            }
        }

        $imageIDFront = '';
        if ($_FILES['basicInfoFileIDFront']['name'] != '') {
            $imageIDFront = uploadFile('basicInfoFileIDFront');
            if ($imageIDFront == '') {
                $imageIDFront = 'Error';
            }
        }

        $imageIDBack = '';
        if ($_FILES['basicInfoFileIDBack']['name'] != '') {
            $imageIDBack = uploadFile('basicInfoFileIDBack');
            if ($imageIDBack == '') {
                $imageIDBack = 'Error';
            }
        }

        // First of all, let's begin a transaction
        $pdo->beginTransaction();
        if ($image == 'Error') {
            $pdo->rollback();
            echo "Record not added becuase of file uploader";
        }
        if ($_POST['Type'] == "active") {
            $sql = "INSERT INTO `residence`
                    (`customer_id`, `passenger_name`, `Nationality`, `passportNumber`, `passportExpiryDate`, `VisaType`, `sale_price`,
                    `saleCurID`, `StepOneUploader`, `completedStep`,`status`,InsideOutside,`uid`,`salary_amount`,`positionID`, `gender`,`dob`) 
                    VALUES(:customer_id,:passenger_name,:Nationality, :passportNumber, :passportExpiryDate,
                    :VisaType,:sale_price,:saleCurID,:StepOneUploader,:completedStep,:status, :insideOutside,:uid,:salary_amount,:positionID, :gender,:dob)";
        } else if ($_POST['Type'] == "completed") {
            $sql = "UPDATE `residence` SET customer_id=:customer_id,passenger_name=:passenger_name,Nationality=:Nationality, passportNumber = :passportNumber, 
                    passportExpiryDate = :passportExpiryDate, InsideOutside = :insideOutside, `uid` = :uid, `salary_amount` = :salary_amount, positionID = :positionID, gender = :gender, dob = :dob,
                    VisaType=:VisaType,sale_price=:sale_price,saleCurID=:saleCurID,StepOneUploader=:StepOneUploader WHERE residenceID
                    =:residenceID";
        }
        $stmt = $pdo->prepare($sql);
        // bind parameters to statement
        $stmt->bindParam(':customer_id', $_POST['customer_id']);
        $stmt->bindParam(':passenger_name', $_POST['passengerName']);
        $stmt->bindParam(':Nationality', $_POST['nationality']);
        $stmt->bindParam(':VisaType', $_POST['visaType']);
        $stmt->bindParam(':sale_price', $_POST['sale_amount']);
        $stmt->bindParam(':saleCurID', $_POST['sale_currency_type']);
        $stmt->bindParam(':StepOneUploader', $_SESSION['user_id']);
        $stmt->bindParam(':passportNumber', $_POST['passportNumber']);
        $stmt->bindParam(':passportExpiryDate', $_POST['passportExpiryDate']);
        $stmt->bindParam(':insideOutside', $_POST['insideOutside']);
        $stmt->bindParam(':uid', $_POST['uid']);
        $stmt->bindParam(':salary_amount', $_POST['salary_amount']);
        $stmt->bindParam(':positionID', $_POST['position']);
        $stmt->bindParam(':gender', $_POST['gender']);
        $stmt->bindParam(':dob', $_POST['dob']);

        if ($_POST['Type'] == "active") {
            $stepCompleted = 1;
            $status = 1;
            $stmt->bindParam(':completedStep', $stepCompleted);
            $stmt->bindParam(':status', $status);
        }
        if ($_POST['Type'] == "completed") {
            $stmt->bindParam(':residenceID', $_POST['ID']);
        }
        // execute the prepared statement
        if ($_POST['Type'] == "completed" && checkValidResideneceID($_POST['ID']) == 1) {
            $stmt->execute();
        } else if ($_POST['Type'] == "active") {
            $stmt->execute();
        }
        $getResidenceID = $pdo->lastInsertId();

        $ResID = (isset($_POST['Type']) && $_POST['Type'] == "active") ? $getResidenceID : $_POST['ID'];

        if ($image != 'Error' && $image != '') {
            $filetype = '1';

            $stmt = $pdo->prepare("SELECT * FROM `residencedocuments` WHERE ResID = '{$ResID}' AND fileType = '{$filetype}'");
            $stmt->execute();
            $file = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($file) {
                // delete existing file and upload new file
                if (file_exists('residence/' . $file['file_name'])) {
                    unlink('residence/' . $file['file_name']);
                }

                $fileSql = "UPDATE `residencedocuments` SET `file_name` = :file_name, `original_name` = :original_name WHERE ResID = :ResID AND fileType = :fileType";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $ResID);
                $fileStmt->bindParam(':file_name', $image);
                $fileStmt->bindParam(':original_name', $_FILES['basicInfoFile']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            } else {
                $fileSql = "INSERT INTO `residencedocuments`(`ResID`, `file_name`, `original_name`, `fileType`) VALUES (:ResID,:file_name,:original_name,:fileType)";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $ResID);
                $fileStmt->bindParam(':file_name', $image);
                $fileStmt->bindParam(':original_name', $_FILES['basicInfoFile']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            }
        }

        if ($imagePhoto != 'Error' && $imagePhoto != '') {
            $filetype = 11;

            $stmt = $pdo->prepare("SELECT * FROM `residencedocuments` WHERE ResID = '{$ResID}' AND fileType = '{$filetype}'");
            $stmt->execute();
            $file = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($file) {

                if (file_exists('residence/' . $file['file_name'])) {
                    unlink('residence/' . $file['file_name']);
                }

                $fileSql = "UPDATE `residencedocuments` SET `file_name` = :file_name, `original_name` = :original_name WHERE ResID = :ResID AND fileType = :fileType";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $ResID);
                $fileStmt->bindParam(':file_name', $imagePhoto);
                $fileStmt->bindParam(':original_name', $_FILES['basicInfoFilePhoto']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            } else {
                $fileSql = "INSERT INTO `residencedocuments`(`ResID`, `file_name`, `original_name`, `fileType`) VALUES
                            (:ResID,:file_name,:original_name,:fileType)";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $ResID);
                $fileStmt->bindParam(':file_name', $imagePhoto);
                $fileStmt->bindParam(':original_name', $_FILES['basicInfoFilePhoto']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            }
        }

        if ($imageIDFront != 'Error' && $imageIDFront != '') {
            $filetype = 12;

            $stmt = $pdo->prepare("SELECT * FROM `residencedocuments` WHERE ResID = '{$ResID}' AND fileType = '{$filetype}'");
            $stmt->execute();
            $file = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($file) {

                if (file_exists('residence/' . $file['file_name'])) {
                    unlink('residence/' . $file['file_name']);
                }

                $fileSql = "UPDATE `residencedocuments` SET `file_name` = :file_name, `original_name` = :original_name WHERE ResID = :ResID AND fileType = :fileType";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $ResID);
                $fileStmt->bindParam(':file_name', $imageIDFront);
                $fileStmt->bindParam(':original_name', $_FILES['basicInfoFileIDFront']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            } else {
                $fileSql = "INSERT INTO `residencedocuments`(`ResID`, `file_name`, `original_name`, `fileType`) VALUES (:ResID,:file_name,:original_name,:fileType)";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $ResID);
                $fileStmt->bindParam(':file_name', $imageIDFront);
                $fileStmt->bindParam(':original_name', $_FILES['basicInfoFileIDFront']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            }
        }

        if ($imageIDBack != 'Error' && $imageIDBack != '') {
            $filetype = 13;

            $stmt = $pdo->prepare("SELECT * FROM `residencedocuments` WHERE ResID = '{$ResID}' AND fileType = '{$filetype}'");
            $stmt->execute();
            $file = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($file) {

                if (file_exists('residence/' . $file['file_name'])) {
                    unlink('residence/' . $file['file_name']);
                }

                $fileSql = "UPDATE `residencedocuments` SET `file_name` = :file_name, `original_name` = :original_name WHERE ResID = :ResID AND fileType = :fileType";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $ResID);
                $fileStmt->bindParam(':file_name', $imageIDBack);
                $fileStmt->bindParam(':original_name', $_FILES['basicInfoFileIDBack']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            } else {
                $fileSql = "INSERT INTO `residencedocuments`(`ResID`, `file_name`, `original_name`, `fileType`) VALUES (:ResID,:file_name,:original_name,:fileType)";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $ResID);
                $fileStmt->bindParam(':file_name', $imageIDBack);
                $fileStmt->bindParam(':original_name', $_FILES['basicInfoFileIDBack']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            }
        }



        $pdo->commit();

        echo $getResidenceID;
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['SaveOfferLetterData'])) {
    try {
        if (checkValidResideneceID($_POST['ID']) == 1) {
            $image = '';
            if ($_FILES['offerLetterFile']['name'] != '') {
                $image = uploadFile('offerLetterFile');
                if ($image == '') {
                    $image = 'Error';
                }
            }
            // First of all, let's begin a transaction
            $pdo->beginTransaction();
            if ($image == 'Error') {
                $pdo->rollback();
                echo "Record not added becuase of file uploader";
            }
            if ($image != '') {
                $filetype = 2;
                $fileSql = "INSERT INTO `residencedocuments`(`ResID`, `file_name`, `original_name`, `fileType`) VALUES
                    (:ResID,:file_name,:original_name,:fileType)";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $_POST['ID']);
                $fileStmt->bindParam(':file_name', $image);
                $fileStmt->bindParam(':original_name', $_FILES['offerLetterFile']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            }
            $sql = "UPDATE `residence` SET salary_amount=:salary_amount,salaryCurID=:salaryCurID,positionID=:positionID,
                company=:company,offerLetterCost=:offerLetterCost,offerLetterCostCur=:offerLetterCostCur,offerLetterSupplier=
                :offerLetterSupplier,offerLetterAccount=:offerLetterAccount,stepTwoUploder=:stepTwoUploder, mb_number = :mb_number, offerLetterStatus = 'submitted' ";
            if ($_POST['Type'] == "active") {
                $sql = $sql . ",completedStep =:completedStep ";
            }
            $sql = $sql . " WHERE residenceID =:residenceID";
            $stmt = $pdo->prepare($sql);
            $supplier = NULL;
            $account = NULL;
            if ($_POST['offerLChargOpt'] == 1) {
                $account = $_POST['offerLChargedEntity'];
            } else if ($_POST['offerLChargOpt'] == 2) {
                $supplier = $_POST['offerLChargedEntity'];
            }
            // bind parameters to statement
            $completedStep = 2;
            $stmt->bindParam(':salary_amount', $_POST['salary_amount']);
            $stmt->bindParam(':salaryCurID', $_POST['salaryCur']);
            $stmt->bindParam(':positionID', $_POST['position']);
            $stmt->bindParam(':company', $_POST['company']);
            $stmt->bindParam(':offerLetterCost', $_POST['offerLetterCost']);
            $stmt->bindParam(':offerLetterCostCur', $_POST['offerLetterCostCur']);
            $stmt->bindParam(':offerLetterSupplier', $supplier);
            $stmt->bindParam(':offerLetterAccount', $account);
            $stmt->bindParam(':stepTwoUploder', $_SESSION['user_id']);
            $stmt->bindParam(':mb_number', $_POST['mb_number']);
            if ($_POST['Type'] == "active") {
                $stmt->bindParam(':completedStep', $completedStep);
            }
            $stmt->bindParam(':residenceID', $_POST['ID']);
            // execute the prepared statement
            $stmt->execute();
            $pdo->commit();
            echo "Succcess";
        } else {
            echo "Residence ID is wrong";
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['SaveInsuranceData'])) {
    try {
        if (checkValidResideneceID($_POST['ID']) == 1) {
            $image = '';
            if ($_FILES['insuranceFile']['name'] != '') {
                $image = uploadFile('insuranceFile');
                if ($image == '') {
                    $image = 'Error';
                }
            }
            // First of all, let's begin a transaction
            $pdo->beginTransaction();
            if ($image == 'Error') {
                $pdo->rollback();
                echo "Record not added becuase of file uploader";
            }
            if ($image != '') {
                $filetype = 3;
                $fileSql = "INSERT INTO `residencedocuments`(`ResID`, `file_name`, `original_name`, `fileType`) VALUES
                    (:ResID,:file_name,:original_name,:fileType)";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $_POST['ID']);
                $fileStmt->bindParam(':file_name', $image);
                $fileStmt->bindParam(':original_name', $_FILES['insuranceFile']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            }
            $sql = "UPDATE `residence` SET insuranceCost=:insuranceCost,insuranceCur=:insuranceCur,insuranceSupplier=
                :insuranceSupplier,insuranceAccount=:insuranceAccount,stepThreeUploader=:stepThreeUploader ";
            if ($_POST['Type'] == "active") {
                $sql = $sql . ",completedStep =:completedStep ";
            }
            $sql = $sql . " WHERE residenceID =:residenceID";
            $stmt = $pdo->prepare($sql);
            $supplier = NULL;
            $account = NULL;
            if ($_POST['insuranceChargOpt'] == 1) {
                $account = $_POST['insuranceChargedEntity'];
            } else if ($_POST['insuranceChargOpt'] == 2) {
                $supplier = $_POST['insuranceChargedEntity'];
            }
            // bind parameters to statement
            $completedStep = 3;
            $stmt->bindParam(':insuranceCost', $_POST['insuranceCost']);
            $stmt->bindParam(':insuranceCur', $_POST['insuranceCur']);
            $stmt->bindParam(':insuranceSupplier', $supplier);
            $stmt->bindParam(':insuranceAccount', $account);
            $stmt->bindParam(':stepThreeUploader', $_SESSION['user_id']);
            if ($_POST['Type'] == "active") {
                $stmt->bindParam(':completedStep', $completedStep);
            }
            $stmt->bindParam(':residenceID', $_POST['ID']);
            // execute the prepared statement
            $stmt->execute();
            $pdo->commit();
            echo "Succcess";
        } else {
            echo "Residence ID is wrong";
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['SaveLabourCardData'])) {
    try {
        if (checkValidResideneceID($_POST['ID']) == 1) {
            $image = '';
            if ($_FILES['laborCardFile']['name'] != '') {
                $image = uploadFile('laborCardFile');
                if ($image == '') {
                    $image = 'Error';
                }
            }
            // First of all, let's begin a transaction
            $pdo->beginTransaction();
            if ($image == 'Error') {
                $pdo->rollback();
                echo "Record not added becuase of file uploader";
            }
            if ($image != '') {
                $filetype = 4;
                $fileSql = "INSERT INTO `residencedocuments`(`ResID`, `file_name`, `original_name`, `fileType`) VALUES
                    (:ResID,:file_name,:original_name,:fileType)";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $_POST['ID']);
                $fileStmt->bindParam(':file_name', $image);
                $fileStmt->bindParam(':original_name', $_FILES['laborCardFile']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            }
            $sql = "UPDATE `residence` SET laborCardID=:laborCardID,laborCardFee=:laborCardFee,laborCardCur=:laborCardCur,
                laborCardSupplier=:laborCardSupplier,laborCardAccount=:laborCardAccount,stepfourUploader=:stepfourUploader ";
            if ($_POST['Type'] == "active") {
                $sql = $sql . ",completedStep =:completedStep ";
            }
            $sql = $sql . " WHERE residenceID =:residenceID";
            $stmt = $pdo->prepare($sql);
            $supplier = NULL;
            $account = NULL;
            if ($_POST['lrbChargOpt'] == 1) {
                $account = $_POST['lbrChargedEntity'];
            } else if ($_POST['lrbChargOpt'] == 2) {
                $supplier = $_POST['lbrChargedEntity'];
            }
            // bind parameters to statement
            $completedStep = 4;
            $stmt->bindParam(':laborCardID', $_POST['labor_card_id']);
            $stmt->bindParam(':laborCardFee', $_POST['labour_card_fee']);
            $stmt->bindParam(':laborCardCur', $_POST['laborCardCur']);
            $stmt->bindParam(':laborCardSupplier', $supplier);
            $stmt->bindParam(':laborCardAccount', $account);
            $stmt->bindParam(':stepfourUploader', $_SESSION['user_id']);
            if ($_POST['Type'] == "active") {
                $stmt->bindParam(':completedStep', $completedStep);
            }
            $stmt->bindParam(':residenceID', $_POST['ID']);
            // execute the prepared statement
            $stmt->execute();
            $pdo->commit();
            echo "Succcess";
        } else {
            echo "Residence ID is wrong";
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['SaveEVisaTyping'])) {
    try {
        if (checkValidResideneceID($_POST['ID']) == 1) {
            $image = '';
            if ($_FILES['eVisaFile']['name'] != '') {
                $image = uploadFile('eVisaFile');
                if ($image == '') {
                    $image = 'Error';
                }
            }
            // First of all, let's begin a transaction
            $pdo->beginTransaction();
            if ($image == 'Error') {
                $pdo->rollback();
                echo "Record not added becuase of file uploader";
            }
            if ($image != '') {
                $filetype = 4;
                $fileSql = "INSERT INTO `residencedocuments`(`ResID`, `file_name`, `original_name`, `fileType`) VALUES 
                    (:ResID,:file_name,:original_name,:fileType)";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $_POST['ID']);
                $fileStmt->bindParam(':file_name', $image);
                $fileStmt->bindParam(':original_name', $_FILES['eVisaFile']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            }
            $sql = "UPDATE `residence` SET eVisaCost=:eVisaCost,eVisaCur=:eVisaCur,eVisaSupplier=:eVisaSupplier, eVisaStatus = 'submitted',
                eVisaAccount=:eVisaAccount,stepfiveUploader=:stepfiveUploader";
            if ($_POST['Type'] == "active") {
                $sql = $sql . ",completedStep =:completedStep ";
            }
            $sql = $sql . " WHERE residenceID =:residenceID";
            $stmt = $pdo->prepare($sql);
            $supplier = NULL;
            $account = NULL;
            if ($_POST['eVisaTChargOpt'] == 1) {
                $account = $_POST['eVisaTChargedEntity'];
            } else if ($_POST['eVisaTChargOpt'] == 2) {
                $supplier = $_POST['eVisaTChargedEntity'];
            }
            // bind parameters to statement
            $completedStep = 5;
            $stmt->bindParam(':eVisaCost', $_POST['evisa_cost']);
            $stmt->bindParam(':eVisaCur', $_POST['eVisaCostCur']);
            $stmt->bindParam(':eVisaSupplier', $supplier);
            $stmt->bindParam(':eVisaAccount', $account);
            $stmt->bindParam(':stepfiveUploader', $_SESSION['user_id']);
            if ($_POST['Type'] == "active") {
                $stmt->bindParam(':completedStep', $completedStep);
            }
            $stmt->bindParam(':residenceID', $_POST['ID']);
            // execute the prepared statement
            $stmt->execute();
            $pdo->commit();
            echo "Succcess";
        } else {
            echo "Residence ID is wrong";
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['SaveChangeStatus'])) {
    try {
        if (checkValidResideneceID($_POST['ID']) == 1) {
            $image = '';
            if ($_FILES['changeStatusFile']['name'] != '') {
                $image = uploadFile('changeStatusFile');
                if ($image == '') {
                    $image = 'Error';
                }
            }
            // First of all, let's begin a transaction
            $pdo->beginTransaction();
            if ($image == 'Error') {
                $pdo->rollback();
                echo "Record not added becuase of file uploader";
            }
            if ($image != '') {
                $filetype = 6;
                $fileSql = "INSERT INTO `residencedocuments`(`ResID`, `file_name`, `original_name`, `fileType`) VALUES 
                    (:ResID,:file_name,:original_name,:fileType)";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $_POST['ID']);
                $fileStmt->bindParam(':file_name', $image);
                $fileStmt->bindParam(':original_name', $_FILES['changeStatusFile']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            }
            $sql = "UPDATE `residence` SET changeStatusCost=:changeStatusCost,changeStatusCur=:changeStatusCur,
                changeStatusSupplier=:changeStatusSupplier,changeStatusAccount=:changeStatusAccount,stepsixUploader=
                :stepsixUploader";
            if ($_POST['Type'] == "active") {
                $sql = $sql . ",completedStep =:completedStep ";
            }
            $sql = $sql . " WHERE residenceID =:residenceID";
            $stmt = $pdo->prepare($sql);
            $supplier = NULL;
            $account = NULL;
            if ($_POST['changeSChargOpt'] == 1) {
                $account = $_POST['changeSChargedEntity'];
            } else if ($_POST['changeSChargOpt'] == 2) {
                $supplier = $_POST['changeSChargedEntity'];
            }
            // bind parameters to statement
            $completedStep = 6;
            $stmt->bindParam(':changeStatusCost', $_POST['changeStatusCost']);
            $stmt->bindParam(':changeStatusCur', $_POST['changeStatusCur']);
            $stmt->bindParam(':changeStatusSupplier', $supplier);
            $stmt->bindParam(':changeStatusAccount', $account);
            $stmt->bindParam(':stepsixUploader', $_SESSION['user_id']);
            if ($_POST['Type'] == "active") {
                $stmt->bindParam(':completedStep', $completedStep);
            }
            $stmt->bindParam(':residenceID', $_POST['ID']);
            // execute the prepared statement
            $stmt->execute();
            $pdo->commit();
            echo "Succcess";
        } else {
            echo "Residence ID is wrong";
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['SaveMedicalTyping'])) {
    try {
        if (checkValidResideneceID($_POST['ID']) == 1) {
            $image = '';
            if ($_FILES['medicalFile']['name'] != '') {
                $image = uploadFile('medicalFile');
                if ($image == '') {
                    $image = 'Error';
                }
            }
            // First of all, let's begin a transaction
            $pdo->beginTransaction();
            if ($image == 'Error') {
                $pdo->rollback();
                echo "Record not added becuase of file uploader";
            }
            if ($image != '') {
                $filetype = 7;
                $fileSql = "INSERT INTO `residencedocuments`(`ResID`, `file_name`, `original_name`, `fileType`) VALUES 
                    (:ResID,:file_name,:original_name,:fileType)";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $_POST['ID']);
                $fileStmt->bindParam(':file_name', $image);
                $fileStmt->bindParam(':original_name', $_FILES['medicalFile']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            }
            $sql = "UPDATE `residence` SET medicalTCost=:medicalTCost,medicalTCur=:medicalTCur,
                medicalSupplier=:medicalSupplier,medicalAccount=:medicalAccount,stepsevenUpploader=
                :stepsevenUpploader";
            if ($_POST['Type'] == "active") {
                $sql = $sql . ",completedStep =:completedStep ";
            }
            $sql = $sql . " WHERE residenceID =:residenceID";
            $stmt = $pdo->prepare($sql);
            $supplier = NULL;
            $account = NULL;
            if ($_POST['medicalTChargOpt'] == 1) {
                $account = $_POST['medicalTChargedEntity'];
            } else if ($_POST['medicalTChargOpt'] == 2) {
                $supplier = $_POST['medicalTChargedEntity'];
            }
            // bind parameters to statement
            $completedStep = 7;
            $stmt->bindParam(':medicalTCost', $_POST['medical_cost']);
            $stmt->bindParam(':medicalTCur', $_POST['medicalCostCur']);
            $stmt->bindParam(':medicalSupplier', $supplier);
            $stmt->bindParam(':medicalAccount', $account);
            $stmt->bindParam(':stepsevenUpploader', $_SESSION['user_id']);
            if ($_POST['Type'] == "active") {
                $stmt->bindParam(':completedStep', $completedStep);
            }
            $stmt->bindParam(':residenceID', $_POST['ID']);
            // execute the prepared statement
            $stmt->execute();
            $pdo->commit();
            echo "Succcess";
        } else {
            echo "Residence ID is wrong";
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['SaveEmiratesIDTyping'])) {
    try {
        if (checkValidResideneceID($_POST['ID']) == 1) {
            $image = '';
            if ($_FILES['emiratesIDFile']['name'] != '') {
                $image = uploadFile('emiratesIDFile');
                if ($image == '') {
                    $image = 'Error';
                }
            }
            // First of all, let's begin a transaction
            $pdo->beginTransaction();
            if ($image == 'Error') {
                $pdo->rollback();
                echo "Record not added becuase of file uploader";
            }
            if ($image != '') {
                $filetype = 8;
                $fileSql = "INSERT INTO `residencedocuments`(`ResID`, `file_name`, `original_name`, `fileType`) VALUES 
                    (:ResID,:file_name,:original_name,:fileType)";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $_POST['ID']);
                $fileStmt->bindParam(':file_name', $image);
                $fileStmt->bindParam(':original_name', $_FILES['emiratesIDFile']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            }
            $sql = "UPDATE `residence` SET emiratesIDCost=:emiratesIDCost,emiratesIDCur=:emiratesIDCur,
                emiratesIDSupplier=:emiratesIDSupplier,emiratesIDAccount=:emiratesIDAccount,stepEightUploader=
                :stepEightUploader";
            if ($_POST['Type'] == "active") {
                $sql = $sql . ",completedStep =:completedStep ";
            }
            $sql = $sql . " WHERE residenceID =:residenceID";
            $stmt = $pdo->prepare($sql);
            $supplier = NULL;
            $account = NULL;
            if ($_POST['emirateIDChargOpt'] == 1) {
                $account = $_POST['emiratesIDChargedEntity'];
            } else if ($_POST['emirateIDChargOpt'] == 2) {
                $supplier = $_POST['emiratesIDChargedEntity'];
            }
            // bind parameters to statement
            $completedStep = 8;
            $stmt->bindParam(':emiratesIDCost', $_POST['emiratesIDCost']);
            $stmt->bindParam(':emiratesIDCur', $_POST['emiratesIDCostCur']);
            $stmt->bindParam(':emiratesIDSupplier', $supplier);
            $stmt->bindParam(':emiratesIDAccount', $account);
            $stmt->bindParam(':stepEightUploader', $_SESSION['user_id']);
            if ($_POST['Type'] == "active") {
                $stmt->bindParam(':completedStep', $completedStep);
            }
            $stmt->bindParam(':residenceID', $_POST['ID']);
            // execute the prepared statement
            $stmt->execute();
            $pdo->commit();
            echo "Succcess";
        } else {
            echo "Residence ID is wrong";
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['SaveVisaStamping'])) {
    try {
        if (checkValidResideneceID($_POST['ID']) == 1) {
            $image = '';
            if ($_FILES['visaStampingFile']['name'] != '') {
                $image = uploadFile('visaStampingFile');
                if ($image == '') {
                    $image = 'Error';
                }
            }
            // First of all, let's begin a transaction
            $pdo->beginTransaction();
            if ($image == 'Error') {
                $pdo->rollback();
                echo "Record not added becuase of file uploader";
            }
            if ($image != '') {
                $filetype = 9;
                $fileSql = "INSERT INTO `residencedocuments`(`ResID`, `file_name`, `original_name`, `fileType`) VALUES 
                    (:ResID,:file_name,:original_name,:fileType)";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $_POST['ID']);
                $fileStmt->bindParam(':file_name', $image);
                $fileStmt->bindParam(':original_name', $_FILES['visaStampingFile']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            }
            $sql = "UPDATE `residence` SET visaStampingCost=:visaStampingCost,visaStampingCur=:visaStampingCur,expiry_date=
                :expiry_date,LabourCardNumber=:LabourCardNumber,visaStampingSupplier=:visaStampingSupplier,visaStampingAccount=
                :visaStampingAccount,stepNineUpploader=:stepNineUpploader ";
            if ($_POST['Type'] == "active") {
                $sql = $sql . ",completedStep =:completedStep ";
            }
            $sql = $sql . " WHERE residenceID =:residenceID";
            $stmt = $pdo->prepare($sql);
            $supplier = NULL;
            $account = NULL;
            if ($_POST['visaStampChargOpt'] == 1) {
                $account = $_POST['visaStampChargedEntity'];
            } else if ($_POST['visaStampChargOpt'] == 2) {
                $supplier = $_POST['visaStampChargedEntity'];
            }
            // bind parameters to statement
            $completedStep = 9;
            $stmt->bindParam(':visaStampingCost', $_POST['visaStampingCost']);
            $stmt->bindParam(':visaStampingCur', $_POST['visaStampingCur']);
            $stmt->bindParam(':expiry_date', $_POST['expiry_date']);
            $stmt->bindParam(':LabourCardNumber', $_POST['laborCardNumber']);
            $stmt->bindParam(':visaStampingSupplier', $supplier);
            $stmt->bindParam(':visaStampingAccount', $account);
            $stmt->bindParam(':stepNineUpploader', $_SESSION['user_id']);
            if ($_POST['Type'] == "active") {
                $stmt->bindParam(':completedStep', $completedStep);
            }
            $stmt->bindParam(':residenceID', $_POST['ID']);
            // execute the prepared statement
            $stmt->execute();
            $pdo->commit();
            echo "Succcess";
        } else {
            echo "Residence ID is wrong";
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['SaveContractSubmission'])) {
    try {
        if (checkValidResideneceID($_POST['ID']) == 1) {
            $image = '';
            if ($_FILES['contractSubmissionFile']['name'] != '') {
                $image = uploadFile('contractSubmissionFile');
                if ($image == '') {
                    $image = 'Error';
                }
            }
            // First of all, let's begin a transaction
            $pdo->beginTransaction();
            if ($image == 'Error') {
                $pdo->rollback();
                echo "Record not added becuase of file uploader";
            }

            if ($image != '') {
                $filetype = 10;
                $fileSql = "INSERT INTO `residencedocuments`(`ResID`, `file_name`, `original_name`, `fileType`) VALUES 
                    (:ResID,:file_name,:original_name,:fileType)";
                $fileStmt = $pdo->prepare($fileSql);
                $fileStmt->bindParam(':ResID', $_POST['ID']);
                $fileStmt->bindParam(':file_name', $image);
                $fileStmt->bindParam(':original_name', $_FILES['contractSubmissionFile']['name']);
                $fileStmt->bindParam(':fileType', $filetype);
                $fileStmt->execute();
            }
            $sql = "UPDATE `residence` SET EmiratesIDNumber=:EmiratesIDNumber,steptenUploader=:steptenUploader,
                status=:status ";
            if ($_POST['Type'] == "active") {
                $sql = $sql . ",completedStep =:completedStep ";
            }
            $sql = $sql . " WHERE residenceID =:residenceID";
            $stmt = $pdo->prepare($sql);
            // bind parameters to statement
            $completedStep = 10;
            $status = 2;
            $stmt->bindParam(':EmiratesIDNumber', $_POST['emiratesIDNumber']);
            $stmt->bindParam(':steptenUploader', $_SESSION['user_id']);
            $stmt->bindParam(':status', $status);
            if ($_POST['Type'] == "active") {
                $stmt->bindParam(':completedStep', $completedStep);
            }
            $stmt->bindParam(':residenceID', $_POST['ID']);
            // execute the prepared statement
            $stmt->execute();
            $pdo->commit();
            echo "Succcess";
        } else {
            echo "Residence ID is wrong";
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['InsertCustomer'])) {
    try {
        // Begin transaction
        $pdo->beginTransaction();
        
        // Set defaults
        $defaultPass = 'abc';
        $supplier = NULL;
        $status = 1; // Active by default
        
        // Insert customer with all required fields from manageCustomerController.php
        $sql = "INSERT INTO `customer`(`customer_name`, `customer_ref`, `customer_phone`, `customer_whatsapp`, 
                `customer_address`, `customer_email`, `cust_password`, `status`, `affliate_supp_id`) 
                VALUES (:customer_name, :customer_ref, :customer_phone, :customer_whatsapp, 
                :customer_address, :customer_email, :cust_password, :status, :affliate_supp_id)";
        
        // Create prepared statement
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':customer_name', $_POST['CustomerName']);
        $stmt->bindParam(':customer_ref', $_POST['CustomerRef']);
        $stmt->bindParam(':customer_phone', $_POST['CustomerPhone']);
        $stmt->bindParam(':customer_whatsapp', $_POST['CustomerPhone']); // Use phone as whatsapp if not specified
        $stmt->bindParam(':customer_address', $_POST['CustomerAddress']);
        $stmt->bindParam(':customer_email', $_POST['CustomerEmail']);
        $stmt->bindParam(':cust_password', $defaultPass);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':affliate_supp_id', $supplier);
        
        // Execute the statement
        $stmt->execute();
        
        // Get the inserted ID
        $customer_id = $pdo->lastInsertId();
        
        // Commit transaction
        $pdo->commit();
        
        // Return success
        echo "Success";
    } catch (PDOException $e) {
        // Roll back the transaction if something failed
        $pdo->rollback();
        echo "ERROR: " . $e->getMessage();
    }
}

function uploadFile($name)
{
    $new_image_name = '';
    if ($_FILES[$name]['size'] <= 20971520) { // 20MB limit instead of 2MB
        $file_name = $_FILES[$name]['name'];
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $valid_extensions = array('jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip');
        if (in_array(strtolower($extension), $valid_extensions)) {
            $new_image_name = rand() . '.' . $extension;
            $path = "residence/" . $new_image_name;
            
            // Ensure residence directory exists
            if (!file_exists("residence")) {
                mkdir("residence", 0777, true);
            }
            
            // Use copy as fallback if move_uploaded_file fails
            if (!move_uploaded_file($_FILES[$name]['tmp_name'], $path)) {
                error_log("Failed to move uploaded file: " . $_FILES[$name]['name'] . " to " . $path);
                
                // Try direct copy as fallback
                if (copy($_FILES[$name]['tmp_name'], $path)) {
                    error_log("Successfully copied file as fallback");
                } else {
                    error_log("Failed to copy file as fallback");
                    $new_image_name = '';
                }
            }
            
            // Verify file was uploaded
            if (!file_exists($path) || filesize($path) === 0) {
                error_log("File upload failed or file is empty: " . $path);
                $new_image_name = '';
            }
            
            return $new_image_name;
        } else {
            error_log("Invalid file extension: " . $extension);
            $new_image_name = '';
        }
    } else {
        error_log("File too large: " . $_FILES[$name]['size'] . " bytes");
        $new_image_name = '';
    }
    return $new_image_name;
}
function checkValidResideneceID($rid)
{
    include 'connection.php';
    $selectQuery = $pdo->prepare("SELECT IFNULL((SELECT CASE WHEN residenceID THEN 1 ELSE 0 END AS residenceID FROM 
        `residence` WHERE residenceID = :ResID LIMIT 1),0) AS residenceID");
    $selectQuery->bindParam(':ResID', $rid);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $validitycheck = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    return $validitycheck[0]["residenceID"];
}
// Close connection
unset($pdo);
