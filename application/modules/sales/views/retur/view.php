<div id="page-content" class="clearfix">
    <div style="max-width: 1000px; margin: auto;">
        <div class="page-title clearfix mt15">
            <h1>INVOICE <?php echo get_order_id($invoice_info->code); ?>
                
            </h1>
                
        </div>

        <div id="invoice-status-bar">
            <?php $this->load->view("order/order_status_bar"); ?>
        </div>

        
<?php echo form_open(get_uri("sales/s_retur/retur_save"), array("id" => "order-form", "class" => "general-form", "role" => "form")); ?>

<input type="hidden" id="fid_cust" name="fid_cust" class="form-control" value=<?=$client_info->id?>>
<input type="hidden" id="fid_inv" name="fid_inv" class="form-control" value=<?php echo $invoice_info->id ?>>
<input type="hidden" id="code" name="code" class="form-control" value=#<?php echo getMaxId('sales_retur',"RET") ?>>
<input type="hidden" id="description" name="description" class="form-control" value=Retur&nbsp;<?php echo getMaxId('sales_retur',"RET") ?>>

        <div class="mt15">
            <div class="panel panel-default p15 b-t">
               <div class="clearfix p20">
                    <div class="col-sm-6">
                        <table style="font-size:14px;">
                            <tr>
                                <td width="200">Invoice Code</td>
                                <td width="20">:</td>
                                <td> <strong>#<?php echo $invoice_info->code ?></strong></td>
                            </tr>
                            <tr>
                                <td>Pay Date</td>
                                <td>:</td>
                                <td> <?php echo format_to_date($retur_info->pay_date,true) ?></td>
                            </tr>

                            <tr>
                                <td>Retur Date</td>
                                <td>:</td>
                                <td> <?php
                                    echo form_input(array(
                                        "id" => "retur_date",
                                        "name" => "retur_date",
                                        "class" => "form-control",
                                        "value" => date("Y/m/d"),
                                        "placeholder" => "Y/m/d",
                                        "data-rule-required" => true,
                                        "data-msg-required" => lang("field_required"),
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Alasan Retur</td>
                                <td>:</td>
                                <td> 
                                    <select name="alasan" id='dropdown' class="form-control">
                                        <option value="Rusak">Rusak</option>
                                        <option value="Cacat">Cacat</option>
                                        <option value="Tidak Sesuai Pesanan">Tidak Sesuai Pesanan</option>
                                        <option value="Alasan Lainnya">Alasan Lainnya</option>
                                     </select>
                                </td>
                            </tr>
                             <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Alasan Lainnya</td>
                                <td>:</td>
                                <td> 
                                    <input name="alasan_lainnya" type="text" id="textInput" class="form-control" /></textarea>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <table style="font-size:14px;" class="display">
                            <tr>
                                <td width="200">Retur Code</td>
                                <td width="20"> :</td>
                                <td> <strong>#<?php echo getMaxId('sales_retur',"RET") ?></strong></td>
                            </tr>
                            <tr>
                                <td width="200">Customers</td>
                                <td width="20"> :</td>
                                <td> <?php echo $client_info->code." - ".$client_info->name ?></td>
                            </tr>
                            <tr>
                                <td>Contacts</td>
                                <td>:</td>
                                <td > <?php echo ($client_info->mobile ? $client_info->mobile : $client_info->contact) ?></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td> <?php echo $invoice_info->email_to ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="table-responsive mt15 pl15 pr15">
                    <table id="order-item-table" class="display" width="100%">            
                    </table>
                </div>

                <table id="order-item-table" class="display dataTable no-footer" width="100%">            
                    <thead>
                        <tr role="row">
                            <th class="sorting_desc" tabindex="0" aria-controls="order-item-table" rowspan="1" colspan="1" aria-label="Item : activate to sort column ascending" aria-sort="descending">
                                Item 
                            </th>

                            <th class="text-right w30p sorting" tabindex="0" aria-controls="order-item-table" rowspan="1" colspan="1" aria-label="Retur Account : activate to sort column ascending">
                            Retur Account 
                            </th>

                            <th class="text-right w15p sorting" tabindex="0" aria-controls="order-item-table" rowspan="1" colspan="1" aria-label="Quantity: activate to sort column ascending">
                            Quantity
                            </th>

                            <th class="text-right w15p sorting" tabindex="0" aria-controls="order-item-table" rowspan="1" colspan="1" aria-label="Rate: activate to sort column ascending">
                            Rate
                            </th>

                            <th class="text-right w15p sorting" tabindex="0" aria-controls="order-item-table" rowspan="1" colspan="1" aria-label="Total: activate to sort column ascending">
                            Total
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <? foreach ($item_info as $data) { ?>
                        <tr role="row" class="odd">

                        <td><b><?=$data->title?></b><br><span><?=$data->description?></span></span></td>
                        
                        <td class=" text-right w30p">
                            <? echo form_dropdown("fid_retur", $retur_dropdown, "", "class='form-control'")?>
                        </td>

                        <td class=" text-right w15p"><input type="text" name="quantity" value="<?=to_decimal_format($data->quantity)?>" id="quantity" onkeyup="myFunction()" onkeypress="validate(event)" class="form-control">
                        </td>
                        
                        <td class=" text-right w15p"><input id = "rate" name="rate" class="form-control" onkeyup="myRate()" readonly="true" value = <?=$data->rate?>>
                        </td>

                        <td class=" text-right w15p"><input id = "total" name= "total" class = "form-control" onkeyup="myTotal()"  value="<?=$data->total?>">
                        </td>
                    </tr>
                    <?}?>

                </tbody></table>

               <div class="clearfix">
                    <div class="col-sm-8">

                    </div>
                    <div class="col-sm-4" id="order-total-section">
                        <table id="quotation-item-table" class="table display dataTable text-right strong table-responsive">     
    <tbody><tr>
        <td>Sub Total</td>
        <td><input type="text" name="sub_total" id="sub_total" value="<?php echo $invoice_info->sub_total ?>" class="form-control"></td>
    </tr>
            <tr>
            <td>PPN (10%)</td>
            <td><input type="text" name="ppn" id="ppn" value="<?php echo $invoice_info->ppn ?>" class="form-control"></td>
        </tr>
        <tr>
        <td>Grand Total</td>
        <td><input type="text" name="grand_total" id="grand_total" value="<?php echo $invoice_info->amount ?>"  class="form-control"></td>
    </tr>
</tbody></table>     </div>
                </div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" onclick="window.history.go(-1); return false;"><span class="fa fa-arrow-left"></span> Back</button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>


            </div>
        </div>


        

           
    </div>
</div>




<script>
    function myFunction(e){
       
        var pass = document.getElementById('quantity');
        var pass2 = document.getElementById('rate');
        //var pass3 = document.getElementById('sub_total');
        //document.getElementById("quantity");
        var input=parseInt(pass.value);
        var input2=parseInt(pass2.value);
        //var input3=parseInt(pass3.value);

        var result = input*input2;

            if (!isNaN(result)) {
                document.getElementById('total').value = result;
            }
        var result2 = document.getElementById('total').value = result;

        var result3 = result2;
        if (!isNaN(result3)) {
                document.getElementById('sub_total').value = result3;
            }

        var result4 = result3 * 0.1;
        if (!isNaN(result4)) {
                document.getElementById('ppn').value = result4;
            }

        var result5 = result4 + result3;
        if (!isNaN(result4)) {
                document.getElementById('grand_total').value = result5;
            }


        if(input<0 || input> <?=to_decimal_format($data->quantity)?>)
        alert("Jumlah retur lebih besar dari penjualan atau kurang dari 0");
    return;

            //var txtFirstNumberValue = document.getElementById('quantity').value;
           //var txtSecondNumberValue = document.getElementById('rate').value;
            
       // console.log('alert');
    }


    function validate(evt) {
              var theEvent = evt || window.event;

              // Handle paste
              if (theEvent.type === 'paste') {
                  key = event.clipboardData.getData('text/plain');
              } else {
              // Handle key press
                  var key = theEvent.keyCode || theEvent.which;
                  key = String.fromCharCode(key);
              }
              var regex = /[0-9]|\./;
              if( !regex.test(key) ) {
                theEvent.returnValue = false;
                if(theEvent.preventDefault) theEvent.preventDefault();
              }
            }

</script>

<script type="text/javascript">
    $(document).ready(function() {
    $('#textInput').prop( "disabled", true );
    $('#dropdown').change(function() {
    if( $(this).val() == 'Alasan Lainnya') {
            $('#textInput').prop( "disabled", false );
    } else {       
      $('#textInput').prop( "disabled", true );
    }
  });
 
});

</script>



<!--

<script type="text/javascript">
    // window.onload = updateInvoiceStatusBar();
    RELOAD_VIEW_AFTER_UPDATE = true;
    $(document).ready(function () {
        $("#order-item-table").appTable({
            source: '<?php echo_uri("sales/s_retur/item_list_data/". $invoice_info->id) ?>',
            order: [[0, "asc"]],
            hideTools: true,
            columns: [
                {title: '<?php echo lang("item") ?> '},
                {title: '<?php echo "Retur Account" ?> ', "class": "text-right w30p"},
                {title: '<?php echo lang("quantity") ?>', "class": "text-right w15p"},
                {title: '<?php echo lang("rate") ?>', "class": "text-right w15p"},
                {title: '<?php echo lang("total") ?>', "class": "text-right w15p"},
            ],
            onDeleteSuccess: function (result) {
                $("#order-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            },
            onUndoSuccess: function (result) {
                $("#order-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            }
        });

        
    });

    updateInvoiceStatusBar = function (invoiceId) {
        $.ajax({
            url: "<?php echo get_uri("sales/order/get_invoice_status_bar"); ?>/" + invoiceId,
            success: function (result) {
                if (result) {
                    $("#invoice-status-bar").html(result);
                }
            }
        });
    };


$(function() { 
    function groupTable($rows, startIndex, total){
    if (total === 0){
        return;
    }
    var i , currentIndex = startIndex, count=1, lst=[];
    var tds = $rows.find('td:eq('+ currentIndex +')');
    var ctrl = $(tds[0]);
    lst.push($rows[0]);
    for (i=1;i<=tds.length;i++){
        if (ctrl.text() ==  $(tds[i]).text()){
            count++;
            $(tds[i]).addClass('deleted');
            lst.push($rows[i]);
       }else{
            if (count>1){
                ctrl.attr('rowspan',count);
                groupTable($(lst),startIndex+1,total-1)
            }
            count=1;
            lst = [];
            ctrl=$(tds[i]);
            lst.push($rows[i]);
        }
        }
    }
    groupTable($('#order-item-table tr:has(td)'),0,3);
    $('#order-item-table .deleted').remove();
});

</script>-->

<?php
//required to send email 

load_css(array(
    "assets/js/summernote/summernote.css",
));
load_js(array(
    "assets/js/summernote/summernote.min.js",
));
?>

