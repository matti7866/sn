function drawCustomerPaymentModal(){
    var cusPaymentModal = `
    <div class="modal dropdownModal fade" id="exampleModal"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color:#20252a;" >
            <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fa fa-money" aria-hidden="true"></i> <i>Add Customer Payment</i></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <div class="modal-body">
            <form id="CountryNameForm"> 
             <div class="form-group row mb-2">
                <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Customer Name:</label>
                <div class="col-sm-8">
                <select class="form-control" onchange="getCustomerPendingPayment()" style="width:100%" name="addcustomer_id" id="addcustomer_id"></select>
                </div>
              </div>
              <div class="form-group row mb-2 totalChargeDiv d-none" >
                <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-barcode"></i> Total Charges:</label>
                <div class="col-sm-8" id="pendingPaymentSection">
                    
                </div>
              </div>
              <div class="row mb-2">
                <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-barcode"></i> Payment Recieved:</label>
                <div class="col-sm-8">
                  <input type="number" class="form-control col-sm-"  name="payment_recieved" id="payment_recieved" placeholder="Payment Recieved">
                </div>
              </div>
              <div class="row mb-2">
                <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-sticky-note-o"></i> Remarks:</label>
                <div class="col-sm-8">
                  <textarea class="form-control col-sm-" rows="3"  name="remarks" id="remarks" placeholder="Remarks..."></textarea>
                </div>
              </div>
              <div class="form-group row mb-2">
                <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Account:</label>
                <div class="col-sm-8" id="accountSection">
                  <select class="form-control" style="width:100%" onchange="getConditionalCur('byInsert')" name="addaccount_id" id="addaccount_id"></select>
                </div>
                <div class="col-sm-3 d-none" id="currencySection">
                  <select class=" form-control col-sm-4"  style="width:100%"  id="payment_currency_type" name="payment_currency_type" ></select>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
            <button onclick="makePay()" id="mkCustomerPayBtn" type="button" class="btn text-white bg-danger">Save</button>
          </div>
          </form>
        </div>
      </div>
    </div>`;
    $('#customer-payment-modal').append(cusPaymentModal);
}