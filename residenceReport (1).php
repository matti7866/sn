<?php
   include 'header.php';
   include "connection.php";
   $sql = "SELECT role_name FROM `roles`  WHERE role_id = :role_id";
	 $stmt = $pdo->prepare($sql);
	 $stmt->bindParam(':role_id', $_SESSION['role_id']);
	 $stmt->execute();
	 $role_name = $stmt->fetchAll(\PDO::FETCH_ASSOC);
	 $role_name = $role_name[0]['role_name'];
   // permission for pending tasks
   $sql = "SELECT permission.select FROM `permission` WHERE role_id = :role_id AND page_name = 'Residence' ";
   $stmt = $pdo->prepare($sql);
   $stmt->bindParam(':role_id', $_SESSION['role_id']);
   $stmt->execute();
   $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
   $PT_Select = $records[0]['select'];
?>
<link href='https://fonts.googleapis.com/css?family=Arizonia' rel='stylesheet'>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap" rel="stylesheet">
<link href="https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<style>
   .apexcharts-tooltip{
     color:black;
   }
   .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active{
    
     
      background: linear-gradient(to bottom,#ff7471 0,#ff423e 100%);
       color:white;
   }
   .nav-tabs .nav-link:focus, .nav-tabs .nav-link:hover{
    color:white;
   }
   .table th {
      background-color: #ff423e !important;
      color: white;
  }
   .FineTableArea{
    margin-right:10px;
    margin-top:15px;
   }
   .iconArea{
    cursor:pointer;
   }   
   .chartBox {
      width: auto;
      height:400px;
      padding: 20px;
      border-radius: 20px;
   }
   #searchDropdown{
    position:absolute;
    z-index:100;
    width:31.3%;
    display:none;
    height: 150px;
    overflow:auto;
   }
   #searchDropdown ul li:hover{
    cursor: pointer;
    background-color: #ADD8E6;
    font-weight: bold;
   }
</style>
<?php
  include 'nav.php';
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="panel text-white">
            <div class="panel-heading bg-inverse ">
                    <h4 class="panel-title"><i class="fa fa-info"></i> Residence <code> Report <i class="fa fa-arrow-down"></i></code></h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="residence.php" onClick="resetForm()" class="btn btn-xs btn-icon btn-success" ><i class="fa fa-redo"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-danger " data-toggle="panel-remove" data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-times"></i></a>
                    </div>
            </div>
            <div class="panel-body p-3 bg-gray-800">
                <div class="row mb-3">
                    <div class="col-12" >
                        <div class="card border-0 mb-3 bg-gray-800 text-white   " data-scrollbar="true" data-height="100%" style="height: 100%;" >
                            <div class="card-body" >
                                <div class="row">  
                                
                                  <div class="col-lg-4 offset-6" id="searchAreaDiv">
                                      
                                      <input type="text" style="width:100%" onclick="getSearchRpt()"  class="form-control pull-right" onkeyup="getSearchRpt()"  placeholder="Customer,Passenger,Company name, Company Number" id="search"/>
                                      <div class="row">
                                        <div class="col-12">
                                        <div class="card mb-4 border-0" id="searchDropdown">
                                        
                                      </div>
                                        </div>
                                      </div>
                                      
                                  </div>
                                  <div class="col-lg-2 d-none" id="currencyAreaForRpt">
                                    <select class="form-control" onchange="getAbstrictView()" style="width:100%" name="residenceCurrency" id="residenceCurrency" spry:default="select one"></select>
                                  </div>
                                  <div class="col-lg-2 mb-4">
                                      <a href="residence.php" class="pull-right mt-2" > Add New Residence </a>
                                  </div>
                                  <hr />
                                  <div class="col-lg-12">
                                      <ul class="nav nav-tabs " id="residenceTabs" >
                                        <li class="nav-item">
                                        <a href="#default-tab-1" data-bs-toggle="tab" class="nav-link active">Pending Residence</a>
                                        </li>
                                        <li class="nav-item">
                                        <a href="#default-tab-2" data-bs-toggle="tab" class="nav-link ">Pending Residence Payment</a>
                                        </li>
                                        <li class="nav-item">
                                        <a href="#default-tab-3" data-bs-toggle="tab" class="nav-link ">Completed Residence Process</a>
                                        </li>
                                        <li class="nav-item">
                                          <a href="#default-tab-4" data-bs-toggle="tab" class="nav-link ">Due Residence's Payment Tabular Report</a>
                                        </li>
                                        
                                    
                                       
                                       </ul>
                                  </div>
                                  
                                <div class="tab-content bg-gray-800 panel p-3 rounded-0 rounded-bottom">
                                    <div class="tab-pane  fade active show bg-gray-800" id="default-tab-1">
                                        <hr />
                                        <!-- Begging of Accordin -->
                                        <div class="accordion" id="accordion">
                                            <div class="accordion-item border-0">
                                              <div class="accordion-header" id="headingOne">
                                                <button class="accordion-button bg-gray-900 text-white px-3 py-10px pointer-cursor" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                                  <i class="fa fa-circle fa-fw text-blue me-2 fs-8px"></i> View Residence Outstaning
                                                </button>
                                              </div>
                                              <div id="collapseOne" class="accordion-collapse collapse " data-bs-parent="#accordion">
                                                <div class="accordion-body bg-gray-800 text-white">
                                                  <div class="chartMenu">
                                                  </div>
                                                  <div class="chartCard">
                                                    <div class="chartBox">
                                                      <canvas id="myChart"></canvas>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        <!-- End of Accordin -->
                                        <hr />
                                        <div id="DailyRpt"></div>
                                        
                                    </div>
                                    <div class="tab-pane fade  " id="default-tab-2">
                                      <hr />
                                      <div id="PendingPayment"></div>
                                      <div id="PendingPaymentPagination" class="d-flex justify-content-center m-3" ></div>
                                    </div>
                                    <div class="tab-pane fade  " id="default-tab-3">
                                      <hr />
                                      <div id="completedResidence"></div>
                                      <div id="completedResidencePagination" class="d-flex justify-content-center m-3" ></div>
                                    </div>
                                    <div class="tab-pane fade  " id="default-tab-4">
                                      <input type="hidden" id="customerID" />
                                      <input type="hidden" id="passengerName" />
                                      <hr />
                
                                      <div class="row align-items-center pb-1px" id="abstractViewArea">
                                        
                                      </div>
                                      <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <i class="fa fa-print" id="printButton" onclick="printLedger()" style="font-size:15px; cursor:pointer"></i>
                                        </div>
                                      </div>
                                      <!-- Beginning of report area -->
                                      <div id="printThisArea">
                                        <div class="card-body">
                                          <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 ">
                                                        <img src="logoselab-removebg-preview.png"  style="height:180px;width:180px;">
                                                    </div>
                                                    <div class="col-md-6" >
                                                    <h1 class="h1 text-danger float-start mt-5" id="logoname" style="font-family: Arizonia;font-size:50px; "><i><b>SN</b></i></h1>
                                                    </div>
                                                </div>
                                                <br/>
                                                <div class="row">
                                                    <div class="col-md-10" style="overflow-wrap: break-word;">
                                                        <p  style="font-family: 'Montserrat', sans-serif; font-size:20px; margin-top:3px;margin-bottom:0px"><b>Selab Nadiry Travel & Tourism</b></p>
                                                      
                                                        <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px">Address: Frij Murar Shop# 15, Deira, Dubai</p>
                                                        
                                                        <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px">Contact:+971 4 298 4564,+971 58 514 0764</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 offset-md-2 ">
                                                <h3 class="text-nowrap text-danger mt-5 h3" style="font-family: 'Montserrat', sans-serif; font-size:18px; margin-left:47px"><b>Customer Information</b></h3>
                                                <hr  style="border: none; width:230px; color:black; border-bottom:2px solid black;">
                                                <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px;margin-left:47px">Name: <span class="text-capitalize" id="name"></span></p>
                                                <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px;margin-left:47px">Email: <span id="email"></span></p>
                                                <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px;margin-left:47px">Phone: <span id="phone"></span></p>
                                                <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px;margin-left:47px">Currency: <span id="currencyInfo"></span></p>
                                                <h3 class="text-nowrap text-danger mt-5 h3" style="font-family: 'Montserrat', sans-serif; font-size:18px;margin-left:47px"><b>Date</b></h3>
                                                <hr  style="border: none; width:230px; color:black; border-bottom:2px solid black;">
                                                <h3 class="text-nowrap h3" style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-left:47px"><?php  echo date("d-M-Y") ?></h3>
                                                
                                            </div>
                                          </div>
                                          <br/>
                                          <div class="row">
                                            <div class="table-responsive ">
                                              <table id="myTable"  class="table  table-dark table-hover table-bordered ">
                                                <thead >
                                                  <tr id="ad" class="bg-danger text-white">
                                                      <th style="-webkit-print-color-adjust: exact;">S#</th>
                                                      <th style="-webkit-print-color-adjust: exact;">Transaction Type</th>
                                                      <th style="-webkit-print-color-adjust: exact;">Passenger Name</th>
                                                      <th style="-webkit-print-color-adjust: exact;" >Date</th>
                                                      <th style="-webkit-print-color-adjust: exact;">Visa Type</th>
                                                      <th style="-webkit-print-color-adjust: exact;">Debit</th>
                                                      <th style="-webkit-print-color-adjust: exact;">Credit</th>
                                                      <th style="-webkit-print-color-adjust: exact;">Running Balance</th>    
                                                  </tr>
                                                </thead>
                                                <tbody id="TicketReportTbl">
                                
                                          
                                                </tbody>
                                              </table>
                                            </div> 
                                          </div>
                                          <!--- Beginning Of Total !-->
                                          <div class="row">
                                              <div class="col-md-12 mt-5">
                                                          <div class="col-md-8 offset-4">
                                                              <p class="font-weight-bold text-right" style="font-size:20px">Total Charges: <span id="total"></span> </p>
                                                              <hr/>
                                                          </div>
                                                          <div class="col-md-8 offset-4">
                                                              <p class="font-weight-bold text-right" style="font-size:20px">Total Paid: <span id="total_paid"></span> </p>
                                                              <hr/>
                                                          </div>
                                                          <div class="col-md-8 offset-4">
                                                              <p class="font-weight-bold text-right" style="font-size:30px"><b>Outstanding Balance: <span id="outstanding_balance">0</span> </b></p>
                                                              <hr/>
                                                          </div>
                                              </div>
                                          </div>
                                          <!-- End of Totaal !-->
                                        </div>
                                          <!-- End of This plce to be printed !-->

                                      </div>
                                </div>
                                
                                
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
            </div>
        </div>
    </div>
  </div>
