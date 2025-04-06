<?php
$dbHost = "localhost";
$dbUser = "analytics";
$dbPass = "jNa77kK4kGWSrDiw";
$dbName = "analytics";

$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);


if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

function send_response($data = array())
{
  header('Content-Type: application/json');
  echo json_encode($data);
  die();
}

function filter_post($key)
{
  return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

// if request type is post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


  $start = filter_post('start');
  $end = filter_post('end');


  if ($start == "" || $end == "") {
    send_response(array('message' => 'LC# Start and End are required', 'status' => 'error'));
  }

  if (!is_numeric($start) || !is_numeric($end)) {
    send_response(array('message' => 'LC# Start and End must be numbers', 'status' => 'error'));
  }

 
  if ($start > $end) {
    send_response(array('message' => 'LC# Start must be less than LC# End', 'status' => 'error'));
  }

  // maxium 50 allowed
  if (($end - $start) + 1 > 50) {
    send_response(array('message' => 'Maximum 50 LC#s allowed', 'status' => 'error'));
  }


  $data = array();
  $html = '';

  for ($i = $start; $i <= $end; $i++) {

    $url = 'https://dubaiz.ae/api/lc.php?l=' . $i;
    $response = json_decode(file_get_contents($url));

    if ($response->status != 'success') {
      $html .= '<tr>';
      $html .= '<td>' . $i . '</td>';
      $html .= '<td colspan="7" class="text-center">Unable to fetch data</td>';
      $html .= '</tr>';
      continue;
    }

    $data = isset($response->data) && is_array($response->data) && count($response->data) > 0 ? $response->data[0] : array();

    $status = '';

    if (!isset($data->personCode)) {
      $html .= '<tr>';
      $html .= '<td>' . $i . '</td>';
      $html .= '<td colspan="7" class="text-center">Person Code not found</td>';
      $html .= '</tr>';
      continue;
    }

    // check if person code is already in db
    $check = mysqli_query($conn, "SELECT * FROM `members` WHERE `personCode` = '$data->personCode'");
    if (mysqli_num_rows($check) > 0) {
      $status = '<span class="badge bg-warning">Exists</span>';
    } else {

      // convert change date format from dd/mm/yyyy to yyyy-mm-dd
      $dob = date('Y-m-d', strtotime(str_replace('/', '-', $data->dob)));

      // insert
      mysqli_query($conn, "
      INSERT INTO `members` 
      (`personCode`, `labourCardNumber`, `name`, `dob`, `nationality`, `gender`,`datetime_created`) 
      VALUES ('{$data->personCode}', '{$i}', '{$data->nameEn}', '{$dob}', '{$data->nationality}', '{$data->gender}',NOW())
      ");
      $status = '<span class="badge bg-success">Added</span>';
    }

    $html .= '<tr>';
    $html .= '<td>' . $i . '</td>';
    $html .= '<td>' . $data->personCode . '</td>';
    $html .= '<td>' . $data->nameEn . '</td>';
    $html .= '<td>' . $data->nationality . '</td>';
    $html .= '<td>' . $data->dob . '</td>';
    $html .= '<td>' . $data->gender . '</td>';
    $html .= '<td>' . $status . '</td>';
    $html .= '</tr>';
  }

  send_response(array('message' => 'Data fetched successfully', 'status' => 'success', 'data' => $html));


  die();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/darkly/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>


<body>
  <div class="container mt-4">
    <h3>Fetch Data</h3>
    <form action="" method="post">
      <div class="row">
        <div class="col-md-2 mb-2">
          <label for="start">
            LC# Start
          </label>
          <input type="number" name="start" id="start" class="form-control">
        </div>
        <div class="col-md-2 mb-2">
          <label for="end">LC# End</label>
          <input type="number" name="end" id="end" class="form-control">
        </div>
        <div class="col-md-2 mb-2">
          <label for="">&nbsp;</label>
          <button class="btn btn-primary w-100" type="submit">
            <i class="fa-solid fa-download"></i> Download
          </button>
        </div>
      </div>
    </form>

    <div class="row">
      <div class="col-md-12" id="message"></div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Data</h5>
            <table class="table table-bordered table-sm table-striped">
              <thead>
                <tr>
                  <th>LC#</th>
                  <th>Person Code</th>
                  <th>Name</th>
                  <th>Nationality</th>
                  <th>Date of Birth</th>
                  <th>Gender</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="data"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</body>
<script src=" https://code.jquery.com/jquery-3.7.1.min.js">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $(document).ready(function() {
    $('form').submit(function(e) {
      var frm = $(this);
      var btn = frm.find('button[type="submit"]');
      e.preventDefault();
      $('#message').html('<div class="alert alert-info"><i class="fa-solid fa-spinner fa-spin"></i> Please wait while we are fetching data...</div>');
      btn.attr('disabled', true);

      $.ajax({
        url: 'doit.php',
        method: 'post',
        data: frm.serialize(),
        error: function(jqXHR, textStatus, errorThrown) {
          $('#message').html('<div class="alert alert-danger">' + errorThrown + '</div>');
        },
        success: function(e) {
          if (e.status == 'error') {
            $('#message').html('<div class="alert alert-danger">' + e.message + '</div>');
            $('#data').html('');
          } else {
            $('#message').html('<div class="alert alert-success">' + e.message + '</div>');
            $('#data').html(e.data);
          }
        },
        complete: function() {
          btn.attr('disabled', false);
        }
      });
    });
  });
</script>

</html>