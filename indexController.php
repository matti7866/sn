<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
}
include 'connection.php';
$sql = "SELECT role_name FROM `roles`  WHERE role_id = :role_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$role_name = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$role_name = $role_name[0]['role_name'];

if (isset($_POST['Select_todaysInfo'])) {
    if ($role_name == 'Admin') {
        $selectQuery = $pdo->prepare("SELECT COUNT(ticket.ticket) AS Todays_Ticket, (SELECT IFNULL(SUM(ticket.Sale),0)
            - IFNULL(SUM(ticket.net_price),0) FROM ticket WHERE DATE(ticket.datetime) = CURRENT_DATE) AS ticket_profit,
            (SELECT COUNT(visa_id) FROM visa WHERE DATE(visa.datetime)  = CURRENT_DATE) AS Todays_Visa,(SELECT 
            IFNULL(SUM(sale),0) - IFNULL(SUM(net_price),0) FROM visa WHERE DATE(visa.datetime) = CURRENT_DATE) AS 
            Visa_Profit,(SELECT IFNULL(SUM(expense_amount),0) FROM expense WHERE DATE(expense.time_creation) = 
            CURRENT_DATE) AS Total_Expense FROM ticket WHERE DATE(ticket.datetime) = CURRENT_DATE");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($rpt);
    }
}