</div>
<!-- Payment Modal -->
<div class="modal fade" id="myModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-white bg-dark" >
        <h5 class="modal-title" id="exampleModalLongTitle">Residence Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form>
        <input type="hidden" id="resID">
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label"><i class="fa fa-fw fa-user"></i>Payable Amount</label>
            <div class="col-sm-8">
                <input type="text"  disabled="disabled" class="form-control" id="balance">
            </div>
        </div>
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label text-danger"><i class="fa fa-fw fa-info"></i>Alert</label>
            <div class="col-sm-8">
              <p class="text-danger">Payment Currency will be taken automatically from sale price currency. if the sale price currency is
                AED then payment currency should also be AED. In case customer pays in dollar then convert that money to AED and write 
                remarks<p>

            </div>
        </div>
      
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label"><i class="fa fa-fw fa-user"></i>Payment Amount</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" id="pay" placeholder="Type amount here">

            </div>
        </div>
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label"><i class="fa fa-fw fa-comment"></i>Remarks</label>
            <div class="col-sm-8">
               <textarea class="form-control" rows="5" id="remarks"></textarea>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Account:</label>
            <div class="col-sm-8">
            <select class="form-control  js-example-basic-single" style="width:100%" name="account_id" id="account_id"></select>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info" onclick="makePay()">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Extra Charges Modal -->
<div class="modal fade" id="fineModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white ">
        <h5 class="modal-title font-weight-bold" id="ExChargeHeader">Add Residence Fine</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
      <form id="genralUpdForm">
        <input type="hidden"  class="form-control" id="rID" name="rID">
          <div class="form-group row mb-2 fSection">
            <label for="inputPassword" class="col-sm-3 col-form-label">Fine Amount:</label>
            <div class="col-sm-9" >
              <input type="number" class="form-control" name="fine_amount" id="fine_amount" placeholder="Fine Amount">
            </div>
          </div>
          <div class="form-group row mb-2 chargeSction">
            <label for="inputPassword" class="col-sm-3 col-form-label">Account:</label>
            <div class="col-sm-9 accountArea">
              <select class="form-control  chargesSelect2" onchange="checkType()" style="width:100%" name="chargeAccount" id="chargeAccount"></select>
            </div>
            <div class="col-sm-3 currencyArea d-none">
                <select class="form-control chargesSelect2"   style="width:100%" id="fine_currency_type" name="fine_currency_type" spry:default="select one"></select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onclick="saveResidenceFine()" class="btn btn-danger">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- View Fine Charges Modal -->
<div class="modal fade" id="viewFineModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white ">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel">View Fine</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
          <form class="col-md-6 form-group" style="display:none"  method="post" enctype="multipart/form-data" id="chargeUpload" >
            <input type="file" name="Chargesuploader" id="Chargesuploader" />
            <input type="text" name="uploadChargesID" id="uploadChargesID" />
            <button type="submit" id="submitChargeUploadForm" >Call</button>
          </form>
            <div class="table-responsive ">
              <table   class="table  table-striped table-hover ">
                <thead class="bg-dark text-white">
                  <tr>
                    <th>S#</th>
                    <th >Type</th>
                    <th >Fine Amount</th>
                    <th >Account</th>
                    <th >Date</th>
                    <th >Charged By</th>
                    <th >Receipt</th>
                    <th class="text-center" >Action</th>
                  </tr>
                </thead>
                <tbody id="viewFineTable">
                   
                </tbody>
              </table>
            </div> 
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Extra Update Fine Modal -->
<div class="modal fade" id="updfineModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white ">
        <h5 class="modal-title font-weight-bold" id="ExChargeHeader">Update Residence Fine</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
      <form id="genralUpdForm">
        <input type="hidden"  class="form-control" id="updrID" name="updrID">
          <div class="form-group row mb-2 fSection">
            <label for="inputPassword" class="col-sm-3 col-form-label">Fine Amount:</label>
            <div class="col-sm-9" >
              <input type="number" class="form-control" name="updfine_amount" id="updfine_amount" placeholder="Fine Amount">
            </div>
          </div>
          <div class="form-group row mb-2 chargeSction">
            <label for="inputPassword" class="col-sm-3 col-form-label">Account:</label>
            <div class="col-sm-9 updaccountArea">
              <select class="form-control  chargesSelecte3" onchange="updcheckType()" style="width:100%" name="updchargeAccount" id="updchargeAccount"></select>
            </div>
            <div class="col-sm-3 updcurrencyArea d-none">
                <select class="form-control chargesSelecte3"   style="width:100%" id="updfine_currency_type" name="updfine_currency_type" spry:default="select one"></select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onclick="updResidenceFine()" class="btn btn-danger">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Payment For Fine Modal -->
<div class="modal fade" id="FinePaymentModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-white bg-dark" >
        <h5 class="modal-title" id="exampleModalLongTitle">Residence Fine Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form>
        <input type="hidden" id="resFPaymentID">
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label"><i class="fa fa-fw fa-user"></i>Payable Amount</label>
            <div class="col-sm-8">
                <input type="text"  disabled="disabled" class="form-control" id="fine_balance">
            </div>
        </div>
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label text-danger"><i class="fa fa-fw fa-info"></i>Alert</label>
            <div class="col-sm-8">
              <p class="text-danger">Payment Currency will be taken automatically from fine price currency. if the sale price currency is
                AED then payment currency should also be AED. In case customer pays in dollar then convert that money to AED and write 
                remarks<p>

            </div>
        </div>
      
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label"><i class="fa fa-fw fa-user"></i>Payment Amount</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" id="Fine_payAmount" placeholder="Type amount here">

            </div>
        </div>
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label"><i class="fa fa-fw fa-comment"></i>Remarks</label>
            <div class="col-sm-8">
               <textarea class="form-control" rows="5" id="fine_remarks"></textarea>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Account:</label>
            <div class="col-sm-8">
            <select class="form-control  js-example-basic-single" style="width:100%" name="fine_account_id" id="fine_account_id"></select>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info" onclick="saveFinePayment()">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>
    <?php
      include 'footer.php';
    ?>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
<script src="dashboardCustom.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js" integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="./views/components/date/dateFormat.js"></script>
<script src="./views/components/select2/search.js"></script>
<script>
 $(document).ready(function(){
   
    
    $('#fine_account_id').select2({
        dropdownParent: $("#FinePaymentModel")
    });
    $('.chargesSelect2').select2({
        dropdownParent: $("#fineModal")
    });
    $('.chargesSelecte3').select2({
        dropdownParent: $("#updfineModal")
    });
    $("#residenceTabs li").click(function(e) {
      if(e.target.childNodes[0].nodeValue == "Pending Residence"){
        hideCurrencyDropdownForSearch();
        getPendingResidence();
      }else if(e.target.childNodes[0].nodeValue == "Pending Residence Payment"){
        hideCurrencyDropdownForSearch();
        $('#PendingPaymentPagination').pagination({
          items: 1,
          itemsOnPage: 10,
          cssStyle: 'dark-theme'
        });
        getPendingPayForResidence();
      }else if(e.target.childNodes[0].nodeValue == "Completed Residence Process"){
        hideCurrencyDropdownForSearch();
        $('#completedResidencePagination').pagination({
          items: 1,
          itemsOnPage: 10,
          cssStyle: 'dark-theme'
        });
        getCompletedResidence();
      }else if(e.target.childNodes[0].nodeValue == "Due Residence's Payment Tabular Report" ){
        hideCurrencyDropdownForSearch();
        // initalize the customer
        $(document).on('input', '#search', function(){
          let inputValue  = $(this).val();
          if(inputValue.length > 2 && $("#residenceTabs li a.active").attr('href') == "#default-tab-4" ){
            
            // call ajax for giving user idea
            getSearchResult(inputValue);

          }
        })  
      
      } 
   });
   getPendingResidence();
 
   $("#account_id").select2({
      dropdownParent: $("#myModel")
    });

    showTotalFineView();
 });

// search 
 function getSearchRpt(){
    if($("#residenceTabs li a.active").attr('href') == "#default-tab-1"){
      getPendingResidence();
    }else if($("#residenceTabs li a.active").attr('href') == "#default-tab-2"){
      getPendingPayForResidence();
    }else if($("#residenceTabs li a.active").attr('href') == "#default-tab-3"){
      getCompletedResidence();
    }else if($("#residenceTabs li a.active").attr('href') == "#default-tab-4"){
      let searchbox = $('#search').val();
          if(searchbox.length > 2 && $("#residenceTabs li a.active").attr('href') == "#default-tab-4"){
            // call ajax for giving user idea
            getSearchResult(searchbox);
         }  
    }
 }
