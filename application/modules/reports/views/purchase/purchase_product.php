<div id="page-content" class="clearfix">
	<?php
    load_css(array(
        "assets/css/invoice.css"
    ));
    ?>
    <div style="max-width: 1000px; margin: auto;">
        
        <div id="invoice-status-bar" class="panel panel-default  p5 no-border m0">

        	<form action="" method="GET" role="form" class="general-form">
               <table class="table table-bordered">
                   <tr>
                        <td>
                        	<input type="text" class="form-control" id="start_date" name="start" autocomplete="off" placeholder="START DATE" value="<?php echo $_GET['start'] ?>">
                        </td>
						<td>
							<input type="text" class="form-control" id="end_date" name="end" autocomplete="off" placeholder="END DATE" value="<?php echo $_GET['end'] ?>">
                        </td>
                        <td>
	                        <select class="form-control" name="status">
                            <option value="" <?php if($status == "") echo 'selected'; ?>>--Semua Status--</option>
                                    <option value="1" <?php if($status === "1") echo 'selected'; ?>>Terverifikasi</option>
                                    <option value="0" <?php if($status === "0") echo 'selected'; ?>>Belum Terverifikasi</option>
	                        </select>
	                    </td>
                        <td>
                            <button type="submit" name="search" class="btn btn-default" value="2"><i class=" fa fa-search"></i> Filter</button>
                            <a href="#" name="print"  class="btn btn-default" onclick="tableToExcel('table-print', 'Lap_Pembelian')"><i class=" fa fa-file-excel-o"></i> Excel</a>

                        </td>
                   </tr>
               </table>
               </form>
        </div>

        <div class="mt15">
            <div class="panel panel-default p15 b-t">
            	<div>
            		<table class="table table-bordered" id="table-print">
                         <tr>
                            <th colspan="5">
                              <center><h3>Laporan Pembelian</h3>

                    <p><strong><?php echo $date_range ?></strong></p><p><strong><?php echo $paid ?></strong></p>
                            </th>

                        </tr>
            			<tr>
            				<th style="text-align: center;">Transaction Code</th>
            				<th style="text-align: center;">Nomer Akun</th>
                            <th>Rincian</th>
                            <th style="text-align: center; ">Status</th>
            				<th style="text-align: center;">Debet</th>
                            <th style="text-align: center;">Credit</th>
            			</tr>
            			<tbody>
            			<?php $jumlah = 0; $jumlahDebet = 0;  $jumlahCredit = 0;foreach($purchase_report as $row){ ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $row->code; ?></td>
                            <td style="text-align: center;"><?php echo $row->account_number_name; ?></td>
                            <td><?php echo $row->description; ?></td>
                            <td><?php echo $row->status; ?></td>
                            <td style="text-align: center;"><?php echo number_format(nsi_round($row->debet), 0, 0, '.'); $jumlahDebet += $row->debet; ?></td>
                            <td style="text-align: center;"><?php echo number_format(nsi_round($row->credit), 0, 0, '.'); $jumlahCredit += $row->credit;?></td>
            			</tr>
            			<?php } ?>
            			</tbody>
            			<tfoot>
            				<tr>
            					<th colspan="4" style="text-align: right;">TOTAL :</th>
								<th style="text-align: center;"><?php echo number_format(nsi_round($jumlahDebet), 0, 0, '.'); ?></th>
                                <th style="text-align: center;"><?php echo number_format(nsi_round($jumlahCredit), 0, 0, '.'); ?></th>
            				</tr>
            			</tfoot>
            		</table>
            	</div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        setDatePicker("#start_date");
        setDatePicker("#end_date");
    });
</script>