// else if(isset($_POST['Select_RangeTikVisaInfo'])){
//     $selectQuery = $pdo->prepare("SELECT IFNULL(SUM(ticket.sale),0) -  IFNULL(SUM(ticket.net_price),0) AS TicketProfit, 
//     (SELECT IFNULL(SUM(visa.sale),0) -  IFNULL(SUM(visa.net_price),0) FROM visa WHERE DATE(visa.datetime) BETWEEN 
//     :from_date AND :to_date) AS VisaProfit  FROM ticket WHERE DATE(ticket.datetime) BETWEEN :from_date AND :to_date  
//     ");
//     $selectQuery->bindParam(':from_date', $_POST['Fromdate']);
//     $selectQuery->bindParam(':to_date', $_POST['Todate']);
//     $selectQuery->execute();
//     /* Fetch all of the remaining rows in the result set */
//     $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
//     // encoding array to json format
//     echo json_encode($rpt);
// }else if(isset($_POST['GetLineForTikVisa'])){
//     $selectQuery = $pdo->prepare("SELECT SUM(Total) AS total FROM(SELECT date(ticket.datetime) as date, 
//     IFNULL(SUM(ticket.sale),0) -  IFNULL(SUM(ticket.net_price),0) AS Total FROM ticket WHERE DATE(ticket.datetime) 
//     BETWEEN :from_date AND :to_date GROUP BY date UNION ALL SELECT date(visa.datetime) as date,IFNULL(SUM(visa.sale),0)
//     - IFNULL(SUM(visa.net_price),0) AS Total FROM visa WHERE DATE(visa.datetime) BETWEEN :from_date AND :to_date GROUP 
//     By date) AS baseTable GROUP BY date ");
//     $selectQuery->bindParam(':from_date', $_POST['Fromdate']);
//     $selectQuery->bindParam(':to_date', $_POST['Todate']);
//     $selectQuery->execute();
//     /* Fetch all of the remaining rows in the result set */
//     $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
//     // encoding array to json format
//     echo json_encode($rpt);
// }else if(isset($_POST['GetTotalExpenses'])){
//     $selectQuery = $pdo->prepare("SELECT DATE(time_creation) AS date ,IFNULL(SUM(expense.expense_amount),0) AS Total
//     FROM expense WHERE DATE(expense.time_creation) BETWEEN :from_date AND :to_date GROUP BY date");
//     $selectQuery->bindParam(':from_date', $_POST['Fromdate']);
//     $selectQuery->bindParam(':to_date', $_POST['Todate']);
//     $selectQuery->execute();
//     /* Fetch all of the remaining rows in the result set */
//     $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
//     // encoding array to json format
//     echo json_encode($rpt);
// }else if(isset($_POST['GetTopThreeProd'])){
//     $selectQuery = $pdo->prepare("SELECT expense_type.expense_type, IFNULL(SUM(expense.expense_amount),0) AS 
//     productLvlTotal FROM expense INNER JOIN expense_type ON expense.expense_type_id = expense_type.expense_type_id 
//     WHERE DATE(expense.time_creation) BETWEEN :from_date AND :to_date GROUP BY expense_type.expense_type ORDER BY 
//     productLvlTotal DESC LIMIT 3");
//     $selectQuery->bindParam(':from_date', $_POST['Fromdate']);
//     $selectQuery->bindParam(':to_date', $_POST['Todate']);
//     $selectQuery->execute();
//     /* Fetch all of the remaining rows in the result set */
//     $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
//     // encoding array to json format
//     echo json_encode($rpt);
// }
else if (isset($_POST['GetTodaysFlight'])) {
    $selectQuery = $pdo->prepare("
        SELECT Pnr,IFNULL(ticketNumber,'') AS ticketNumber,customer_name, passenger_name, DATE_FORMAT(ticket.date_of_travel,\"%M, %d %Y\") as date_of_travel, fromAir.airport_code AS from_place, toAir.airport_code AS to_place ,IFNULL(ticket.remarks,'') as remarks 
        FROM ticket 
        INNER JOIN customer ON customer.customer_id = ticket.customer_id 
        INNER JOIN airports AS fromAir ON fromAir.airport_id = ticket.from_id
        INNER JOIN airports AS toAir ON toAir.airport_id = ticket.to_id 
        WHERE (ticket.date_of_travel BETWEEN CURRENT_DATE() AND CURRENT_DATE()+5)
        UNION ALL 
        SELECT Pnr,IFNULL(ticketNumber,'') AS ticketNumber, DATE_FORMAT(ticket.date_of_travel,\"%M, %d %Y\") as date_of_travel, customer_name, passenger_name,fromAir.airport_code AS to_place, toAir.airport_code AS to_place, IFNULL(datechange.remarks,'') as remarks 
        FROM ticket 
        INNER JOIN datechange ON ticket.ticket = datechange.ticket_id 
        INNER JOIN customer ON customer.customer_id = ticket.customer_id 
        INNER JOIN airports AS fromAir ON fromAir.airport_id = ticket.from_id 
        INNER JOIN airports AS toAir ON toAir.airport_id = ticket.to_id 
        WHERE ( datechange.extended_Date BETWEEN CURRENT_DATE() AND datechange.extended_Date + 5)");
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($rpt);
} else if (isset($_POST['GetTommrowsFlight'])) {
    $selectQuery = $pdo->prepare("SELECT Pnr,IFNULL(ticketNumber,'') AS ticketNumber,customer_name, passenger_name,
        fromAir.airport_code AS from_place, toAir.airport_code AS to_place ,IFNULL(ticket.remarks,'') as remarks FROM ticket INNER JOIN customer ON 
        customer.customer_id = ticket.customer_id INNER JOIN airports AS fromAir ON fromAir.airport_id = ticket.from_id
        INNER JOIN airports AS toAir ON toAir.airport_id = ticket.to_id WHERE ticket.date_of_travel = CURRENT_DATE() +1
        UNION ALL SELECT Pnr,IFNULL(ticketNumber,'') AS ticketNumber, customer_name, passenger_name,fromAir.airport_code 
        AS to_place, toAir.airport_code AS to_place,IFNULL(datechange.remarks ,'')as remarks FROM ticket INNER JOIN datechange ON ticket.ticket = 
        datechange.ticket_id INNER JOIN customer ON customer.customer_id = ticket.customer_id INNER JOIN airports AS 
        fromAir ON fromAir.airport_id = ticket.from_id INNER JOIN airports AS toAir ON toAir.airport_id = ticket.to_id 
        WHERE datechange.extended_Date = CURRENT_DATE()+1 ");
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($rpt);
} else if (isset($_POST['GetDailyEntryReport'])) {
    $selectQuery = $pdo->prepare("SELECT 'Ticket Entry' AS EntryType, customer_name, passenger_name,
        CONCAT(CONCAT(CONCAT(CONCAT('Travel From: ', airports.airport_code),' To '),to_airport.airport_code),' Airport') AS 
        Details, datetime, staff_name FROM ticket INNER JOIN customer ON customer.customer_id = ticket.customer_id INNER JOIN 
        airports ON airports.airport_id = ticket.from_id INNER JOIN airports AS to_airport ON to_airport.airport_id = ticket.to_id
        INNER JOIN staff ON staff.staff_id = ticket.staff_id WHERE DATE(datetime) BETWEEN :from_date AND :to_date
        UNION ALL
        SELECT 'Visa Entry' AS EntryType, customer_name, passenger_name, country_names AS Details, datetime, staff_name FROM visa 
        INNER JOIN country_name ON country_name.country_id = visa.country_id INNER JOIN customer ON customer.customer_id = 
        visa.customer_id INNER JOIN staff ON staff.staff_id = visa.staff_id WHERE DATE(datetime) BETWEEN :from_date AND :to_date
        UNION ALL
        SELECT 'Visa Fine Entry' AS EntryType, customer_name, passenger_name, country_names AS Details, visaextracharges.datetime,
        staff_name FROM visaextracharges INNER JOIN visa ON visa.visa_id = visaextracharges.visa_id INNER JOIN country_name ON 
        country_name.country_id = visa.country_id INNER JOIN staff ON staff.staff_id = visaextracharges.uploadedBy INNER JOIN 
        customer ON customer.customer_id = visa.customer_id WHERE DATE(visaextracharges.datetime) BETWEEN :from_date AND 
        :to_date AND visaextracharges.typeID = 1
        UNION ALL
        SELECT 'Escape Report Entry' AS EntryType, customer_name, passenger_name, country_names AS Details, 
        visaextracharges.datetime, staff_name FROM visaextracharges INNER JOIN visa ON visa.visa_id = visaextracharges.visa_id 
        INNER JOIN country_name ON country_name.country_id = visa.country_id INNER JOIN staff ON staff.staff_id = 
        visaextracharges.uploadedBy INNER JOIN customer ON customer.customer_id = visa.customer_id WHERE 
        DATE(visaextracharges.datetime) BETWEEN :from_date AND :to_date AND visaextracharges.typeID = 2
        UNION ALL
        SELECT 'Escape Removal Entry' AS EntryType, customer_name, passenger_name, country_names AS Details, 
        visaextracharges.datetime, staff_name FROM visaextracharges INNER JOIN visa ON visa.visa_id = visaextracharges.visa_id
        INNER JOIN country_name ON country_name.country_id = visa.country_id INNER JOIN staff ON staff.staff_id = 
        visaextracharges.uploadedBy INNER JOIN customer ON customer.customer_id = visa.customer_id WHERE 
        DATE(visaextracharges.datetime) BETWEEN :from_date AND :to_date AND visaextracharges.typeID = 3
        UNION ALL 
        SELECT 'Date change Entry' AS EntryType, customer_name, passenger_name,CONCAT(CONCAT(CONCAT(CONCAT('Travel From: ', 
        airports.airport_code),' To '),to_airport.airport_code),' Airport') AS Details, datechange.datetime, staff_name FROM 
        datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id INNER JOIN  customer ON customer.customer_id = 
        ticket.customer_id INNER JOIN airports ON airports.airport_id = ticket.from_id INNER JOIN airports AS to_airport ON 
        to_airport.airport_id = ticket.to_id INNER JOIN staff ON staff.staff_id = ticket.staff_id WHERE DATE(datechange.datetime)
        BETWEEN :from_date AND :to_date AND datechange.ticketStatus = 1 
        UNION ALL 
        SELECT 'Ticket Fine Entry' AS EntryType, customer_name, passenger_name,CONCAT(CONCAT(CONCAT(CONCAT('Travel From: ',
        airports.airport_code),' To '),to_airport.airport_code),' Airport') AS Details, datechange.datetime, staff_name FROM 
        datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id INNER JOIN  customer ON customer.customer_id = 
        ticket.customer_id INNER JOIN airports ON airports.airport_id = ticket.from_id INNER JOIN airports AS to_airport ON 
        to_airport.airport_id = ticket.to_id INNER JOIN staff ON staff.staff_id = ticket.staff_id WHERE DATE(datechange.datetime)
        BETWEEN :from_date AND :to_date AND datechange.ticketStatus = 2
        UNION ALL 
        SELECT 'Residence Entry Basic Information Section' AS EntryType, customer_name, passenger_name, country_names AS Details, residence.datetime,
        staff_name FROM residence INNER JOIN customer ON customer.customer_id = residence.customer_id INNER JOIN country_name ON
        country_name.country_id = residence.VisaType INNER JOIN staff ON staff.staff_id = residence.StepOneUploader WHERE 
        DATE(residence.datetime) BETWEEN :from_date AND :to_date AND residence.StepOneUploader IS NOT NULL
        UNION ALL 
        SELECT 'Residence Entry Offer Letter Typing Section' AS EntryType, customer_name, passenger_name, country_names AS Details, 
        residence.datetime, staff_name FROM residence INNER JOIN customer ON customer.customer_id = residence.customer_id 
        INNER JOIN country_name ON country_name.country_id = residence.VisaType INNER JOIN staff ON staff.staff_id = 
        residence.stepTwoUploder WHERE DATE(residence.datetime) BETWEEN :from_date AND :to_date AND residence.stepTwoUploder
        IS NOT NULL
        UNION ALL 
        SELECT 'Residence Entry Insurance Section' AS EntryType, customer_name, passenger_name, country_names AS Details,residence.datetime,
        staff_name FROM residence INNER JOIN customer ON customer.customer_id = residence.customer_id INNER JOIN country_name ON
        country_name.country_id = residence.VisaType INNER JOIN staff ON staff.staff_id = residence.stepThreeUploader WHERE 
        DATE(residence.datetime) BETWEEN :from_date AND :to_date AND residence.stepThreeUploader IS NOT NULL
        UNION ALL 
        SELECT 'Residence Entry Labour Card Typing Section' AS EntryType, customer_name, passenger_name, country_names AS Details,residence.datetime,
        staff_name FROM residence INNER JOIN customer ON customer.customer_id = residence.customer_id INNER JOIN country_name ON 
        country_name.country_id = residence.VisaType INNER JOIN staff ON staff.staff_id = residence.stepfourUploader WHERE 
        DATE(residence.datetime) BETWEEN :from_date AND :to_date AND residence.stepfourUploader IS NOT NULL
        UNION ALL 
        SELECT 'Residence Entry E-Visa Typing Section' AS EntryType, customer_name, passenger_name, country_names AS Details,residence.datetime,
        staff_name FROM residence INNER JOIN customer ON customer.customer_id = residence.customer_id INNER JOIN country_name ON
        country_name.country_id = residence.VisaType INNER JOIN staff ON staff.staff_id = residence.stepfiveUploader WHERE 
        DATE(residence.datetime) BETWEEN :from_date AND :to_date AND residence.stepfiveUploader IS NOT NULL
        UNION ALL 
        SELECT 'Residence Entry Change Status Section' AS EntryType, customer_name, passenger_name, country_names AS Details, residence.datetime,
        staff_name FROM residence INNER JOIN customer ON customer.customer_id = residence.customer_id INNER JOIN country_name ON 
        country_name.country_id = residence.VisaType INNER JOIN staff ON staff.staff_id = residence.stepsixUploader WHERE 
        DATE(residence.datetime) BETWEEN :from_date AND :to_date AND residence.stepsixUploader IS NOT NULL
        UNION ALL 
        SELECT 'Residence Entry Medical Typing Section' AS EntryType, customer_name, passenger_name, country_names AS Details,residence.datetime,
        staff_name FROM residence INNER JOIN customer ON customer.customer_id = residence.customer_id INNER JOIN country_name ON
        country_name.country_id = residence.VisaType INNER JOIN staff ON staff.staff_id = residence.stepsevenUpploader WHERE 
        DATE(residence.datetime) BETWEEN :from_date AND :to_date AND residence.stepsevenUpploader IS NOT NULL
        UNION ALL 
        SELECT 'Residence Entry Emirates ID Typing Section' AS EntryType, customer_name, passenger_name, country_names AS Details,residence.datetime,
        staff_name FROM residence INNER JOIN customer ON customer.customer_id = residence.customer_id INNER JOIN country_name ON
        country_name.country_id = residence.VisaType INNER JOIN staff ON staff.staff_id = residence.stepEightUploader WHERE 
        DATE(residence.datetime) BETWEEN :from_date AND :to_date AND residence.stepEightUploader IS NOT NULL
        UNION ALL 
        SELECT 'Residence Entry Visa Stamping Section' AS EntryType, customer_name, passenger_name, country_names AS Details, residence.datetime,
        staff_name FROM residence INNER JOIN customer ON customer.customer_id = residence.customer_id INNER JOIN country_name ON 
        country_name.country_id = residence.VisaType INNER JOIN staff ON staff.staff_id = residence.stepNineUpploader WHERE 
        DATE(residence.datetime) BETWEEN :from_date AND :to_date AND residence.stepNineUpploader IS NOT NULL
        UNION ALL 
        SELECT 'Residence Entry Contract Submission Section' AS EntryType, customer_name, passenger_name, country_names AS Details, residence.datetime,
        staff_name FROM residence INNER JOIN customer ON customer.customer_id = residence.customer_id INNER JOIN country_name ON 
        country_name.country_id = residence.VisaType INNER JOIN staff ON staff.staff_id = residence.stepNineUpploader WHERE 
        DATE(residence.datetime) BETWEEN :from_date AND :to_date AND residence.steptenUploader IS NOT NULL
        UNION ALL 
        SELECT serviceName AS EntryType, customer_name, passenger_name, service_details AS Details, service_date, staff_name 
        FROM servicedetails INNER JOIN service ON service.serviceID = servicedetails.serviceID INNER JOIN customer ON
        customer.customer_id = servicedetails.customer_id INNER JOIN staff ON staff.staff_id = servicedetails.uploadedBy WHERE 
        DATE(servicedetails.service_date) BETWEEN :from_date AND :to_date
        UNION ALL 
        SELECT 'Hotel Reservation Entry' AS EntryType, customer_name, '' AS passenger_name, CONCAT('Hotel Name: 
        ',hotel.hotel_name) AS Details,datetime, staff_name FROM hotel INNER JOIN customer ON customer.customer_id = 
        hotel.customer_id INNER JOIN staff ON staff.staff_id = hotel.staffID WHERE DATE(datetime)  BETWEEN :from_date AND 
        :to_date
        UNION ALL 
        SELECT 'Rental Car Reservation Entry' AS EntryType, customer_name, '' AS passenger_name, CONCAT('Car Description: 
        ',car_rental.car_description) AS Details,datetime, staff_name FROM car_rental INNER JOIN customer ON customer.customer_id
        = car_rental.customer_id INNER JOIN staff ON staff.staff_id = car_rental.staffID WHERE DATE(datetime)  BETWEEN :from_date
        AND :to_date
        UNION ALL 
        SELECT 'Loan Entry' AS EntryType, customer_name, '' AS passenger_name, CONCAT('Amount & Remarks: ',
        CONCAT(CONCAT(CONCAT(CONCAT(loan.amount,' '), currencyName)),', '),loan.remarks) AS Details,datetime, staff_name FROM 
        loan INNER JOIN customer ON customer.customer_id = loan.customer_id INNER JOIN staff ON staff.staff_id = loan.staffID 
        INNER JOIN currency ON currency.currencyID = loan.currencyID  WHERE DATE(datetime)  BETWEEN :from_date AND :to_date
        UNION ALL
        SELECT 'Customer Payment Entry' AS EntryType, customer_name, '' AS passenger_name, CONCAT('Amount & Remarks: 
        ',CONCAT(CONCAT(CONCAT(CONCAT(customer_payments.payment_amount,' '), currencyName)),', '),IFNULL(customer_payments.remarks
        ,'No Remarks')) AS Details, datetime, staff_name FROM customer_payments INNER JOIN customer ON customer.customer_id = 
        customer_payments.customer_id INNER JOIN currency ON currency.currencyID = customer_payments.currencyID INNER JOIN staff 
        ON staff.staff_id = customer_payments.staff_id WHERE DATE(datetime)  BETWEEN :from_date AND :to_date
        UNION ALL 
        SELECT 'Expense Entry' AS EntryType, expense_type AS customer_name, '' AS passenger_name, CONCAT('Amount & Remarks: 
        ',CONCAT(CONCAT(CONCAT(CONCAT(expense.expense_amount,' '), currencyName)),', '),IFNULL(expense.expense_remark,
        'No Remarks')) AS Details, time_creation AS datetime, staff_name FROM expense INNER JOIN expense_type ON 
        expense_type.expense_type_id = expense.expense_type_id INNER JOIN currency ON currency.currencyID = expense.CurrencyID 
        INNER JOIN staff ON staff.staff_id = expense.staff_id WHERE DATE(time_creation)  BETWEEN :from_date AND :to_date
        UNION ALL
        SELECT 'Supplier Payment Entry' AS EntryType, supplier.supp_name AS customer_name, '' AS passenger_name, 
        CONCAT('Amount & Remarks: ',CONCAT(CONCAT(CONCAT(CONCAT(payment.payment_amount,' '), currencyName)),', '),
        IFNULL(payment.payment_detail,'No Details')) AS Details,time_creation AS datetime, staff_name FROM payment INNER JOIN 
        supplier ON supplier.supp_id = payment.supp_id INNER JOIN currency ON currency.currencyID = payment.currencyID INNER JOIN
        staff ON staff.staff_id = payment.staff_id WHERE DATE(time_creation)  BETWEEN :from_date AND :to_date
        UNION ALL

        SELECT 
            'Withdrawal Entry' AS EntryType, 
            '' as customer_name,
            '' as passenger_name,
            CONCAT('Account: ',accounts.account_name,'<br />','Remarks: ',remarks,'<br />','Amount: ',withdrawal_amount,'<br />','Currency: ',currency.currencyName) as Details,
            datetime,
            staff.staff_name as staff_name
        FROM withdrawals
        INNER JOIN accounts ON accounts.account_ID = withdrawals.accountID
        INNER JOIN currency ON currency.currencyID = withdrawals.currencyID
        INNER JOIN staff ON staff.staff_id = withdrawals.withdrawalBy
        WHERE DATE(datetime) BETWEEN :from_date AND :to_date

        UNION ALL

        SELECT 
            'Deposit Entry' AS EntryType, 
            '' as customer_name,
            '' as passenger_name,
            CONCAT('Account: ',accounts.account_name,'<br />','Remarks: ',remarks,'<br />','Amount: ',deposit_amount,'<br />','Currency: ',currency.currencyName) as Details,
            datetime,
            staff.staff_name as staff_name
        FROM deposits
        INNER JOIN accounts ON accounts.account_ID = deposits.accountID
        INNER JOIN currency ON currency.currencyID = deposits.currencyID
        INNER JOIN staff ON staff.staff_id = deposits.depositBy
        WHERE DATE(datetime) BETWEEN :from_date AND :to_date
        ");
    $selectQuery->bindParam(':from_date', $_POST['FromDate']);
    $selectQuery->bindParam(':to_date', $_POST['ToDate']);
    $selectQuery->execute();



    /* Fetch all of the remaining rows in the result set */
    $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($rpt);
} else if (isset($_POST['GetEvents'])) {
    $selectQuery = $pdo->prepare("SELECT id,title, CASE WHEN description = '' OR description = NULL THEN 'No Description' 
        ELSE description END AS description,eventDate AS eventDate, DATE_FORMAT(eventDate,'%d-%b-%Y') AS FormattedeventDate, CASE 
        WHEN assignedTo = NULL OR assignedTo = 0 THEN 'Self-Assigned' ELSE (SELECT staff.staff_name FROM staff WHERE staff.staff_id
        = createdBy) END AS assignedBy   FROM `events` WHERE iscompleted = 0 AND (createdBy = :staff_id OR assignedTo = :staff_id)
        ORDER BY eventCreatedDateTime DESC");
    $selectQuery->bindParam(':staff_id', $_SESSION['user_id']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $events = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($events);
} else if (isset($_POST['Select_Employees'])) {
    $selectQuery = $pdo->prepare("SELECT staff_id, staff_name FROM `staff` ORDER BY staff_name ASC");
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $employees = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($employees);
} else if (isset($_POST['SavePlan'])) {
    try {
        $sql = "INSERT INTO `events`(`title`, `description`, `eventDate`, `assignedTo`, `createdBy`) VALUES 
                (:title,:description,:eventDate,:assignedTo,:createdBy)";
        $stmt = $pdo->prepare($sql);
        // bind parameters to statement
        $stmt->bindParam(':title', $_POST['Title']);
        $stmt->bindParam(':description', $_POST['Description']);
        $stmt->bindParam(':eventDate', $_POST['DT']);
        $stmt->bindParam(':assignedTo', $_POST['Employees']);
        $stmt->bindParam(':createdBy', $_SESSION['user_id']);
        // execute the prepared statement
        $stmt->execute();
        echo "Success";
    } catch (PDOException $e) {
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['EventCompleted'])) {
    try {
        $selectQuery = $pdo->prepare("UPDATE `events` SET iscompleted = 1 WHERE id = :id");
        $selectQuery->bindParam(':id', $_POST['ID']);
        $selectQuery->execute();
        echo "Success";
    } catch (PDOException $e) {
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
}
// else if(isset($_POST['GetNotification'])){
//     $selectQuery = $pdo->prepare("SELECT (SELECT COUNT(notification_id) FROM notification INNER JOIN 
//     notification_analysis ON notification.notification_id = notification_analysis.notificationID INNER JOIN staff
//     ON staff.staff_id = notification_analysis.employee_ID WHERE notification_analysis.employee_ID = :employeeID AND
//     seen = 0 ) AS TotalNotification, notification_id, notification_subject,notification_description,DATE(datetime) AS
//     timeSent, staff_pic,seen  FROM notification INNER JOIN notification_analysis ON notification.notification_id = 
//     notification_analysis.notificationID INNER JOIN staff ON staff.staff_id = notification_analysis.employee_ID 
//     WHERE notification_analysis.employee_ID = :employeeID ORDER BY notification.notification_id DESC LIMIT 5");
//     $selectQuery->bindParam(':employeeID', $_SESSION['user_id']);
//     $selectQuery->execute();
//     /* Fetch all of the remaining rows in the result set */
//     $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
//     // encoding array to json format
//     echo json_encode($rpt);
// }else if(isset($_POST['GetPendingTaskNumber'])){
//     $selectQuery = $pdo->prepare("SELECT COUNT(*) as totalPendingTasks FROM pending_tasks WHERE status = 1");
//     $selectQuery->execute();
//     /* Fetch all of the remaining rows in the result set */
//     $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
//     // encoding array to json format
//     echo json_encode($rpt);
// }
// Close connection
unset($pdo);