function getPendingResidence(){
  var getPendingResidence = "getPendingResidence";
  var search = $('#search').val();
    $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
          GetPendingResidence:getPendingResidence,
          Search:search
        },
        success: function (response) { 
          var pendingResidenceRpt = JSON.parse(response);
            var dailyrpt = $('#DailyRpt');
            if(pendingResidenceRpt.length == 0){
                dailyrpt.empty();
                dailyrpt.append('<div class="row"><h1 class="text-center">No Residence pending Report...</h1></div>');
            }else{
              dailyrpt.empty();
              var dailyrptTable = '';
              var j = 1;
              for(var i =0; i<pendingResidenceRpt.length; i++){
                dailyrptTable += '<div class="row"><div class="col-lg-6"><h2> <i>' + pendingResidenceRpt[i].passenger_name +  
                '</i></h2><p class="line-height-lg-point6">Customer Name: ' + pendingResidenceRpt[i].customer_name + ' '+
                ' Nationality: '+ pendingResidenceRpt[i].countryName + ' ' + 'Residence Type: ' + 
                pendingResidenceRpt[i].country_names + '</p><p class="line-height-lg-point6">Company Name: ' + 
                pendingResidenceRpt[i].company_name + ' '+ ' Company Number: '+ pendingResidenceRpt[i].company_number + '</p><p class="line-height-lg-point6">Sale Price: <span  class="text-info" '+
                'style="font-size:14px">' + numeral(pendingResidenceRpt[i].sale_price).format('0,0') + ' '  + 
                pendingResidenceRpt[i].currencyName +'</span> &nbsp;&nbsp; Total Paid: <span  class="text-info" '+
                'style="font-size:14px">'+ numeral(pendingResidenceRpt[i].total).format('0,0') + ' ' + 
                pendingResidenceRpt[i].currencyName +'</span>  &nbsp;&nbsp;';
                if(pendingResidenceRpt[i].total_Fine != 0){
                  dailyrptTable += 'Total Fine: <span  class="text-info" '+
                'style="font-size:14px">'+ numeral(pendingResidenceRpt[i].total_Fine).format('0,0') + ' ' + 
                pendingResidenceRpt[i].residenceFineCurrency +'</span> &nbsp;&nbsp; Fine Paid: <span  class="text-info" '+
                'style="font-size:14px">'+ numeral(pendingResidenceRpt[i].totalFinePaid).format('0,0') + ' ' + 
                pendingResidenceRpt[i].residenceFineCurrency +'</span>';
                }
                
                dailyrptTable +='</p><div class="d-flex"><p >Remaining: '+
                '<span class="text-red" style="font-size:14px">'+ numeral((pendingResidenceRpt[i].sale_price - 
                pendingResidenceRpt[i].total)).format('0,0') + ' ' + pendingResidenceRpt[i].currencyName + '</span>';
                if(pendingResidenceRpt[i].total_Fine != 0){
                  dailyrptTable += '&nbsp;&nbsp;Remaining Fine: '+
                  '<span class="text-red" style="font-size:14px">'+ numeral((pendingResidenceRpt[i].total_Fine - 
                  pendingResidenceRpt[i].totalFinePaid)).format('0,0') + ' ' + pendingResidenceRpt[i].residenceFineCurrency + '</span>';
                }
                dailyrptTable +='&nbsp;&nbsp;<button '+
                'class="btn btn-danger" type="button" onclick="getPendingPayment('+pendingResidenceRpt[i].main_residenceID+')"><i class="fa fa-cc-paypal"></i> Make Payment</button>&nbsp;'+
                '<div style="margin-top:-15px"><div class="btn-group"><button type="button" class="btn btn-danger   mt-3 dropdown-toggle"  data-bs-toggle="dropdown" aria-expanded="false">'+
                'More <i class="fa fa-caret-down" aria-hidden="true"></i></button><ul class="dropdown-menu"><li><button class="dropdown-item" type="button" onclick="openResidenceFineDialog('+pendingResidenceRpt[i].main_residenceID+')"><i class="fa fa-plus"></i> Add Fine</button></li><li><button class="dropdown-item" type="button" onclick="viewFine('+pendingResidenceRpt[i].main_residenceID+')"><i class="fa fa-eye"></i> View Fine</button></li><li><button class="dropdown-item" type="button" onclick="deleteResidence('+pendingResidenceRpt[i].main_residenceID+')"><i class="fa fa-trash"></i> Delete Residence</button></li></ul>'+
                '</div></div></p></div></div><div class="col-lg-6 d-flex  justify-content-xl-end  justify-content-center"><div style="position:relative"><div class="progress "  id="pendingCircle'+ j +'" '+
                'style="height:auto;width:100px;background-color:#2d353c;font-size:17px; ">'+
                '</div><div style="position:absolute;top:120px;left:10px"><a href="residence.php?id='
                +pendingResidenceRpt[i].main_residenceID + '&stp='+ pendingResidenceRpt[i].completedStep + '" '+
                'class="btn btn-info" type="button" >Continue'+
                '</a></div></div></div></div><hr class="reportLineBreaker" />';
                j++;
              }
              dailyrpt.append(dailyrptTable);
              var pendingCircleCounter = 1;
              for(var c = 0; c<pendingResidenceRpt.length;c++){
                $('#pendingCircle'+pendingCircleCounter).circleProgress({
	                max: 10,
	                value:pendingResidenceRpt[c].completedStep,
	                textFormat: 'percent',
                });
                pendingCircleCounter++;
              }
              setCircleAttributes('#ff423e');
              
             
            }
            
        },
    });
}
function setCircleAttributes(color){
    $('.circle-progress-value').css({'stroke-width': '9px','stroke': color,'stroke-linecap': 'round'});
    $('.circle-progress-circle').css({'stroke-width': '2px'});
    $('.circle-progress-text').css({'fill':'white'})
}
function getPendingPayment(id){
  event.preventDefault();
  $('#resID').val(id);
  $('#myModel').modal('show');
  getAccounts("forPendingPayment",null);
  var getPendingResidencePayment = 'getPendingResidencePayment';
    $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
            GetPendingResidencePayment:getPendingResidencePayment,
            ID:id
        },
        success: function (response) {  
           var resPendPayment = JSON.parse(response);
          $('#balance').val(numeral(resPendPayment[0].remaining).format('0,0') + ' ' + resPendPayment[0].currencyName);
        },
    });
}
function makePay(){
    var insert_payment ="INSERT_Payment";
    var payment = $('#pay');
    if(payment.val() == ""){
        notify('Validation Error!', 'payment is required', 'error');
        return;
    }
    var remarks= $('#remarks');
    var account_id = $('#account_id');
    if(account_id.val() == "-1"){
        notify('Validation Error!', 'account is required', 'error');
        return;
    }
    var resID = $('#resID');
    if(resID.val() == "-1"){
        notify('Validation Error!', 'Something went wrong', 'error');
        return;
    }
        $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: {
                Insert_Payment:insert_payment,
                Payment : payment.val(),
                Remarks: remarks.val(),
                Account_ID:account_id.val(),
                ResID:resID.val()
            },
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#myModel').modal('hide');
                    if($("#residenceTabs li a.active").attr('href') == "#default-tab-1"){
                      getPendingResidence();
                    }else if($("#residenceTabs li a.active").attr('href') == "#default-tab-2"){
                      getPendingPayForResidence();
                    }
                    payment.val('');
                    resID.val('');
                    $('#remarks').val('');
                    showTotalFineView();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
}
function getAccounts(type,id){
    var select_Accounts = "SELECT_Accounts";
    $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
          Select_Accounts:select_Accounts,
        },
        success: function (response) {  
            var account = JSON.parse(response);
            if(type =="forPendingPayment"){
              $('#account_id').empty();
              $('#account_id').append("<option value='-1'>--Account--</option>");              
              var selected = '';
               for(var i=0; i<account.length; i++){
                $('#account_id').append("<option "+ selected + "  value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
            }else if(type == "forFine"){
              $('#chargeAccount').empty();
              $('#chargeAccount').append("<option value='-1'>--Account--</option>");              
              var selected = '';
               for(var i=0; i<account.length; i++){
                $('#chargeAccount').append("<option "+ selected + "  value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
              checkType();
            }else if(type == "byUpdateWithoutCash"){
              $('#updchargeAccount').empty();
              $('#updchargeAccount').append("<option value='-1'>--Account--</option>");              
              var selected = '';
               for(var i=0; i<account.length; i++){
                if(id == account[i].account_ID){
                  selected = "selected";
                }else{
                  selected = "";
                }
                $('#updchargeAccount').append("<option "+ selected + "  value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
              updcheckType();
            }else if(type == "byUpdate"){
              $('#updchargeAccount').empty();
              $('#updchargeAccount').append("<option value='-1'>--Account--</option>");              
              var selected = '';
               for(var i=0; i<account.length; i++){
                if(id == account[i].account_ID){
                  selected = "selected";
                }else{
                  selected = "";
                }
                $('#updchargeAccount').append("<option "+ selected + "  value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
            }else if(type == "forFinePayment"){
              $('#fine_account_id').empty();
              $('#fine_account_id').append("<option value='-1'>--Account--</option>");              
              var selected = '';
               for(var i=0; i<account.length; i++){
                $('#fine_account_id').append("<option "+ selected + "  value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
            }
            
              
        },
    });
}
function getPendingPayForResidence(){
  var page = parseInt($('#PendingPaymentPagination').pagination('getCurrentPage'));
  var getPendingPayForResidence = "getPendingPayForResidence";
  var search = $('#search').val();
    $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
          GetPendingPayForResidence:getPendingPayForResidence,
          Search:search,
          Page:page
        },
        success: function (response) { 
          var pendingPaymentResidenceRpt = JSON.parse(response);
            var PendingPayment = $('#PendingPayment');
            if(pendingPaymentResidenceRpt.length == 0){
              PendingPayment.empty();
              PendingPayment.append('<div class="row"><h1 class="text-center">No Residence Pending Payment Report...</h1></div>');
              $('#PendingPaymentPagination').pagination('updateItems', 1);
            }else{
              PendingPayment.empty();
              var pendingPaymentTable = '';
              var j = 1;
              for(var i =0; i<pendingPaymentResidenceRpt.length; i++){
                pendingPaymentTable += '<div class="row"><div class="col-lg-6"><h2> <i>' + pendingPaymentResidenceRpt[i].passenger_name +  
                '</i></h2><p class="line-height-lg-point6">Customer Name: ' + pendingPaymentResidenceRpt[i].customer_name + ' '+
                ' Nationality: '+ pendingPaymentResidenceRpt[i].countryName + ' ' + 'Residence Type: ' + 
                pendingPaymentResidenceRpt[i].country_names + '</p><p class="line-height-lg-point6">Company Name: ' + 
                pendingPaymentResidenceRpt[i].company_name + ' '+ ' Company Number: '+ pendingPaymentResidenceRpt[i].company_number + '</p><p class="line-height-lg-point6">Sale Price: <span  class="text-info" '+
                'style="font-size:14px">' + numeral(pendingPaymentResidenceRpt[i].sale_price).format('0,0') + ' '  + 
                pendingPaymentResidenceRpt[i].currencyName +'</span> &nbsp;&nbsp; Total Paid: <span  class="text-info" '+
                'style="font-size:14px">'+ numeral(pendingPaymentResidenceRpt[i].total).format('0,0') + ' ' + 
                pendingPaymentResidenceRpt[i].currencyName +'</span> &nbsp;&nbsp;';
                if(pendingPaymentResidenceRpt[i].total_Fine != 0){
                  pendingPaymentTable += 'Total Fine: <span  class="text-info" '+
                'style="font-size:14px">'+ numeral(pendingPaymentResidenceRpt[i].total_Fine).format('0,0') + ' ' + 
                pendingPaymentResidenceRpt[i].residenceFineCurrency +'</span> &nbsp;&nbsp; Fine Paid: <span  class="text-info" '+
                'style="font-size:14px">'+ numeral(pendingPaymentResidenceRpt[i].totalFinePaid).format('0,0') + ' ' + 
                pendingPaymentResidenceRpt[i].residenceFineCurrency +'</span>';
                }
                pendingPaymentTable +='</p><div class="d-flex"><p>Remaining: '+
                '<span class="text-red" style="font-size:14px">'+ numeral((pendingPaymentResidenceRpt[i].sale_price - 
                pendingPaymentResidenceRpt[i].total)).format('0,0') + ' ' + pendingPaymentResidenceRpt[i].currencyName + '</span>';
                if(pendingPaymentResidenceRpt[i].total_Fine != 0){
                  pendingPaymentTable += '&nbsp;&nbsp;Remaining Fine: '+
                  '<span class="text-red" style="font-size:14px">'+ numeral((pendingPaymentResidenceRpt[i].total_Fine - 
                  pendingPaymentResidenceRpt[i].totalFinePaid)).format('0,0') + ' ' + pendingPaymentResidenceRpt[i].residenceFineCurrency + '</span>';
                }
                pendingPaymentTable += '&nbsp;&nbsp;<button '+
                'class="btn btn-danger" type="button" onclick="getPendingPayment('+pendingPaymentResidenceRpt[i].main_residenceID+')"><i class="fa fa-cc-paypal"></i> Make Payment</button>&nbsp;';
                pendingPaymentTable += '<div style="margin-top:-15px"><div class="btn-group"><button type="button" class="btn btn-danger   mt-3 dropdown-toggle"  data-bs-toggle="dropdown" aria-expanded="false">'+
                'More <i class="fa fa-caret-down" aria-hidden="true"></i></button><ul class="dropdown-menu"><li><button class="dropdown-item" type="button" onclick="openResidenceFineDialog('+pendingPaymentResidenceRpt[i].main_residenceID+')"><i class="fa fa-plus"></i> Add Fine</button></li><li><button class="dropdown-item" type="button" onclick="viewFine('+pendingPaymentResidenceRpt[i].main_residenceID+')"><i class="fa fa-eye"></i> View Fine</button></li><li><button class="dropdown-item" type="button" onclick="deleteResidence('+pendingPaymentResidenceRpt[i].main_residenceID+')"><i class="fa fa-trash"></i> Delete Residence</button></li></ul>'+
                '</div></div></p></div></div><div class="col-lg-6 d-flex  justify-content-xl-end  justify-content-center"><div style="position:relative"><div class="progress "  id="pendingPaymentCircle'+ j +'" '+
                'style="height:auto;width:100px;background-color:#2d353c;font-size:17px; ">'+
                '</div><div style="position:absolute;top:120px;left:15px"><a href="residence.php?id='
                +pendingPaymentResidenceRpt[i].main_residenceID + '&stp='+ pendingPaymentResidenceRpt[i].completedStep + '" '+
                'class="btn btn-info" type="button" ><i class="fa fa-eye"></i> View'+
                '</a></div></div></div></div><hr class="reportLineBreaker" />'
                j++;
              }
             
              PendingPayment.append(pendingPaymentTable);
              var pendingPaymentCircleCounter = 1;
              for(var c = 0; c<pendingPaymentResidenceRpt.length;c++){
                $('#pendingPaymentCircle'+pendingPaymentCircleCounter).circleProgress({
	                max: 10,
	                value:pendingPaymentResidenceRpt[c].completedStep,
	                textFormat: 'percent',
                });
                pendingPaymentCircleCounter++;
              }
              setCircleAttributes('#f59c1a');
              $('#PendingPaymentPagination').pagination('updateItems', pendingPaymentResidenceRpt[0].totalRow);
              
             
            }
            
        },
    });

}
 $('#PendingPaymentPagination').on('click', function(){
    getPendingPayForResidence();
 });
 $('#completedResidencePagination').on('click', function(){
  getCompletedResidence();
 });
 function getCompletedResidence(){
  var page = parseInt($('#completedResidencePagination').pagination('getCurrentPage'));
  var getCompletedResidence = "getCompletedResidence";
  var search = $('#search').val();
    $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
          GetCompletedResidence:getCompletedResidence,
          Search:search,
          Page:page
        },
        success: function (response) { 
          var compeletedResidenceRpt = JSON.parse(response);
            var completedResidence = $('#completedResidence');
            if(compeletedResidenceRpt.length == 0){
              completedResidence.empty();
              completedResidence.append('<div class="row"><h1 class="text-center">No Completed Residence Report...</h1></div>');
              $('#completedResidencePagination').pagination('updateItems', 1);
            }else{
              completedResidence.empty();
              var completeTable = '';
              var j = 1;
              for(var i =0; i<compeletedResidenceRpt.length; i++){
                completeTable += '<div class="row"><div class="col-lg-6"><h2> <i>' + compeletedResidenceRpt[i].passenger_name +  
                '</i></h2><p class="line-height-lg-point6">Customer Name: ' + compeletedResidenceRpt[i].customer_name + ' '+
                ' Nationality: '+ compeletedResidenceRpt[i].countryName + ' ' + 'Residence Type: ' + 
                compeletedResidenceRpt[i].country_names + '</p><p class="line-height-lg-point6">Company Name: ' + 
                compeletedResidenceRpt[i].company_name + ' '+ ' Company Number: '+ compeletedResidenceRpt[i].company_number + '</p><p class="line-height-lg-point6">Sale Price: <span  class="text-info" '+
                'style="font-size:14px">' + numeral(compeletedResidenceRpt[i].sale_price).format('0,0') + ' '  + 
                compeletedResidenceRpt[i].currencyName +'</span> &nbsp;&nbsp; Total Paid: <span  class="text-info" '+
                'style="font-size:14px">'+ numeral(compeletedResidenceRpt[i].total).format('0,0') + ' ' + 
                compeletedResidenceRpt[i].currencyName +'</span>&nbsp;&nbsp;';
                if(compeletedResidenceRpt[i].total_Fine != 0){
                  completeTable += 'Total Fine: <span  class="text-info" '+
                'style="font-size:14px">'+ numeral(compeletedResidenceRpt[i].total_Fine).format('0,0') + ' ' + 
                compeletedResidenceRpt[i].residenceFineCurrency +'</span> &nbsp;&nbsp; Fine Paid: <span  class="text-info" '+
                'style="font-size:14px">'+ numeral(compeletedResidenceRpt[i].totalFinePaid).format('0,0') + ' ' + 
                compeletedResidenceRpt[i].residenceFineCurrency +'</span>';
                }
                completeTable += '</p><p> <a href="residence.php?id='
                +compeletedResidenceRpt[i].main_residenceID + '&stp='+ compeletedResidenceRpt[i].completedStep + '" '+
                'class="btn btn-primary" type="button" ><i class="fa fa-eye"></i> View'+
                '</a></p></div><div class="col-lg-6 d-flex  justify-content-xl-end  justify-content-center "><div class="progress "  id="completedCircle'+ j +'" '+
                'style="height:auto;width:100px;background-color:#2d353c;font-size:17px; ">'+
                '</div></div></div><hr class="reportLineBreaker" />';
                j++;
              }
              completedResidence.append(completeTable);
              var completedCounter = 1;
              for(var c = 0; c<compeletedResidenceRpt.length;c++){
                $('#completedCircle'+completedCounter).circleProgress({
	                max: 10,
	                value:compeletedResidenceRpt[c].completedStep,
	                textFormat: 'percent',
                });
                completedCounter++;
              }
              setCircleAttributes('#348fe2');
              $('#completedResidencePagination').pagination('updateItems', compeletedResidenceRpt[0].totalRow);
              
             
            }
            
        },
    });

}
function deleteResidence(id){
  var DeleteResidence = "DeleteResidence";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this residence? Remember deleting residence will also delete the fine on residence!',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "residenceReportController.php",  
                data: {
                  DeleteResidence:DeleteResidence,
                  ID:id
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                    getSearchRpt();
                    showTotalFineView();
                }else{
                  notify('Opps!', response, 'error');
                }
              },
            });
            }
        },
        close: function () {
        }
    }
});
}

