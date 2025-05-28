<div id="page-content" class="p20 clearfix">
    <?php
    load_css(array(
        "assets/css/invoice.css",
    ));
    ?>

    <div class="invoice-preview">
            
            <?php
        

        if ($show_close_preview)
            echo "<div class='text-center'>" . anchor("sales/s_retur/data_retur", lang("close_preview"), array("class" => "btn btn-default round")) . "</div>"
            ?>

        <div class="bg-white mt15 p30">
            <div class="col-md-12">
                <div class="ribbon"><span class="label label-danger large">RETUR</span></div>
            </div>

            <div style=" margin: auto;">
    <center><span style="font-size:16px;font-weight: bold;"><u>RETUR</u><br><?=$retur_info->sales_code?></span></center>
    <table style="color: #444; width: 100%;">
    <tr>
        <td style="width: 45%; vertical-align: top;">
            <img src="<?=base_url();?>files/system/_file5c81f0c8a4fd4-invoice-logo.png" />        </td>
        <td style="width: 20%;">
        </td>
        <td style="width: 35%; vertical-align: top; text-align: right"><span style="font-size:20px; font-weight: bold;background-color: #d9534f; color: #fff;"> <?=$retur_info->sales_code?>&nbsp;</span>
<div style="line-height: 10px;"></div>
<span></span><br />
<span>Retur Date: <?=format_to_date($retur_info->retur_date);?></span>
<br>
<span>#INV REF : 
        <strong><?=$retur_info->invoices_code?></strong></span>
        <br>
<span>Alasan Retur : 
        <strong><?=$retur_info->alasan?></strong></span>        </td>
    </tr>
    <tr>
        <td style="padding: 5px;"></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><div><b>PT Danu Nusantara</b></div>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">Makassar            <div style="line-height: 1px;"> </div>
        <br />Phone: 0411                <div style="line-height: 2px;"> </div>
        <br />Website: <a style="color:#666; text-decoration: none;" href="http://www.danunusantara.com/">http://www.danunusantara.com/</a>
    </span>        </td>
        <td></td>
        <td><div><b>RETUR TO</b></div>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>
<div style="line-height: 3px;"> </div>
<strong>    <span><?php echo $retur_info->code." - ".$retur_info->name ?></span>
 </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
            <div><?php echo ($retur_info->mobile ? $retur_info->mobile : $retur_info->contact) ?>                                                <br><?php echo $retur_info->email ?>            


        </div>
</span>        </td>
    </tr>
</table></div>

<br />

<table style="width: 100%; color: #444;">            
    <tr style="font-weight: bold; background-color: #2AA384; color: #fff;  ">
        <th style="width: 45%; border-right: 1px solid #eee;"> Item </th>
        <th style="text-align: center;  width: 15%; border-right: 1px solid #eee;"> Quantity</th>

        <th style="text-align: right;  width: 20%; border-right: 1px solid #eee;"> Rate</th>
        <th style="text-align: right;  width: 20%; "> Total</th>
    </tr>
         <? foreach ($item_info as $data) { ?>  
        <tr style="background-color: #f4f4f4; ">
            <td style="width: 45%; border: 1px solid #fff; padding: 10px;"><?=$data->title?>  <br />
                <span style="color: #888; font-size: 90%;"><?=$data->description?></span>

            </td>
            <td style="text-align: center; width: 15%; border: 1px solid #fff;"> <?=$data->quantity?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff;"> <?=$data->rate?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff;"> <?=$data->amount?></td>
        </tr><?}?>
   
       <tr>
        <td colspan="3" style="text-align: right;">Total</td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
            <?=$data->amount?>        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: right;">PPN 10%</td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
            <?  $rate=$data->amount;
                $total=$data->grand_total;
    
                echo $total-$rate;

            ?>       </td>
    </tr>
            <tr>
            <td colspan="3" style="text-align: right;">Grand Total</td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
               <?=$data->grand_total?>           </td>
        </tr>   
    
</table>

<span style="color:#444; line-height: 14px;">
    <p><u><b>TRANSFER</b></u><br></p><p>PT. Danu Nusantara (BNI)</p><p>No. Rek : 2188833386&nbsp; &nbsp; </p><p>PT. Danu Nusantara (Mandiri)<br></p><p>No. Rek : 2188833386&nbsp; <br></p><p><br></p><p align="center">&nbsp; TTD,</p><p align="center"><br></p><p align="center">Sales Manager</p></span>

        </div>

    </div>
</div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#payment-amount").change(function () {
            var value = $(this).val();
            $(".payment-amount-field").each(function () {
                $(this).val(value);
            });
        });
    });



</script>
<script type="text/javascript">
<!--
window.print();
//-->
</script>