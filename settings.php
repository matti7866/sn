<?php include 'header.php' ?>
<title>Settings</title>
<?php 
  include 'nav.php';
  if(!isset($_SESSION['user_id'])){
    header('location: login.php');
  }

  
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 mb-2">
      <h3>Settings</h3>
    </div>
    <div class="col-md-12">
    <ul class="nav nav-tabs">
    
      <li class="nav-item">
        <a href="#whatsapp-api" data-bs-toggle="tab" class="nav-link active">Whatsapp API Settings</a>
      </li>
    </ul>
    <div class="tab-content panel p-3 rounded-0 rounded-bottom">
      
      <div class="tab-pane fade  active show" id="whatsapp-api">
        <form action="" method="POST" id="frmWhatsapp">
          <input type="hidden" name="action" value="saveSettings">
          <div class="row">
            <div class="col-md-6 mx-auto">
              <div class="row">
                <div class="col-md-12" id="msgEv"></div>
              </div>
              <div class="row mb-2">
                <label for="" class="form-label col-form-label col-md-4">Whatsapp API (EV)</label>
                <div class="col-md-3">
                  <select name="settings[ev_status]" id="ev_status" class="form-select">
                    <option value="1">Enabled</option>
                    <option value="0">Disabled</option>
                  </select>
                </div>
              </div>
              <div class="row mb-2">
                <label for="ev_url" class="form-label col-form-label col-md-4">Whatsapp API URL (EV)</label>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="settings[ev_url]" id="ev_url" value="<?php echo $settings['ev_url'] ?>">
                </div> 
              </div>
              <div class="row mb-2">
                <label for="ev_api_key" class="form-label col-form-label col-md-4">Whatsapp API Key (EV)</label>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="settings[ev_api_key]" id="ev_api_key" value="<?php echo $settings['ev_api_key'] ?>">
                </div> 
              </div>
              
              <div class="row mb-2">
                <label for="ev_instance" class="form-label col-form-label col-md-4">Whatsapp API Instance Name (EV)</label>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="settings[ev_instance]" id="ev_instance" value="<?php echo $settings['ev_instance'] ?>">
                </div> 
              </div>

              <div class="row mb-2">
                <label class="form-label col-form-label col-md-4">API Status</label>
                <div class="col-md-8" id="apiStatus"></div>
              </div>
              <div class="row mb-2">
                <label class="form-label col-form-label col-md-4">Instance Status</label>
                <div class="col-md-8" id="instanceStatus"></div>
              </div>

              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-8">
                  <button type="submit" id="btnSubmit" class="btn btn-primary">Save Settings</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    </div>
  </div>
</div>

<?php include 'footer.php' ?>
<script type="text/javascript">
  
  function getEvStatus(){
    $("#apiStatus").html('Loading...');
    $("#instanceStatus").html('Loading...');
    $.ajax({
      url: 'settingsController.php',
      type: 'POST',
      data: {action: 'loadEvStatus'},
      success: function(response){
        if(response.status == 'success'){
          $('#apiStatus').html(response.data.apiStatus);
          $('#instanceStatus').html(response.data.instanceStatus);
        }
      }
    });
  }

  $(document).ready(function(){
    getEvStatus();

    $('#frmWhatsapp').on('submit',function(e){
      e.preventDefault();
      var frm = $(this);
      var btn = frm.find('#btnSubmit');
      var msg = frm.find('#msgEv');
      

      btn.attr('data-temp',btn.html()).attr('disabled',true).html('Saving...');
      $.ajax({
        url: 'settingsController.php',
        type: 'POST',
        data: frm.serialize(),
        success: function(response){
          if(response.status == 'success'){
            msg.html('<div class="alert alert-success">'+response.message+'</div>');
          }else{
            msg.html('<div class="alert alert-danger">'+response.message+'</div>');
          }
          btn.html(btn.attr('data-temp')).attr('disabled',false);
          getEvStatus();
        }
      });
    })

  });


</script>