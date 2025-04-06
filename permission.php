<?php
  include 'header.php';
?>
<title>Permission</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Permission' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
$insert = $records[0]['insert'];
if($select == 0){
  echo "<script>window.location.href='pageNotFound.php'</script>";
}
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="card w3-card-24 " id="todaycard">
            <div class="card-header bg-light">
                <h1 class="text-danger text-center"><b><i>Permission</i></b></h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <select class="form-control  js-example-basic-single" onchange="rolePermission(); return false" style="width:100%" name="employee_role" id="employee_role"></select>
                            </div>
                            <div class="col-md-4 offset-md-4">
                                <button type="button" class="btn btn-danger float-end" onclick="checkAll()">Select All</button>
                            </div>
                        </div>
                        <br/>
                        <div class="table-responsive ">
                            <table id="myTable"  class="table  table-striped table-hover ">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>S#</th>
                                        <th>Page Name</th>
                                        <th>Select</th>
                                        <th>Insert</th>
                                        <th>Update</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Dashboard</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk1">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check" style="display:none">
                                            <input type="checkbox"  class="form-check-input" id="chk2">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check" style="display:none">
                                            <input type="checkbox"  class="form-check-input" id="chk3">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check" style="display:none">
                                            <input type="checkbox"  class="form-check-input" id="chk4">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>Ticket</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk6">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk7">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk8">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>Visa</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk9">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk10">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk11">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk12">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">4</th>
                                    <td>Loan</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk13">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk14">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk15">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk16">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">5</th>
                                    <td>Hotel</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk17">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk18">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk19">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk20">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">6</th>
                                    <td>Rental Car</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk21">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk22">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk23">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk24">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">7</th>
                                    <td>Hawala</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk25">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk26">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk27">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk28">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">8</th>
                                    <td>Supplier</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk29">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk30">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk31">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk32">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">9</th>
                                    <td>Supplier Ledger</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk33">
                                        </div>
                                    </td>
                                    <td id="chk34">
                                        <div class="form-check" style="display:none">
                                            <input type="checkbox"  class="form-check-input" id="chk34">
                                        </div>
                                    </td>
                                    <td id="chk35">
                                        <div class="form-check" style="display:none">
                                            <input type="checkbox"  class="form-check-input" id="chk35">
                                        </div>
                                    </td>
                                    <td id="chk36">
                                        <div class="form-check" style="display:none">
                                            <input type="checkbox"  class="form-check-input" id="chk36">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">10</th>
                                    <td>Expenses</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk37">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk38">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk39">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk40">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">11</th>
                                    <td>Customer Payment</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk41">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk42">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk43">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk44">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">12</th>
                                    <td>Supplier Payment</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk45">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk46">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk47">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk48">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">13</th>
                                    <td>Customer</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk49">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk50">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk51">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk52">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">14</th>
                                    <td>Customer Ledger</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk53">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check" style="display:none">
                                            <input type="checkbox"  class="form-check-input" id="chk54">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check" style="display:none">
                                            <input type="checkbox"  class="form-check-input" id="chk55">
                                        </div>
                                    </td>
                                    <td id="chk56">
                                        <div class="form-check" style="display:none">
                                            <input type="checkbox"  class="form-check-input" id="chk56">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">15</th>
                                    <td>Staff</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk57">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk58">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk59">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk60">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">16</th>
                                    <td>Role</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk61">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk62">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk63">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk64">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">17</th>
                                    <td>Permission</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk65">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk66">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk67">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk68">
                                        </div>
                                    </td>
                                </tr>
<tr>
                                    <th scope="row">18</th>
                                    <td>Supplier Visa Prices</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk69">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk70">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk71">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk72">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">19</th>
                                    <td>Customer Visa Prices</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk73">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk74">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk75">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk76">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">20</th>
                                    <td>Accounts</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk77">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk78">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk79">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk80">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">21</th>
                                    <td>Salary</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk81">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk82">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk83">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk84">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">22</th>
                                    <td>Deposit</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk85">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk86">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk87">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk88">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">23</th>
                                    <td>Reminder</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk89">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk90">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk91">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk92">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">24</th>
                                    <td>Pending Tasks</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk93">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk94">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk95">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk96">
                                        </div>
                                    </td>
                                    
                                </tr>
                                <tr>
                                <th scope="row">25</th>
                                    <td>Company Documents</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk97">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk98">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk99">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk100">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                <th scope="row">26</th>
                                    <td>Currency</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk101">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk102">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk103">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk104">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                <th scope="row">27</th>
                                    <td>Residence</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk105">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk106">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk107">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk108">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                <th scope="row">28</th>
                                    <td>Service</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk109">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk110">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk111">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk112">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                <th scope="row">29</th>
                                    <td>Cheques</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk113">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk114">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk115">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"  class="form-check-input" id="chk116">
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div> 
                        <div class="row">
                                  
                            <div class="col-md-4 offset-md-8">
                                <?php  if($insert ==1) {  ?>
                                <button class="btn btn-danger float-end" onclick="AddPermission()"><i class="fa fa-save"></i> Save</button>
                               <?php }  ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   </div>