function openResidenceFineDialog(id){
  var showFineAmount = "showFineAmount";
  $('#rID').val(id);
  getAccounts('forFine',null);
  $('#fineModal').appendTo("body").modal('show');
}
function saveResidenceFine(){
  var saveResidenceFine = "saveResidenceFine";
  var rid = $('#rID').val();
  if(rid == '' || rid == 'undefined' || rid == 0){
        notify('Error!', 'Something went wrong! Please refresh the page', 'error');
        return;
  }
  var fine_amount = $('#fine_amount');
  if(fine_amount.val() < 1){
        notify('Validation Error!', 'Fine Amount should be greater than 0', 'error');
        return;
  }
  if(fine_amount.val() == ''){
        notify('Validation Error!', 'Fine Amount should not be empty', 'error');
        return;
  }
  var chargeAccount = $('#chargeAccount').select2('data');
  if(chargeAccount[0].id <= 1 || chargeAccount[0].id == ""  || chargeAccount[0].id == "undefined"){
            notify('Validation Error!', 'Please select acount', 'error');
            return;
  }
  var fine_currency_type = $('#fine_currency_type');
    $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
          SaveResidenceFine:saveResidenceFine,
          Fine_Amount:fine_amount.val(),
          ChargeAccount:chargeAccount[0].id,
          Fine_currency_type:fine_currency_type.val(),
          RID:rid
        },
        success: function (response) {  
          if(response == "Success"){
            notify('Success!', 'Record added successfully', 'success');
            $('#rID').val('');
            $('#fineModal').modal('hide');
            $('#fine_amount').val('');
            getSearchRpt();
            showTotalFineView();
          }else{
            notify('Error!', 'Something went wrong!', 'error');
          }
              
        },
    });
}

