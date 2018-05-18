<?php
include 'header.php';
$id=$_GET['id'];
$main=mysql_fetch_array(mysql_query("SELECT * FROM settings"));
$company_name=$main['main_name'];
$company_location=$main['main_location'];
$company_tel=$main['main_tel'];
$company_address=$main['main_address'];
$email=$main['email'];
$inv=mysql_fetch_array(mysql_query("SELECT i.invoicenumber as invoicenumber,i.dateadded as dateadded,p.name as name,i.totalcost as invoicetotal from invoices i inner join patients p on p.id=i.patientid where i.id='$id'"));
$count=$inv['invoicenumber'];
$result=mysql_query("SELECT ii.installment as installment,ii.status as status ,ii.datedue as datedue  from invoice_installments ii inner join invoice_paymentplan ip on ip.id=ii.invoiceplanid where ip.invoiceid='$id'");

$total=$inv['invoicetotal'];

?>

<div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Billing</a> <a href="#" class="current">invoice</a> </div>
    <h1>Invoice</h1>
  </div>
  <div class="container-fluid"><hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-briefcase"></i> </span>
            <h5 ><?php echo $company_name ?></h5>
          </div>
          <div class="widget-content">
            <div class="row-fluid">
              <div class="span6">
                <table class="">
                  <tbody>
                    <tr>
                      <td><h4><?php echo $company_name ?></h4></td>
                    </tr>
                    <tr>
                      <td><?php echo $company_tel ?></td>
                    </tr>
                    <tr>
                      <td><?php echo $company_address ?></td>
                    </tr>
                    <tr>
                      <td><?php echo $company_location ?></td>
                    </tr>
                    <tr>
                      <td ><?php echo $email?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="span6">
                <table class="table table-bordered table-invoice">
                  <tbody>
                    <tr>
                    <tr>
                      <form action="actionclass.php?action=addinvoiceitems" method="post">
                      <td class="width30">Invoice ID:</td>
                      <td class="width70"><strong><input type="text" name="invoiceno" value="<?php echo $count ?>" readonly=""></strong></td>
                    </tr>
                    <tr>
                      <td>Issue Date:</td>
                      <td><strong><input type="date" name="dateadded" value="<?php echo $inv['dateadded']?>" readonly=""></strong></td>
                    </tr>
                    <tr>
                    <td class="width30">Client:</td>
                    <td class="width70">
                     <input type="text" name="name" value="<?php echo $inv['name']?>" readonly="">

                    </td>
                  </tr>
                    <tr>
                    <td class="width30">Amount Due:</td>
                    <td class="width70">
                     <input type="text" name="name" value="<?php echo $total?>" readonly="">

                    </td>
                  </tr>
                    </tbody>
                  </form>
                </table>
              </div>
            </div>
            <div class="row-fluid">
              <div class="span12">
                <table class="table table-bordered table-invoice-full">
                  <thead>
                    
                    <tr>
                      <th class="head0">Installment Amount</th>
                      <th class="head1">Date Due</th>
                      <th class="head0 right">Status</th>
                      <th class="head0 ">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php while($row=mysql_fetch_array($result)){?>
                    <tr>
                      <td class="right"><?php echo $row[0]?></td>
                      <td class="right"><?php echo $row[2]?></td>
                      <?php 
                      $status=$row[1];
                      if($status=='0'){

                      echo "<td>Unpaid</td>";
                    }elseif ($status=='1') {
                      echo "<td>Paid</td>";
                    } ?>
                    <td><a href="invoice.php?id=<?php echo $id?>" class="btn btn-success " >Process Payement</a> </td>
                    </tr>
                    <?php }?>
                    
                  </tbody>
                </table>
                <div class="pull-right">

                  <h4><span>Amount Due:</span><?php //echo $total ?></h4>
                  <input type="number" id="total" readonly="" value="<?php echo $total ?>"> 
                  <br>
                  <form action="paymentstructure.php?id=<?php echo $id?>" method="post">
                   <label>Choose Payment Structure</label>
                  <select name="planid" class="form-control">
                    <option value="1">Cash</option>
                    <option value="2">Installments</option>
                  </select>
                  <label>Period</label>
                  <input type="text" name="period" required="" id="period" onkeyup="javascript:check()" class="form-control">
                  <label>Installments</label>
                  <input type="text" name="installments" id="installments" readonly="" =""  class="form-control">
                  <br>
                  <button class="btn btn-primary btn-large pull-right" >Submit Invoice</button> 
                </form>
                </div></div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--Footer-part-->

<?php include('footer.php') ?>
</div>
<!--end-Footer-part--> 

<!-- modal -->

        <div id="myAlert" class="modal hide">
              <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button">×</button>
                <h3>Add Billing Item</h3>
              </div>

              <div class="modal-body">
                <form method="post" action="actionclass.php?action=addinvoiceitem&&invoiceid=<?php echo $id?>&&clientid=">
                   <div class="control-group">
                  <label class="control-label">Product</label>
                  <div class="controls">
                    <select name="product" class="form-control" id="project" >
                    <?php
                    $productresult=mysql_query("SELECT id,productname from products");
                    while($productrow=mysql_fetch_array($productresult)){
                    ?>  
                    <option value="<?php echo $productrow[0]?>"><?php echo $productrow[1]?></option>
                    <?php }?> 
                    </select>

                  <label class="control-label">Quantity</label>
                    <input type="text" name="quantity" class="form-control">
                  <label class="control-label">Price</label>
                    <input type="text" name="price" class="form-control">
                  <label class="control-label">Plot No</label>
                    <select name="plotno" class="form-control" id="plotno">
                    <?php
                    $productresult=mysql_query("SELECT p.id as productid,ps.id as id ,ps.plotno as plotno , p.productname as productname from plotstatus ps inner join products p on p.id=ps.projectid where ps.id not in(SELECT plotid from plot_customers ) and ps.status='0' group by p.id , ps.id asc ");
                    while($productrow=mysql_fetch_array($productresult)){
                    ?>  
                    <option value="<?php echo $productrow['id']?>"><?php echo $productrow['productname']."  No ".$productrow['plotno']  ?></option>
                    <?php }?> 
                    </select>

                   </div>
                </div>
                </div>
              <div class="modal-footer"> <button type="submit" class="btn btn-primary" >Confirm</button> </div>
            </form>
            </div>

<script src="js/jquery.min.js"></script> 
<script src="js/jquery.ui.custom.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/jquery.peity.min.js"></script> 
<script src="js/matrix.interface.js"></script> 
<script src="js/matrix.popover.js"></script>
</body>

</html>
<script type="text/javascript">
 function check(){
 var a= document.getElementById('total').value;
 var b= document.getElementById('period').value;
 amt=parseInt(a)/parseInt(b);
 document.getElementById('installments').value= amt.toFixed(2);
 
 
 }	 

</script>