</div>
<?php include 'footer.php'; ?>
<script>
  $(document).ready(function(){
    getRole('all',0);
    $('.js-example-basic-single').select2();
  });
  function getRole(type,id){
    var getRole = "getRole";
    $.ajax({
        type: "POST",
        url: "permissionController.php",  
        data: {
          GetRole:getRole,
        },
        success: function (response) {  
            var roles = JSON.parse(response);
            if(type == "all"){
              $('#employee_role').empty();
              $('#employee_role').append("<option value='-1'>--Select Role--</option>");
              for(var i=0; i<roles.length; i++){
                $('#employee_role').append("<option value='"+ roles[i].role_id +"'>"+ 
                roles[i].role_name +"</option>");
              }
            }else{
              $('#updemployee_role').empty();
              $('#updemployee_role').append("<option value='-1'>--Select Role--</option>");
              for(var i=0; i<roles.length; i++){
                if(roles[i].role_id ==id ){
                  selected ='selected';
                }else{
                  selected = '';
                }
                $('#updemployee_role').append("<option "+ selected +" value='"+ roles[i].role_id +"'>"+ 
                roles[i].role_name +"</option>");
              }
            }
        },
    });
  }
  function AddPermission(){
    var employee_role = $('#employee_role').select2('data');
    if(employee_role[0].id == "-1"){
        notify('Validation Error!', 'Role is required', 'error');
        return;
    }
   var Insert_Permission = "Insert_Permission";
   var employee_role = employee_role[0].id;
   var finalArr = []; 
   var x = document.getElementById("myTable").rows.length;
   var limit = 0;
        for(var i=1;i<x;i++){
	        var tr = document.getElementsByTagName("tr")[i];
	        var tdl = tr.getElementsByTagName("td").length;
	        for(var j=0;j<tdl;j= j+5){
		        var td = tr.getElementsByTagName("td")[j];
                finalArr.push(td.innerHTML);
                for(var k=1;k<=4;k++){
                    limit += 1;
                    if($('#chk'+limit).is(":checked")){
                        finalArr.push(1);
                    }else{
                        finalArr.push(0);
                    }
                }
	        }
        }
        $.ajax({
            type: "POST",
            url: "permissionController.php",  
            data: {
                Insert_Permission:Insert_Permission,
                FinalArr: finalArr,
                Employee_Role:employee_role
            },
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    location.reload(true);
                }else{
                    notify('Error!', response, 'error');
                }
            },
        }); 
  }
  function checkAll(){
    $('.form-check-input').prop('checked', true);
  }
  function rolePermission(){
    var getPermissions = "getPermissions";
    var employee_role = $('#employee_role').select2('data');
    if(employee_role[0].id == "-1"){
        $('.form-check-input').prop('checked', false);
    }else{
        employee_role = employee_role[0].id;
        $.ajax({
            type: "POST",
            url: "permissionController.php",  
            data: {
                GetPermissions:getPermissions,
                Employee_Role:employee_role
            },
            success: function (response) {
                var permissions = JSON.parse(response);
                if(permissions.length == 0){
                    $('.form-check-input').prop('checked', false);
                }else{
                    var limit = 1;
                for(var chk = 0; chk< permissions.length; chk++){
                    if(permissions[chk].select == 1){
                        $("#chk"+limit).prop('checked', true);
                    }
                    if(permissions[chk].insert == 1){
                        $("#chk"+(limit+1)).prop('checked', true);
                    }
                    if(permissions[chk].update == 1){
                        $("#chk"+(limit+2)).prop('checked', true);
                    }
                    if(permissions[chk].delete == 1){
                        $("#chk"+(limit+3)).prop('checked', true);
                    }
                    limit +=4;
                }
                }
                
            },
        }); 
    }
  }
    

</script>
</body>
</html>