function getCurrencies(type, id){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "viewVisaController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            if(type  == "getAll"){
                $('#fine_currency_type').empty();
                var selected = "";
                for(var i=0; i<currencyType.length; i++){
                  if(i == 0){
                    selected = "selected";
                  }else{
                    selected = "";
                  }
                  $('#fine_currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
            }else if(type  == "updgetAll"){
                $('#updfine_currency_type').empty();
                var selected = "";
                for(var i=0; i<currencyType.length; i++){
                  if(i == 0){
                    selected = "selected";
                  }else{
                    selected = "";
                  }
                  $('#updfine_currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
            }else if(type  == "updgetSpecific"){
                $('#updfine_currency_type').empty();
                var selected = "";
                for(var i=0; i<currencyType.length; i++){
                  if(id == currencyType[i].currencyID){
                    selected = "selected";
                  }else{
                    selected = "";
                  }
                  $('#updfine_currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
                $('.updaccountArea').removeClass('col-sm-9');
                $('.updaccountArea').addClass('col-sm-6');
                $('.updcurrencyArea').removeClass('d-none');
            }
                
        },
    });
}
function checkType(){
 
  var chargeAccount = $('#chargeAccount').select2('data');
  if(chargeAccount[0].text == "Cash"){
    getCurrencies('getAll',null);
    $('.accountArea').removeClass('col-sm-9');
    $('.accountArea').addClass('col-sm-6');
    $('.currencyArea').removeClass('d-none');
  }else{
    $('.accountArea').removeClass('col-sm-6');
    $('.accountArea').addClass('col-sm-9');
    $('.currencyArea').addClass('d-none');
  }
}

function updcheckType(){
  var updchargeAccount = $('#updchargeAccount').select2('data');
  if(updchargeAccount[0].text == "Cash"){
    getCurrencies('updgetAll',null);
    $('.updaccountArea').removeClass('col-sm-9');
    $('.updaccountArea').addClass('col-sm-6');
    $('.updcurrencyArea').removeClass('d-none');
  }else{
    $('.updaccountArea').removeClass('col-sm-6');
    $('.updaccountArea').addClass('col-sm-9');
    $('.updcurrencyArea').addClass('d-none');
  }
}
    function viewFine(id){
      var viewFine = "viewFine";
      $('#viewFineModal').appendTo("body").modal('show');
      $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
            ViewFine:viewFine,
            ID:id
        },
        success: function (response) {
          var viewFineRpt = JSON.parse(response);
          if(viewFineRpt.length === 0){
            $('#viewFineTable').empty();
            var finalTable = "<tr><td></td><td></td><td></td><td>Record Not Found</td><td></td><td></td><td></td><td></td></tr>";
            $('#viewFineTable').append(finalTable);
          }else{
            $('#viewFineTable').empty();
            var j = 1;
            var finalTable = "";
            for(var i=0; i<viewFineRpt.length; i++){
              finalTable = "<tr><th scope='row' >"+ j + "</th>";
              finalTable+="<td style='width:10px'><button type='button' class='btn btn-warning'>Fine</button></td>";
              finalTable+="<td >"+ numeral(viewFineRpt[i].fineAmount).format('0,0') + ' ' + 
              viewFineRpt[i].currencyName +"</td><td>"+ viewFineRpt[i].account_Name +"</td>";
              finalTable+= "<td >"+ viewFineRpt[i].residenceFineDate +"</td><td >"+ viewFineRpt[i].staff_name + "</td>";
              if(viewFineRpt[i].docName == null || viewFineRpt[i].docName == '' ){
                finalTable += "<td><button type='button' onclick='uploadExraFile("+ viewFineRpt[i].residenceFineID +")' class='btn'><i class='fa fa-upload text-danger fa-2x' aria-hidden='true'></i>"+
                "</button></td>";
              }else{
                finalTable += "<td style='width:110px'><a href='downloadFineDocs.php?id=" + viewFineRpt[i].residenceFineID +"&type=2'><button type='button' class='btn'><i class='fa fa-download text-dark fa-2x' aria-hidden='true'></i>"+
                "</button></a><button type='button' onclick='deleteFile(" + viewFineRpt[i].residenceFineID +")' class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i>"+
                "</button></td>";
              }
              finalTable += "<td style='width:180px;'>";
              finalTable += "<button type='button'0 onclick='openExtraCModal(" + viewFineRpt[i].residenceFineID +")'" +
              "class='btn'><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>";
              finalTable +="<button type='button'0 onclick='DeleteFine(" + viewFineRpt[i].residenceFineID +")'" +
              "class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i></button><button type='button'0 "+
              "onclick='payFine(" + viewFineRpt[i].residenceFineID +")'" +
              "class='btn'><i class='fa fa-paypal text-success fa-2x' aria-hidden='true'></i></button>";
              finalTable +="</td>";
              finalTable += "</tr>";
              $('#viewFineTable').append(finalTable);
              j +=1;
            }
            // finalTable += "<div id='totalFineArea'><tr><h1>2222222222</h1></tr></div>";
             
            getFineTotal(viewFineRpt[0].residenceID);
          }
          
            
        },
      });

    }
    function uploadExraFile(id){
      $('#uploadChargesID').val(id);
      $('#Chargesuploader').click();
    }
    document.getElementById("Chargesuploader").onchange = function(event) {
      $('#submitChargeUploadForm').click();
    };
    $(document).on('submit', '#chargeUpload', function(event){
      event.preventDefault();
      var uploadChargesID = $('#uploadChargesID');
      var Chargesuploader = $('#Chargesuploader').val();
      if(uploadChargesID.val() == ""){
        notify('Validation Error!', 'Cant upload somethig went wrong! try again.', 'error');
        return;
      }
      if($('#Chargesuploader').val() != ''){
        if($('#Chargesuploader')[0].files[0].size > 2097152){
          notify('Error!', 'File size is greater than 2 MB. Make Sure It should be less than 2 MB ', 'error');
          return;
        }
      }
      data = new FormData(this);
      data.append('Upload_ExraChargeDoc','Upload_ExraChargeDoc');
        $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    uploadChargesID.val('');
                    $('#Chargesuploader').val('');
                    $('#viewFineModal').modal('hide');
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    function deleteFile(id){
      var DeleteFile = "DeleteFile";
        $.confirm({
        title: 'Delete!',
        content: 'Do you want to delete this file',
        type: 'red',
        typeAnimated: true,
        buttons: {
            tryAgain: {
                text: 'Yes',
                btnClass: 'btn-red',
                action: function(){
                  $.ajax({
                    type: "POST",
                    url: "residenceReportController.php",  
                    data: {
                      DeleteFile:DeleteFile,
                      ID:id,
                    },
                    success: function (response) {  
                    if(response == 'Success'){
                      notify('Success!', response, 'success');
                      $('#viewFineModal').modal('hide');
                      getSearchRpt();
                      showTotalFineView();
                    }else{
                      notify('Opps!', response, 'error');
                    }
                  },
                });
                }
            },
            close: function () {
            }
        }
        });
    }
    function openExtraCModal(id){
      $('#viewFineModal').modal('hide');
      $('#updrID').val(id);
      getDataForUpdate = "getDataForUpdate";
      $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
            GetDataForUpdate:getDataForUpdate,
            ID:id
        },
        success: function (response) {
          var updviewFineRpt = JSON.parse(response);
          $('#updfine_amount').val(updviewFineRpt[0].fineAmount);
          if(updviewFineRpt[0].account_Name != "Cash"){
              getAccounts('byUpdateWithoutCash', updviewFineRpt[0].account_ID);
          }else{
            getAccounts('byUpdate', updviewFineRpt[0].account_ID);
            getCurrencies('updgetSpecific', updviewFineRpt[0].fineCurrencyID);
          }
        }
      });
      $('#updfineModal').appendTo("body").modal('show');
    }
    function updResidenceFine(){
      var UpdSaveResidenceFine = "UpdSaveResidenceFine";
      var updrID = $('#updrID').val();
      if(updrID =="" || updrID == 0 || updrID== "undefined"){
        notify('Error!', "Something went wrong! refresh the page", 'error');
        return;
      }
      var updfine_amount = $('#updfine_amount');
      if(updfine_amount.val() < 1){
            notify('Validation Error!', 'Fine Amount should be greater than 0', 'error');
            return;
      }
      if(updfine_amount.val() == ''){
            notify('Validation Error!', 'Fine Amount should not be empty', 'error');
            return;
      }
      var updchargeAccount = $('#updchargeAccount').select2('data');
      if(updchargeAccount[0].id <= 1 || updchargeAccount[0].id == ""  || updchargeAccount[0].id == "undefined"){
            notify('Validation Error!', 'Please select acount', 'error');
            return;
      }
      var updfine_currency_type = $('#updfine_currency_type');
      $.ajax({
          type: "POST",
          url: "residenceReportController.php",  
          data: {
            UpdSaveResidenceFine:UpdSaveResidenceFine,
            Updfine_Amount:updfine_amount.val(),
            UpdchargeAccount:updchargeAccount[0].id,
            Updfine_Currency_type:updfine_currency_type.val(),
            UpdrID:updrID
          },
          success: function (response) {  
            if(response == "Success"){
              notify('Success!', 'Record updated successfully', 'success');
              $('#updrID').val('');
              $('#updfineModal').modal('hide');
              $('#updfine_amount').val('');
              getSearchRpt();
              showTotalFineView();
            }else{
              notify('Error!', 'Something went wrong!', 'error');
            }
                
          },
      });
    }
    function DeleteFine(id){
      var DeleteFine = "DeleteFine";
        $.confirm({
        title: 'Delete!',
        content: 'Do you want to delete this Fine',
        type: 'red',
        typeAnimated: true,
        buttons: {
            tryAgain: {
                text: 'Yes',
                btnClass: 'btn-red',
                action: function(){
                  $.ajax({
                    type: "POST",
                    url: "residenceReportController.php",  
                    data: {
                      DeleteFine:DeleteFine,
                      ID:id,
                    },
                    success: function (response) {  
                    if(response == 'Success'){
                      notify('Success!', response, 'success');
                      $('#viewFineModal').modal('hide');
                      getSearchRpt();
                      showTotalFineView();
                    }else{
                      notify('Opps!', response, 'error');
                    }
                  },
                });
                }
            },
            close: function () {
            }
        }
        });
    }
    function payFine(id){
      $('#viewFineModal').modal('hide');
      var resFPaymentID = $('#resFPaymentID').val(id);
      if(resFPaymentID < 1 || resFPaymentID =="" || resFPaymentID == "undefined"){
        notify('Error!', "Something went wrong! Please refresh the page.", 'error');
      }
      getTotalFine = "getTotalFine";
      $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
            GetTotalFine:getTotalFine,
            ID:id
        },
        success: function (response) {
          var totalFineRpt = JSON.parse(response);
          $('#fine_balance').val(numeral(totalFineRpt[0].fineAmount).format('0,0') + ' ' + totalFineRpt[0].currencyName);
          getAccounts('forFinePayment',null);
        }
      });
      $('#FinePaymentModel').appendTo("body").modal('show');
    }
    function saveFinePayment(){
    var INSERT_FINE_PAYMENT ="INSERT_FINE_PAYMENT";
    var Fine_payAmount = $('#Fine_payAmount');
    if(Fine_payAmount.val() == "" || Fine_payAmount.val() < 0){
        notify('Validation Error!', 'payment amount is required', 'error');
        return;
    }
    var fine_remarks= $('#fine_remarks');
    var fine_account_id = $('#fine_account_id');
    if(fine_account_id.val() == "-1"){
        notify('Validation Error!', 'account is required', 'error');
        return;
    }
    var resFPaymentID = $('#resFPaymentID');
    if(resFPaymentID.val() < 0 || resFPaymentID.val() == "" || resFPaymentID.val() == "undefined"){
        notify('Validation Error!', 'Something went wrong!Please refresh the page', 'error');
        return;
    }
        $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: {
              INSERT_FINE_PAYMENT:INSERT_FINE_PAYMENT,
              Fine_payAmount : Fine_payAmount.val(),
              Fine_remarks: fine_remarks.val(),
              Fine_account_id:fine_account_id.val(),
              ResFPaymentID:resFPaymentID.val()
            },
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#FinePaymentModel').modal('hide');
                    if($("#residenceTabs li a.active").attr('href') == "#default-tab-1"){
                      getPendingResidence();
                    }else if($("#residenceTabs li a.active").attr('href') == "#default-tab-2"){
                      getPendingPayForResidence();
                    }
                    Fine_payAmount.val('');
                    resFPaymentID.val('');
                    $('#fine_remarks').val('');
                    getSearchRpt();
                    showTotalFineView();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    }
    function getFineTotal(id){
      var getFineTotal = "getFineTotal";
      $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: {
              GetFineTotal:getFineTotal,
              ID:id
            },
            success: function (response) {
              var viewRF = JSON.parse(response);
              var table = "";
              table += '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td style="font-size:14px; font-weight:bold">Outstaning Fine Balance:</td>';
              for(var i = 0; i<viewRF.length; i++){
                if(i == 0){
                  table += '<td style="font-size:14px; font-weight:bold">'+  numeral(viewRF[i].RF).format('0,0') + ' ' + viewRF[i].currencyName +'</td></tr>';
                }else{
                  table +='<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td style="font-size:14px; font-weight:bold">'+ numeral(viewRF[i].RF).format('0,0') + ' ' + viewRF[i].currencyName +'</td></tr>';
                }
                
              }
              $('#viewFineTable').append(table);
            },
        });

    }
    function showTotalFineView(){
      var labelArr = [];
      var dataArr = [];
      var getTotalResidencePendingP = "getTotalResidencePendingP";
      $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: {
              GetTotalResidencePendingP:getTotalResidencePendingP,
            },
            success: function (response) {
              var getTFPRpt = JSON.parse(response);
              for(var i=0;i<getTFPRpt.length;i++){
                labelArr.push(getTFPRpt[i].currencyName);
                dataArr.push(parseInt(getTFPRpt[i].TotalBalance));
              }
              drawPendingResidencePayment(labelArr,dataArr);
            },
        });
      
      
    }
    function drawPendingResidencePayment(labelInp,dataInp){
      const data = {
          labels: labelInp ,
          datasets: [{
            label: 'Total Residence Balance',
            data: dataInp,
            backgroundColor: [
              'rgba(255, 26, 104, 0.4)',
              'rgba(54, 162, 235, 0.4)',
              'rgba(255, 206, 86, 0.4)',
              'rgba(75, 192, 192, 0.4)',
              'rgba(153, 102, 255, 0.4)',
              'rgba(255, 159, 64, 0.4)',
              'rgba(0, 0, 0, 0.4)',
            ],
            borderColor: [
              'rgba(255, 26, 104, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(0, 0, 0, 1)'
            ],
            borderWidth: 1
          }]
      };
        // config 
        const config = {
          type: 'bar',
          data,
          options: {
            plugins: {
              legend: {
                labels: {
                  color: 'white'
                }
              },
              datalabels: {
                color: 'white',
                formatter: function(value) {
                  return value.toLocaleString();
                }
              }
            },
            scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    color: 'white'
                  }
                },
                x: {
                  ticks: {
                    color: 'white'
                  }
                }
              },
              
            },
            plugins: [ChartDataLabels],
        };
        let chartStatus = Chart.getChart("myChart"); // <canvas> id
        if (chartStatus != undefined) {
          chartStatus.destroy();
        }
          // render init block
          const myChart = new Chart(
            document.getElementById('myChart'),
            config
          );
    }
    // this function gives idea for the user
    function getSearchResult(searchTerm){
      var getSearchResult = "getSearchResult";
      $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: {
              GetSearchResult:getSearchResult,
              SearchTerm:searchTerm
            },
            success: function (response) {
              let searchResult = JSON.parse(response);
              let searchDropdown = $('#searchDropdown');
              $('#searchDropdown').css('height', '150px');
              let finalResult = '';
              searchDropdown.empty();
              searchDropdown.append('<ul class="list-group list-group-flush">');
              $('#searchDropdown').show();
              if(searchResult.length == 0){
                $('#searchDropdown').css('height', '42px');
                finalResult+= '<li class="list-group-item text-capitalize text-center">No Result Found...</li>';
                finalResult += '</ul>';
                searchDropdown.append(finalResult);
                return;
              }
              for(let i = 0; i<searchResult.length; i++){
                if(searchResult[i].identifier == 1){
                  finalResult += '<li class="list-group-item text-capitalize" onclick="searchForDuePayments('+ 
                  searchResult[i].customer_id +',\'C\',\'null\')">'+ searchResult[i].customer_name  +'</li>';  
                }else{
                  finalResult += '<li class="list-group-item text-capitalize" onclick="searchForDuePayments('+ 
                  searchResult[i].customer_id +',\'P\',\''+ searchResult[i].passenger_name + '\')">'+ searchResult[i].customer_name  +' <span class="badge rounded-pill '+
                  ' bg-primary text-capitalize">'+
                  searchResult[i].passenger_name +'</span></li>';  
                }
              }
              finalResult += '</ul>';
              searchDropdown.append(finalResult);
              document.getElementById('searchDropdown').addEventListener('mouseover', function(event){
                if(event.target.tagName === 'LI'){
                   event.target.style.cursor = 'pointer';
                   event.target.style.backgroundColor = '#ADD8E6';
                   event.target.style.fontWeight = 'bold';
                }
              });
              document.getElementById('searchDropdown').addEventListener('mouseout', function(event){
                if(event.target.tagName === 'LI'){
                   event.target.style.cursor = 'default';
                   event.target.style.backgroundColor = 'white';
                   event.target.style.fontWeight = 'normal';
                }
              });
              document.addEventListener('mouseup', function(event){
                let dropdown = document.getElementById('searchDropdown');
                if(!dropdown.contains(event.target)){
                  dropdown.style.display = 'none';
                }
              });
              let searchbox = document.getElementById('search');
              searchbox.addEventListener('click', function(){
                  if(searchbox.value.length > 2 && $("#residenceTabs li a.active").attr('href') == "#default-tab-4"){
                    let dropdown = document.getElementById('searchDropdown');
                    dropdown.style.display = 'block';
                    
                  }else{
                    let dropdown = document.getElementById('searchDropdown');
                    dropdown.style.display = 'none';
                  }
              });
              searchbox.addEventListener('input', function(){
                  if(searchbox.value.trim() === ''){
                    let dropdown = document.getElementById('searchDropdown');
                    dropdown.style.display = 'none';
                  }
              });
            },
        });
    }
    function searchForDuePayments(id, flag, passengerName){
      // first hide the dropdown
      let dropdown = document.getElementById('searchDropdown');
      dropdown.style.display = 'none';
      // show the loader
      $('#customerID').val(id);
      $('#passengerName').val(passengerName);
      getCustomerCurrencyForSearch();
    }
    // get customer currency for search
    function getCustomerCurrencyForSearch(){
      const getCustomerCurrencyForSearch = "getCustomerCurrencyForSearch";
      const customerID = $('#customerID').val();
      const passengerName = $('#passengerName').val();
     
      $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: {
              GetCustomerCurrencyForSearch:getCustomerCurrencyForSearch,
              CustomerID:customerID,
              PassengerName:passengerName
            },
            success: function (response) {
              let getCusCurRpt = JSON.parse(response);
              let searchAreaDiv = $('#searchAreaDiv');
              if(getCusCurRpt.length == 0){
                hideCurrencyDropdownForSearch();
                $('#customerID').val('');
                $('#passengerName').val('');
                $('#residenceCurrency').empty(); 
                $('#abstractViewArea').empty();
                $('#abstractViewArea').append('<h2 class="text-center">No Record Found...</h2><hr/>');
                return;
              }
              if($("#residenceTabs li a.active").attr('href') == "#default-tab-4"){
                $('#residenceCurrency').empty();
                showCurrencyDropdownForSearch();
              } 
              let selected = '';       
               for(var i=0; i<getCusCurRpt.length; i++){
                if(i == 0){
                  selected = 'selected';
                }else{
                  selected = '';
                }
                $('#residenceCurrency').append("<option "+ selected + "  value='"+ getCusCurRpt[i].currencyID +"'>"+ 
                getCusCurRpt[i].currencyName +"</option>");
              }
              getAbstrictView();
            },
        });
      
    }
    // the function is used for showing the abstrict view of customer balance
    function getAbstrictView(){
      const getAbstrictView = "getAbstrictView";
      const customerID = $('#customerID').val();
      const passengerName = $('#passengerName').val();
      const residenceCurrency = $('#residenceCurrency').val();
      $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: {
              GetAbstrictView:getAbstrictView,
              CustomerID:customerID,
              PassengerName:passengerName,
              ResidenceCurrency:residenceCurrency
            },
            success: function (response) {
              let getBlanceRpt = JSON.parse(response);
              $('#abstractViewArea').removeClass('d-none');
              $('#abstractViewArea').empty();
              let totalBalance = (parseInt(getBlanceRpt[0].total_residenceCost) + parseInt(getBlanceRpt[0].residenceFine)) - (parseInt(getBlanceRpt[0].total_residency_payment) + parseInt(getBlanceRpt[0].total_fine_payment));
              if(Number.isNaN(totalBalance)){
                totalBalance = 0;
              }
              let totalBalancePercentage =  Math.round(((parseInt(getBlanceRpt[0].total_residency_payment) + parseInt(getBlanceRpt[0].total_fine_payment))* 100) / (parseInt(getBlanceRpt[0].total_residenceCost) + parseInt(getBlanceRpt[0].residenceFine)));
              if(Number.isNaN(totalBalancePercentage)){
                totalBalancePercentage = 0;
              }
              let totalResidenceCostsPercentage =  Math.round(((parseInt(getBlanceRpt[0].total_residency_payment))* 100) / (parseInt(getBlanceRpt[0].total_residenceCost)));
              if(Number.isNaN(totalResidenceCostsPercentage)){
                totalResidenceCostsPercentage = 0;
              }
              let totalFinePercentage =  Math.round(((parseInt(getBlanceRpt[0].total_fine_payment))* 100) / (parseInt(getBlanceRpt[0].residenceFine)));
              if(Number.isNaN(totalFinePercentage)){
                totalFinePercentage = 0;
              }
              let totalPaid = parseInt(getBlanceRpt[0].total_residency_payment) + parseInt(getBlanceRpt[0].total_fine_payment);
              if(Number.isNaN(totalPaid)){
                totalPaid = 0;
              }
              let balanceLable = '';
              let residenceCostLabel = '';
              let fineLable = '';
              if(totalBalance !=  0 ){
                balanceLable = 'Due Payment';
              }else{
                balanceLable = 'Payments completed';
              }
              if(parseInt(getBlanceRpt[0].total_residenceCost) - parseInt(getBlanceRpt[0].total_residency_payment) == 0){
                residenceCostLabel = 'Residence payment completed';
              }else{
                residenceCostLabel = 'Due Residence payment';
              }
              if(parseInt(getBlanceRpt[0].residenceFine) - parseInt(getBlanceRpt[0].total_fine_payment) == 0){
                fineLable = 'Fine Cleared';
              }else{
                fineLable = 'Due Fine';
              }
              $('#abstractViewArea').append(`<div class="col-lg-1">
                                          <div class="h-100px d-flex align-items-center justify-content-center">
                                            <i class="fa fa-cc-visa" style="font-size:50px;color:#49b6d6"></i>
                                          </div>
                                        </div>
                                        <div class="col-lg-2">
                                          <div class="mb-2px text-truncate">Total Residence Cost</div>
                                          <div class="fs-18px mb-5px fw-bold" data-animation="number" data-value="${getBlanceRpt[0].total_residenceCost}">${numeral(getBlanceRpt[0].total_residenceCost).format('0,0')} </div>
                                          <div class="d-flex align-items-center mb-2px">
                                            <div class="flex-grow-1">
                                              <div class="progress h-5px rounded-pill bg-white bg-opacity-10">
                                                <div class="progress-bar progress-bar-striped bg-info" data-animation="width" data-value="${totalResidenceCostsPercentage}%" style="width: ${totalResidenceCostsPercentage}%;"></div>
                                              </div>
                                            </div>
                                            <div class="ms-2 small w-30px text-center"><span data-animation="number" data-value="${totalResidenceCostsPercentage}">${totalResidenceCostsPercentage}</span>%</div>
                                          </div>
                                          <div class="text-gray-500 small mb-15px text-truncate">
                                           Currnecy: <span> ${getBlanceRpt[0].currencyName}</span>
                                          </div>
                                          <a href="#" class="btn btn-xs btn-info fs-10px ps-2 pe-2">${residenceCostLabel}</a>
                                        </div>
                                        <div class="col-lg-1">
                                          <div class="h-100px d-flex align-items-center justify-content-center">
                                            <i class="fa fa-facebook-f" style="font-size:50px;color:#ff5b57"></i>
                                          </div>
                                        </div>
                                        <div class="col-lg-2">
                                          <div class="mb-2px text-truncate">Total Fine</div>
                                          <div class="fs-18px mb-5px fw-bold" data-animation="number" data-value="${getBlanceRpt[0].residenceFine}">${numeral(getBlanceRpt[0].residenceFine).format('0,0')}</div>
                                          <div class="d-flex align-items-center mb-2px">
                                            <div class="flex-grow-1">
                                              <div class="progress h-5px rounded-pill bg-white bg-opacity-10">
                                                <div class="progress-bar progress-bar-striped bg-danger" data-animation="width" data-value="${totalFinePercentage}%" style="width: ${totalFinePercentage}%;"></div>
                                              </div>
                                            </div>
                                            <div class="ms-2 small w-30px text-center"><span data-animation="number" data-value="${totalFinePercentage}">${parseInt(totalFinePercentage)}</span>%</div>
                                          </div>
                                          <div class="text-gray-500 small mb-15px text-truncate">
                                           Currnecy: <span> ${getBlanceRpt[0].currencyName}</span>
                                          </div>
                                          <a href="#" class="btn btn-xs btn-danger fs-10px ps-2 pe-2">${fineLable}</a>
                                        </div>
                                        <div class="col-lg-1">
                                          <div class="h-100px d-flex align-items-center justify-content-center">
                                            <i class="fa fa-paypal" style="font-size:50px;color:#f59c1a"></i>
                                          </div>
                                        </div>
                                        <div class="col-lg-2">
                                          <div class="mb-2px text-truncate">Total Paid</div>
                                          <div class="fs-18px mb-5px fw-bold" data-animation="number" data-value="${totalPaid}">${numeral(totalPaid).format('0,0')} </div>
                                          <div class="d-flex align-items-center mb-2px">
                                            <div class="flex-grow-1">
                                              <div class="progress h-5px rounded-pill bg-white bg-opacity-10">
                                                <div class="progress-bar progress-bar-striped bg-warning" data-animation="width" data-value="${totalBalancePercentage}%" style="width: ${totalBalancePercentage}%;"></div>
                                              </div>
                                            </div>
                                            <div class="ms-2 small w-30px text-center"><span data-animation="number" data-value="${totalBalancePercentage}">${totalBalancePercentage}</span>%</div>
                                          </div>
                                          <div class="text-gray-500 small mb-15px text-truncate">
                                           Currnecy: <span> ${getBlanceRpt[0].currencyName}</span>
                                          </div>
                                          <a href="#" class="btn btn-xs btn-warning fs-10px ps-2 pe-2">${balanceLable}</a>
                                        </div><div class="col-lg-1">
                                          <div class="h-100px d-flex align-items-center justify-content-center">
                                            <i class="fa fa-credit-card" style="font-size:50px;color:#8753de"></i>
                                          </div>
                                        </div>
                                        <div class="col-lg-2">
                                          <div class="mb-2px text-truncate">Total Due Balance</div>
                                          <div class="fs-18px mb-5px fw-bold" data-animation="number" data-value="${totalBalance}">${numeral(totalBalance).format('0,0')}</div>
                                          <div class="d-flex align-items-center mb-2px">
                                            <div class="flex-grow-1">
                                              <div class="progress h-5px rounded-pill bg-white bg-opacity-10">
                                                <div class="progress-bar progress-bar-striped bg-indigo" data-animation="width" data-value="${totalBalancePercentage}%" style="width: ${totalBalancePercentage}%;"></div>
                                              </div>
                                            </div>
                                            <div class="ms-2 small w-30px text-center"><span data-animation="number" data-value="${totalBalancePercentage}">${totalBalancePercentage}</span>%</div>
                                          </div>
                                          <div class="text-gray-500 small mb-15px text-truncate">
                                           Currnecy: <span> ${getBlanceRpt[0].currencyName}</span>
                                          </div>
                                          <a href="#" class="btn btn-xs btn-indigo fs-10px ps-2 pe-2">${balanceLable}</a>
                                        </div><hr style="margin-top: 25px"/>`);
                                        getCustomerInfo(customerID);
                                        $('#currencyInfo').empty();
                                        $('#currencyInfo').append(getBlanceRpt[0].currencyName);
                                        getResidenceLedger();

            },
          });
    }
    // for showing currency dropdown while searching in tab 4
    function showCurrencyDropdownForSearch(){
      const searchAreaDiv = $('#searchAreaDiv');
      searchAreaDiv.removeClass('offset-6');
      searchAreaDiv.addClass('offset-4');
      $('#currencyAreaForRpt').removeClass('d-none');
    }
    // for hiding currency dropdown while searching in other tabs
    function hideCurrencyDropdownForSearch(){
      const searchAreaDiv = $('#searchAreaDiv');
      searchAreaDiv.removeClass('offset-4');
      searchAreaDiv.addClass('offset-6');
      $('#currencyAreaForRpt').addClass('d-none');
    }
    function getCustomerInfo(id){
    var getCustomerInfo = "getCustomerInfo";
      $.ajax({
          type: "POST",
          url: "InvoiceController.php",  
          data: {
            GetCustomerInfo:getCustomerInfo,
            ID:id
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#name').text(report[0].customer_name);
            
            if(report[0].customer_email == ""){
                
                $('#email').text('Nill');
            }else{
                $('#email').text(report[0].customer_email);
            }
            if(report[0].customer_phone == ''){
                $('#phone').text('Nill');
            }else{
                $('#phone').text(report[0].customer_phone);
            }
            
          },
      });
    }
    function getResidenceLedger(){
      const customerID = $('#customerID').val();
      const passengerName = $('#passengerName').val();
      const residenceCurrency = $('#residenceCurrency').val();
      let getResidenceLedger = "getResidenceLedger";
      $.ajax({
          type: "POST",
          url: "residenceReportController.php",  
          data: {
            GetResidenceLedger:getResidenceLedger,
            CustomerID:customerID,
            PassengerName:passengerName,
            CurID:residenceCurrency,
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#TicketReportTbl').empty();
            var j = 1;
            var finalTable = "";
            var remaining = 0;
            var total = 0;
            var totalRefund = 0;
            var customerTotalPaid = 0;
            for(var i=0; i<report.length; i++){
                if(report[i].transactionType == "Residence Payment"){
                    remaining = remaining - parseInt(report[i].credit);
                    customerTotalPaid += parseInt(report[i].credit);
                    finalTable = "<tr><td class='payment' scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td style='-webkit-print-color-adjust: exact;' class='text-capitalize payment' "+
                    ">"+ report[i].transactionType +"</td>"+
                    "<td class='text-capitalize payment' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td class='payment' style='-webkit-print-color-adjust: exact;'>"+ report[i].dt +
                    "</td><td style='-webkit-print-color-adjust: exact;' class='payment'>"+report[i].visaType+"</td><td class='payment' style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].debit).format('0,0') +"</td><td class='payment' style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].credit).format('0,0') +"</td>"+
                    "<td class='payment' style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].transactionType == "Residence application"){
                    total += parseInt(report[i].debit);
                    remaining = remaining + parseInt(report[i].debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td style='-webkit-print-color-adjust: exact;' class='text-capitalize' "+
                    ">"+ report[i].transactionType +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].dt +
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].visaType+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].transactionType == "Residence Fine"){
                    total += parseInt(report[i].debit);
                    remaining = remaining + parseInt(report[i].debit);
                    finalTable = "<tr><td class='fine' scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td style='-webkit-print-color-adjust: exact;' class='text-capitalize fine' "+
                    ">"+ report[i].transactionType +"</td>"+
                    "<td class='text-capitalize fine' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td class='fine' style='-webkit-print-color-adjust: exact;'>"+ report[i].dt +
                    "</td><td class='fine' style='-webkit-print-color-adjust: exact;'>"+report[i].visaType+"</td><td class='fine' style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].debit).format('0,0') +"</td><td class='fine' style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].credit).format('0,0') +"</td>"+
                    "<td class='fine' style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].transactionType == "Residence Fine Payment"){
                    remaining = remaining - parseInt(report[i].credit);
                    customerTotalPaid += parseInt(report[i].credit);
                    finalTable = "<tr><td class='fine_payment' scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td style='-webkit-print-color-adjust: exact;' class='text-capitalize fine_payment' "+
                    ">"+ report[i].transactionType +"</td>"+
                    "<td class='text-capitalize fine_payment' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td class='fine_payment' style='-webkit-print-color-adjust: exact;'>"+ report[i].dt +
                    "</td><td class='fine_payment' style='-webkit-print-color-adjust: exact;'>"+report[i].visaType+"</td><td class='fine_payment' style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].debit).format('0,0') +"</td><td class='fine_payment' style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].credit).format('0,0') +"</td>"+
                    "<td class='fine_payment' style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }
                
                $('#TicketReportTbl').append(finalTable);
                j +=1;
            
              
              
            }
            calculateBalancesForLedger(total,customerTotalPaid,remaining);
          },
      });
    }
    function calculateBalancesForLedger(total,customerTotalPaid,remaining){
            const currencyLabel = $('#residenceCurrency').text();
            $('#total').text(numeral(total).format('0,0') + ' ' + currencyLabel );
            $('#total_paid').text(numeral(customerTotalPaid).format('0,0') + ' ' + currencyLabel);
            $('#outstanding_balance').text(numeral(remaining).format('0,0') + ' ' + currencyLabel);
    }
    function printLedger(){
      //printJS({ printable: 'printThisArea', type: 'html', style: '.table th { background-color: #dc3545 !important;color: white; }.table-striped tbody tr:nth-of-type(odd) td {background-color: rgba(0, 0, 0, .05)!important;}.table tbody tr td.customAbc {background-color: grey !important;color: white;} .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6,.col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {float: left;} .col-sm-12 {width: 100%;} .col-sm-11 {width: 91.66666666666666%;} .col-sm-10 {width: 83.33333333333334%;} .col-sm-9 {width: 75%;} .col-sm-8 {width: 66.66666666666666%;} .col-sm-7 {width: 58.333333333333336%;}.col-sm-6 {width: 50%;} .col-sm-5 {width: 41.66666666666667%;} .col-sm-4 {width: 33.33333333333333%;} .col-sm-3 {width: 25%;} .col-sm-2 {width: 16.666666666666664%;} .col-sm-1 {width: 8.333333333333332%;}' })
      printJS({ 
          printable: 'printThisArea', 
          type: 'html', 
        
              css: [
                  'bootstrap-4.3.1-dist/css/bootstrap.min.css',
                  'customBootstrap.css',
                  'https://fonts.googleapis.com/css?family=Arizonia',
                  'https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap',
              ],
              targetStyles: ['*'],
              onLoadingStart: ()=>{
                $('#myTable').removeClass('table-dark');
                $('#myTable').addClass('table-striped');
              },
              onLoadingEnd: () => {
                $('#myTable').addClass('table-dark');
                $('#myTable').removeClass('table-striped');
              },
      })
    }
</script>
</body>
</html>