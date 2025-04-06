</head>
<body>
<?php  
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		  }
		  if (!isset($_SESSION['user_id'])) {
			header('location:../../../login.php');
		  }
         require_once  '../../../api/connection/index.php';
         $sql = "SELECT permission.select,permission.insert FROM `permission` 
         WHERE role_id = :role_id";
         $stmt = $conn->prepare($sql);
         $stmt->bindParam(':role_id', $_SESSION['role_id']);
         $stmt->execute();
         $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    ?>

  <!-- BEGIN #app -->
  
	<div id="app" class="app app-header-fixed app-sidebar-fixed app-gradient-enabled app-content-full-height">
		<!-- BEGIN #header -->
		<div id="header" class="app-header app-header-inverse">
			<!-- BEGIN navbar-header -->
			<div class="navbar-header">
				<a href="index.php" class="navbar-brand"><span class="navbar-logo"></span> <b>Selab</b> Nadiry</a>
				<button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<!-- END navbar-header -->
			<!-- BEGIN header-nav -->
			<div class="navbar-nav">
				<div class="navbar-item navbar-form">
					<form action="" method="POST" name="search">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Enter keyword" />
							<button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
						</div>
					</form>
				</div>
				<div class="navbar-item dropdown">
					<a href="#" data-bs-toggle="dropdown" class="navbar-link dropdown-toggle icon">
						<i class="fa fa-bell"></i>
						<span class="badge">5</span>
					</a>
					<div class="dropdown-menu media-list dropdown-menu-end">
						<div class="dropdown-header">NOTIFICATIONS (5)</div>
						<a href="javascript:;" class="dropdown-item media">
							<div class="media-left">
								<i class="fa fa-bug media-object bg-gray-500"></i>
							</div>
							<div class="media-body">
								<h6 class="media-heading">Server Error Reports <i class="fa fa-exclamation-circle text-danger"></i></h6>
								<div class="text-muted fs-10px">3 minutes ago</div>
							</div>
						</a>
						<a href="javascript:;" class="dropdown-item media">
							<div class="media-left">
								<img src="#" class="media-object" alt="" />
								<i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
							</div>
							<div class="media-body">
								<h6 class="media-heading">John Smith</h6>
								<p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
								<div class="text-muted fs-10px">25 minutes ago</div>
							</div>
						</a>
						<a href="javascript:;" class="dropdown-item media">
							<div class="media-left">
								<img src="#" class="media-object" alt="" />
								<i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
							</div>
							<div class="media-body">
								<h6 class="media-heading">Olivia</h6>
								<p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
								<div class="text-muted fs-10px">35 minutes ago</div>
							</div>
						</a>
						<a href="javascript:;" class="dropdown-item media">
							<div class="media-left">
								<i class="fa fa-plus media-object bg-gray-500"></i>
							</div>
							<div class="media-body">
								<h6 class="media-heading"> New User Registered</h6>
								<div class="text-muted fs-10px">1 hour ago</div>
							</div>
						</a>
						<a href="javascript:;" class="dropdown-item media">
							<div class="media-left">
								<i class="fa fa-envelope media-object bg-gray-500"></i>
								<i class="fab fa-google text-warning media-object-icon fs-14px"></i>
							</div>
							<div class="media-body">
								<h6 class="media-heading"> New Email From John</h6>
								<div class="text-muted fs-10px">2 hour ago</div>
							</div>
						</a>
						<div class="dropdown-footer text-center">
							<a href="javascript:;" class="text-decoration-none">View more</a>
						</div>
					</div>
				</div>
				
				<div class="navbar-item navbar-user dropdown">
					<a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
						<img src="../../../<?php echo $staff_pic;?>" alt="" /> 
						<span>
							<span class="d-none d-md-inline"><?php echo $row20; ?></span>
							<b class="caret"></b>
						</span>
					</a>
					<div class="dropdown-menu dropdown-menu-end me-1">
						<a href="javascript:;" class="dropdown-item">Profile</a>
						<a href="datediff.php" class="dropdown-item"><i class="fa fa-calculator" aria-hidden="true"></i> Days Calculator</a>
						<a href="changepassword.php" class="dropdown-item">Change Password</a>
						<div class="dropdown-divider"></div>
						<a href="../../../logout.php" class="dropdown-item">Log Out</a>
					</div>
				</div>
			</div>
			<!-- END header-nav -->
		</div>
		<!-- END #header -->
	
		<!-- BEGIN #sidebar -->
		<div id="sidebar" class="app-sidebar">
			<!-- BEGIN scrollbar -->
			<div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
				<!-- BEGIN menu -->
				<div class="menu">
					<div class="menu-profile">
						<a href="javascript:;" class="menu-profile-link" data-toggle="app-sidebar-profile" data-target="#appSidebarProfileMenu">
							<div class="menu-profile-cover with-shadow"></div>
							<div class="menu-profile-image">
								<img src="../../../<?php echo $staff_pic;?>" alt="" />
							</div>
							<div class="menu-profile-info">
								<div class="d-flex align-items-center">
									<div class="flex-grow-1">
                  					   <?php echo $row20; ?>
									</div>
									<div class="menu-caret ms-auto"></div>
								</div>
								<small>CEO</small>
							</div>
						</a>
					</div>
					<div id="appSidebarProfileMenu" class="collapse">
						<div class="menu-item pt-5px">
							<a href="javascript:;" class="menu-link">
								<div class="menu-icon"><i class="fa fa-cog"></i></div>
								<div class="menu-text">Settings</div>
							</a>
						</div>
						<div class="menu-item">
							<a href="javascript:;" class="menu-link">
								<div class="menu-icon"><i class="fa fa-pencil-alt"></i></div>
								<div class="menu-text"> Send Feedback</div>
							</a>
						</div>
						<div class="menu-item pb-5px">
							<a href="javascript:;" class="menu-link">
								<div class="menu-icon"><i class="fa fa-question-circle"></i></div>
								<div class="menu-text"> Helps</div>
							</a>
						</div>
						<div class="menu-divider m-0"></div>
					</div>
					<div class="menu-header">Navigation</div>
          <?php if($records[0]['select'] == 1) { ?>
          <div class="menu-item active">
						<a href="../../../index.php" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-th-large"></i>
							</div>
							<div class="menu-text">Dashboard </div>
						</a>
					</div>
				  <?php } ?>
					
					<?php if($records[1]['select'] == 1 || $records[1]['insert'] == 1) { ?>
					<div class="menu-item has-sub">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-plane"></i>
							</div>
							<div class="menu-text">Ticket </div> 
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
              <?php if($records[1]['insert'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../Ticket.php" class="menu-link">
									<div class="menu-text">New Ticket <i class="fa fa-paper-plane text-theme"></i></div>
								</a>
							</div>
              <?php }  ?>
              <?php if($records[1]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../view ticket.php" class="menu-link">
									<div class="menu-text">Ticket Report</div>
								</a>
							</div>
              <?php }  ?>
						</div>
					</div>
          <?php } ?>
          <?php if($records[2]['select'] == 1 || $records[2]['insert'] == 1 || $records[17]['select'] == 1 || $records[18]['select'] == 1 || $records[26]['select'] ==1  ) { ?>
					<div class="menu-item has-sub">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-ticket"></i>
							</div>
							<div class="menu-text">Visa </div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
            <?php if($records[2]['insert'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../visa.php" class="menu-link">
									<div class="menu-text">New Visa </div>
								</a>
							</div>
              <?php } ?>
			  <?php if($records[26]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../residenceReport.php" class="menu-link">
									<div class="menu-text">Residence </div>
								</a>
							</div>
              <?php } ?>
			  <?php if($records[26]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../residenceRpt.php" class="menu-link">
									<div class="menu-text">Residence Report </div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[2]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../view visa.php" class="menu-link">
									<div class="menu-text">Visa Report </div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[2]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../pendingvisa.php" class="menu-link">
									<div class="menu-text">Pending Visa</div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[17]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../visaPrices.php" class="menu-link">
									<div class="menu-text">Supplier Visa Prices</div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[18]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../customerVisaPrices.php" class="menu-link">
									<div class="menu-text">Customer Visa Prices </div>
								</a>
							</div>
              <?php } ?>
						</div>
					</div>
          <?php } ?>
		  <?php if($records[23]['select'] == 1 || $records[23]['insert'] == 1) { ?>
			<div class="menu-item">
						<a href="../../../pendingTasks.php" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-spinner"></i>
							</div>
							<div class="menu-text">Pending Tasks </div>
							<span class="menu-label d-none "  style="font-weight:bold; font-size:10px"  id="pendingTaskNumber">10</span>
						</a>
		    </div>
		 <?php } ?>
          <?php if($records[3]['select'] == 1 || $records[3]['insert'] == 1) { ?>
					<div class="menu-item has-sub">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-simplybuilt"></i>
							</div>
							<div class="menu-text">Loan</div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
              <?php if($records[3]['insert'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../loan.php" class="menu-link">
									<div class="menu-text">New Loan</div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[3]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../viewloan.php" class="menu-link">
									<div class="menu-text">View Loan</div>
								</a>
							</div>
              <?php } ?>
							
						</div>
					</div>
          <?php } ?>
					<?php if($records[4]['select'] == 1 || $records[4]['insert'] == 1) { ?>
					<div class="menu-item has-sub">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-hotel"></i>
							</div>
							<div class="menu-text">Hotel</div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
              <?php if($records[4]['insert'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../hotel.php" class="menu-link">
									<div class="menu-text">New Booking</div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[4]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../viewhotel.php" class="menu-link">
									<div class="menu-text">View Bookings</div>
								</a>
							</div>
              <?php } ?>
							
						</div>
					</div>
          <?php } ?>
					<?php if($records[5]['select'] == 1 || $records[5]['insert'] == 1) { ?>
					<div class="menu-item has-sub">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-car"></i>
							</div>
							<div class="menu-text">Rental Car</div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
              <?php if($records[5]['insert'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../car_rental.php" class="menu-link">
									<div class="menu-text">New Booking</div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[5]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../viewcar_rental.php" class="menu-link">
									<div class="menu-text">View Bookings</div>
								</a>
							</div>
              <?php } ?>
							
						</div>
					</div>
          <?php } ?>
		  <?php if($records[27]['select'] == 1) { ?>
			<div class="menu-item">
						<a href="../../../service.php" class="menu-link">
							<div class="menu-icon">
								<i class="fab fa-servicestack"></i>
							</div>
							<div class="menu-text">Service </div>
							
						</a>
		    </div>
		 <?php } ?>
		  <!-- <?php if($records[6]['select'] == 1 || $records[6]['insert'] == 1) { ?>
					<div class="menu-item has-sub">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-exchange"></i>
							</div>
							<div class="menu-text">Hawala</div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
              <?php if($records[6]['insert'] == 1) { ?>
							<div class="menu-item">
								<a href="hawala.php" class="menu-link">
									<div class="menu-text">New Hawala</div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[6]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="viewhawala.php" class="menu-link">
									<div class="menu-text">View Hawala</div>
								</a>
							</div>
              <?php } ?>
							
						</div>
					</div>
          <?php } ?> -->
			
          <?php if($records[7]['select'] == 1 || $records[7]['insert'] == 1 || $records[8]['select'] == 1) { ?>
					<div class="menu-item has-sub">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-user"></i>
							</div>
							<div class="menu-text">Supplier</div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
            <?php if($records[7]['insert'] == 1) { ?>
            <div class="menu-item">
								<a href="../../../supplier.php" class="menu-link">
									<div class="menu-text">New Supplier</div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[7]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../viewsupplier.php" class="menu-link">
									<div class="menu-text">View Suppliers</div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[8]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../pending_supplier.php" class="menu-link">
									<div class="menu-text">Ledger</div>
								</a>
							</div>
              <?php } ?>
							
						</div>
					</div>
          <?php } ?>
          <div class="menu-item has-sub">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-dollar"></i>
							</div>
							<div class="menu-text">Expenses</div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
            <div class="menu-item">
								<a href="../../../expenseType.php" class="menu-link">
									<div class="menu-text">Expense Type</div>
								</a>
							</div>
              <?php if($records[9]['insert'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../expenses.php" class="menu-link">
									<div class="menu-text">New Expense</div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[9]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../viewexpense.php" class="menu-link">
									<div class="menu-text">Expense Report</div>
								</a>
							</div>
              <?php } ?>
							
						</div>
					</div>
          <?php if($records[11]['insert'] == 1 || $records[10]['insert'] == 1 || $records[11]['select'] == 1) { ?>
					<div class="menu-item has-sub">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-dollar"></i>
							</div>
							<div class="menu-text">Make Payment</div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
            <?php if($records[11]['select'] == 1 || $records[11]['insert'] == 1) { ?>
            <div class="menu-item">
								<a href="../../../payments.php" class="menu-link">
									<div class="menu-text">Supplier</div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[10]['insert'] == 1 || $records[10]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../customer/payment/customer_payment.php" class="menu-link">
									<div class="menu-text">Customer</div>
								</a>
							</div>
              <?php } ?>
						</div>
					</div>
          <?php } ?>
          <?php if($records[12]['insert'] == 1 || $records[12]['select'] == 1 || $records[13]['select'] == 1) { ?>
					<div class="menu-item has-sub">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-user"></i>
							</div>
							<div class="menu-text">Customers</div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
            <?php if($records[12]['insert'] == 1 || $records[12]['select'] == 1) { ?>
            <div class="menu-item">
								<a href="../../../manageCustomer.php" class="menu-link">
									<div class="menu-text">Customer Report</div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[13]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../pending_payments.php" class="menu-link">
									<div class="menu-text">Ledger</div>
								</a>
							</div>
              <?php } ?>
						</div>
					</div>
          <?php } ?>
          <?php if($records[13]['select'] == 1 || $records[8]['select'] == 1) { ?>
					<div class="menu-item has-sub">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-dollar"></i>
							</div>
							<div class="menu-text">Ledgers</div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
            <?php if($records[13]['select'] == 1) { ?>
            <div class="menu-item">
								<a href="../../../pending_payments.php" class="menu-link">
									<div class="menu-text">Customer</div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[8]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../pending_supplier.php" class="menu-link">
									<div class="menu-text">Supplier</div>
								</a>
							</div>
              <?php } ?>
			  <?php if($records[13]['select'] == 1) { ?>
            <div class="menu-item">
								<a href="../../../affliateBussiness.php" class="menu-link">
									<div class="menu-text">Affilate Bussiness</div>
								</a>
							</div>
              <?php } ?>
						</div>
					</div>
          <?php } ?>
          <?php if($records[14]['select'] == 1 || $records[15]['select'] == 1 || $records[16]['select'] == 1) { ?>
					<div class="menu-item has-sub">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-user"></i>
							</div>
							<div class="menu-text">Manage User</div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
            <?php if($records[14]['select'] == 1) { ?>
              <div class="menu-item">
								<a href="../../../staff.php" class="menu-link">
									<div class="menu-text">Employee</div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[15]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../role.php" class="menu-link">
									<div class="menu-text">Role</div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[16]['select'] == 1) { ?>
              <div class="menu-item">
								<a href="../../../permission.php" class="menu-link">
									<div class="menu-text">Permission</div>
								</a>
							</div>
              <?php } ?>
						</div>
					</div>
          <?php } ?>
		  <?php if($records[19]['select'] == 1 || $records[21]['select'] == 1 ) { ?>
					<div class="menu-item has-sub">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-user"></i>
							</div>
							<div class="menu-text">Manage Accounts</div>
							<div class="menu-caret"></div>
						</a>
						<div class="menu-submenu">
            <?php if($records[19]['select'] == 1) { ?>
              <div class="menu-item">
								<a href="../../../accounts.php" class="menu-link">
									<div class="menu-text">Accounts</div>
								</a>
							</div>
              <?php } ?>
              <?php if($records[21]['select'] == 1) { ?>
							<div class="menu-item">
								<a href="../../../deposit.php" class="menu-link">
									<div class="menu-text">Deposits</div>
								</a>
							</div>
              <?php } ?>
			  <?php if($records[19]['select'] == 1) { ?>
              <div class="menu-item">
								<a href="../../../accountsReport.php" class="menu-link">
									<div class="menu-text">Accounts Report</div>
								</a>
							</div>
              <?php } ?>
						</div>
					</div>
          <?php } ?>
		  <?php if($records[20]['select'] == 1) { ?>
            <div class="menu-item">
						<a href="../../../salary.php" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-dollar"></i>
							</div>
							<div class="menu-text">Manage Salaries </div>
						</a>
					</div>
          <?php } ?>
          <div class="menu-item has-sub">
            <a href="javascript:;" class="menu-link">
                <div class="menu-icon">
                    <i class="fa fa-hdd"></i>
                </div>
                <div class="menu-text">Email</div>
            </a>
            <div class="menu-submenu">
                <div class="menu-item">
                    <a href="../../../emailInbox.php" class="menu-link">
                        <div class="menu-text">Inbox</div>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="../../../compose.php" class="menu-link">
                        <div class="menu-text">Compose</div>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="../../../sentEmail.php" class="menu-link">
                        <div class="menu-text">Sent</div>
                    </a>
                </div>
            </div>
            </div>
			<?php if($records[24]['select'] == 1 || $records[24]['insert'] == 1) { ?>
			<div class="menu-item">
						<a href="../../../company_documents.php" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-files-o"></i>
							</div>
							<div class="menu-text">Company Documents </div>
						</a>
		    </div>
			<?php } ?>
			<?php if($records[22]['select'] == 1) { ?>
			<div class="menu-item">
						<a href="../../../ReminderForm.php" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-clock"></i>
							</div>
							<div class="menu-text">Set Reminder </div>
						</a>
		    </div>
			<?php } ?>
			<?php if($records[25]['select'] == 1 || $records[25]['insert'] == 1) { ?>
			<div class="menu-item">
						<a href="../../../currency.php" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-money"></i>
							</div>
							<div class="menu-text">Currency </div>
						</a>
		    </div>
			<?php } ?>
					<!-- BEGIN minify-button -->
					<div class="menu-item d-flex">
						<a href="javascript:;" class="app-sidebar-minify-btn ms-auto" data-toggle="app-sidebar-minify"><i class="fa fa-angle-double-left"></i></a>
					</div>
					<!-- END minify-button -->
				</div>
				<!-- END menu -->
			</div>
			<!-- END scrollbar -->
		</div>
		
		<div class="app-sidebar-bg"></div>
		<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
		<!-- END #sidebar -->
    <!-- BEGIN #content -->
		<div id="content" class="app-content">