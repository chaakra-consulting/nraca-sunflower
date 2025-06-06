<!-- Styler -->
<style type="text/css">
.panel * {
	font-family: "Arial","​Helvetica","​sans-serif";
}
.fa {
	font-family: "FontAwesome";
}
.datagrid-header-row * {
	font-weight: bold;
}
.messager-window * a:focus, .messager-window * span:focus {
	color: blue;
	font-weight: bold;
}
.daterangepicker * {
	font-family: "Source Sans Pro","Arial","​Helvetica","​sans-serif";
	box-sizing: border-box;
}
.glyphicon	{font-family: "Glyphicons Halflings"}

	
</style>
<?php 
$periode_default = date("Y")."-01-01";
$periode_now = date("Y-m-d");
if(!empty($_GET['start']) && !empty($_GET['end'])){
    $periode_default = $_GET['start'];
    $periode_now = $_GET['end'];
}

$month=1;
$year=date('Y');
$type=$month;
$project=false;
$status="";

if ($_GET['status'] === "0" ||  $_GET['status'] === "1") {
	$status = $_GET['status'];
}

if(!empty($_GET['month'])){
    $month = $_GET['month'];
}
if(!empty($_GET['year'])){
    $year = $_GET['year'];
}
if(!empty($_GET['type'])){
    $type = $_GET['type'];
}

if(!empty($_GET['project'])){
    $project = $_GET['project'];
}
if($project=='')$project=false;

$type2=$type;
if($type==1){
	$type2=$month;
}else{
	$type2=($month+$type)-1;
}
$loop=$type2;
if($loop>12)$loop=12;
$ararymonth=array(
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Augustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        );

?>
<div id="page-content" class="clearfix">
    <div style="max-width: 1000px; margin: auto;">
        
        <div id="invoice-status-bar">
        	<div class="panel panel-default  p5 no-border m0">
            
            <span class="ml15">
	                <form action="" method="GET" role="form" class="general-form">
	               <table class="table table-bordered">
	                   <tr>
	                       <!--<td><label>Start Date</label></td>
	                       <td><input type="text" class="form-control" name="start" id="start" value="<?php echo $periode_default ?>" autocomplete="off">
	                       </td>
	                        <td><label>End Date</label></td>
	                       <td><input type="text" class="form-control" name="end" id="end" value="<?php echo $periode_now ?>" autocomplete="off"></td>-->
	                       <td><select class="form-control" name="month" >
			                <option value="1" <?php if ($month == 1) echo 'selected' ?>>January</option>
			                <option value="2" <?php if ($month == 2) echo 'selected' ?>>February</option>
			                <option value="3" <?php if ($month == 3) echo 'selected' ?>>March</option>
			                <option value="4" <?php if ($month == 4) echo 'selected' ?>>April</option>
			                <option value="5" <?php if ($month == 5) echo 'selected' ?>>May</option>
			                <option value="6" <?php if ($month == 6) echo 'selected' ?>>June</option>
			                <option value="7" <?php if ($month == 7) echo 'selected' ?>>July</option>
			                <option value="8" <?php if ($month == 8) echo 'selected' ?>>August</option>
			                <option value="9" <?php if ($month == 9) echo 'selected' ?>>September</option>
			                <option value="10" <?php if ($month == 10) echo 'selected' ?>>October</option>
			                <option value="11" <?php if ($month == 11) echo 'selected' ?>>November</option>
			                <option value="12" <?php if ($month == 12) echo 'selected' ?>>December</option>
			            </select></td></td>
	                        <td>
	                        	<select class="form-control" name="year">
	                        		<?php for($a=date('Y');$a>=2017;$a--){?>

	                        			<option value="<?php echo $a?>" <?php if ($year == $a) echo 'selected' ?>><?php echo $a?></option>
	                        		<?php } ?>
	                        	</select>
	                        </td>

	                        <td><select name="type" class="form-control"><option value="1" <?php if ($type == 1) echo 'selected' ?>>1 Month</option><option value="3" <?php if ($type == 3) echo 'selected' ?>>3 Month</option><option value="6" <?php if ($type == 6) echo 'selected' ?>>6 Month</option><option value="12" <?php if ($type == 12) echo 'selected' ?>>12 Month</option></select></td>
							<td>
								<select class="form-control" name="status">
									<option value="" <?php if($status == "") echo 'selected'; ?>>--Semua Status--</option>
									<option value="1" <?php if($status === "1") echo 'selected'; ?>>Terverifikasi</option>
									<option value="0" <?php if($status === "0") echo 'selected'; ?>>Belum Terverifikasi</option>
								</select>
							</td>
	                        <td>
	                            <button type="submit" name="search" class="btn btn-default" value="1"><i class=" fa fa-search"></i> Filter</button>
	                           <a href="#" name="print"  class="btn btn-default" onclick="tableToExcel('table-print', 'laba_rugi')"><i class=" fa fa-file-excel-o"></i> Excel</a>

	                        </td>
	                   </tr>
	               </table>
	               </form>
                </span>

            </div>
        </div>

        

        <div class="mt15">
            <div class="panel panel-default p15 b-t">
                

                <div class="table-responsive mt15 pl15 pr15">


	
	<hr>



<table  class="table table-bordered" id="table-print" style="">
    <tr>
        <th colspan="<?php echo (($loop-$month)+3)?>"><p style="text-align:center; font-size: 15pt; font-weight: bold;"> Laporan Laba Rugi Periode <?php echo $ararymonth[($month-1)]." - ".$ararymonth[($loop-1)];  ?> <?php echo $year?></p>
</th>
<tr><td colspan="<?php echo (($loop-$month)+3)?>"><b>Penjualan</b> </td></tr>
	<tr style="background: lightgrey">
		<th style="width:5%; vertical-align: middle; text-align:center" > No. </th>
		<th style="width:75%; vertical-align: middle; text-align:center">Keterangan </th>
		<?php for ($i=($month-1); $i <$loop ;$i++) { ?>
				<th style="width:20%; vertical-align: middle; text-align:center"><?php echo $ararymonth[$i]?> </th>
		<?php }	?> 

	</tr>
	

	<?php
	$no_dapat = 1;
	$jml_dapat =array(0,0,0,0,0,0,0,0,0,0,0,0,0);
	$jml_keluar =array(0,0,0,0,0,0,0,0,0,0,0,0,0);

	foreach ($data_dapat as $row) {
		echo '
				<tr>
					<td class="h_tengah"> '.$row->account_number.' </td>
		';
		if(strlen($row->account_number)==4){

			echo '<td><b>'.$row->account_name.'</b></td>';
				
		}else if(strlen($row->account_number)==4){
				echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row->account_name.'</td>';
		}
		for ($i=($month-1); $i <$loop ;$i++) {

			$jml_akun = $this->Profitloss_model->get_jml_akun_month($row->id,($i+1),$year,$project,false,$status);
			$jumlah = $jml_akun->jum_debet + $jml_akun->jum_kredit;
				if(strlen($row->account_number)==4){
					echo '<td class="h_kanan"><b>'.number_format(nsi_round($jumlah), 0, 0, '.').'</b></td>';
						if ($row->akun == 'pengeluaran') {
							$jml_dapat[$i] -= $jumlah;
						} else {
							$jml_dapat[$i] += $jumlah;
						}
					}else{
						echo '<td class="h_kanan">'.number_format(nsi_round($jumlah), 0, 0, '.').'</td>';
					}
			}
			echo '</tr>';
			$no_dapat++;
		
	}
	?>
	<tr style="background: lightgrey">
		<td colspan="2" class="h_kanan"> <b>Penjualan Bersih</b></td>
		<?php for ($i=($month-1); $i <$loop ;$i++) {?>
		<td class="h_kanan"><b><?php $jml_p = $jml_dapat[$i];
		echo number_format(nsi_round($jml_p), 0, 0, '.')   ?></b></td>
	<?php } ?>
	</tr>

<!--<tr><td colspan="<?php echo (($loop-$month)+2)?>"><b>Beban pokok Penjualan</b></td></tr>


	

	<?php
	$no_beban = 1;
	$jml_beban =array(0,0,0,0,0,0,0,0,0,0,0,0,0);
	foreach ($data_beban_pokok as $row) {
		echo '
				<tr>
					<td class="h_tengah"> '.$no_beban.' </td>
		';
		
		if(strlen($row->account_number)==4){
			echo '<td><b>'.$row->account_name.'</b></td>';
				
			}else if(strlen($row->account_number)==4){
				echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row->account_name.'</td>';
			}

			else if(strlen($row->account_number)==4){
					echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row->account_name.'</td>';
			}
			for ($i=($month-1); $i <$loop ;$i++) {
				$jml_akun = $this->Profitloss_model->get_jml_akun_month($row->id,($i+1),$year,$project,false,$status);
				$jumlah = $jml_akun->jum_debet + $jml_akun->jum_kredit;
				if(strlen($row->account_number)==4){
					echo '<td class="h_kanan"><b>'.number_format(nsi_round($jumlah), 0, 0, '.').'</b></td>';
						$jml_beban[$i] += $jumlah;
					}else{
						echo '<td class="h_kanan">'.number_format(nsi_round($jumlah), 0, 0, '.').'</td>';

					}
		}
		echo '</tr>';
		$no_beban++;
	}
	?>
	<tr style="background: lightgrey">
		<td colspan="2" class="h_kanan"> <b>Jumlah Beban Pokok Penjualan</b></td>
		<?php for ($i=($month-1); $i <$loop ;$i++) {?>
		<td class="h_kanan"><b><?php $jml_p = $jml_beban[$i];
		echo number_format(nsi_round($jml_p), 0, 0, '.')  ?></b></td>
	<?php } ?>
	</tr>-->

	



<tr><td colspan="<?php echo (($loop-$month)+2)?>"><b>Biaya dan Beban</b></td></tr>

	<?php 
		$no=1;
		$jml_beban_operasional =array(0,0,0,0,0,0,0,0,0,0,0,0,0);
		foreach ($data_biaya as $row) {
			echo '
				<tr>
					<td class="h_tengah"> '.$row->account_number.' </td>
		';
		
		if(strlen($row->account_number)==4){
			echo '<td><b>'.$row->account_name.'</b></td>';
				
			}else if(strlen($row->account_number)==4){
				echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row->account_name.'</td>';
			}else if(strlen($row->account_number)==4){
				echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$row->account_name.'</b></td>';
			}else if(strlen($row->account_number)==4){
				echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row->account_name.'</td>';
			}else if(strlen($row->account_number)==4){
				echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row->account_name.'</td>';
			}else if(strlen($row->account_number)==4){
				echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row->account_name.'</td>';
			}

			for ($i=($month-1); $i <$loop ;$i++) {
				$jml_akun = $this->Profitloss_model->get_jml_akun_month($row->id,($i+1),$year,$project,$row->account_number,$status);
				//echo $this->db->last_query();exit();
				$jumlah_beban = $jml_akun->jum_debet + $jml_akun->jum_kredit;
				if(strlen($row->account_number)==4){
					echo '<td class="h_kanan"><b>'.number_format(nsi_round($jumlah_beban), 0, 0, '.').'</b></td>';
						$jml_beban_operasional[$i] += $jumlah_beban;
					}else{
						echo '<td class="h_kanan">'.number_format(nsi_round($jumlah_beban), 0, 0, '.').'</td>';
						//$jml_beban_operasional[$i] += $jumlah_beban;
					}
		}
		echo '</tr>';
		$no_beban++;
	}
	?>
	<tr style="background: lightgrey">
		<td colspan="2" class="h_kanan"><b>Total Beban operasional</b></td>
		<?php for ($i=($month-1); $i <$loop ;$i++) {?>
		<td class="h_kanan"><b><?php $jml_p = $jml_beban_operasional[$i];
		echo number_format(nsi_round($jml_p), 0, 0, '.')   ?></b></td>
	<?php } ?>
	</tr>
	<!--<tr style="background: lightgrey">
		<td colspan="2" class="h_kanan"><b>Pendapatan Operasi (Operating Profit)</b></td>
		<?php for ($i=($month-1); $i <$loop ;$i++) {?>
		<td class="h_kanan"><b><?php $jml_p = ($jml_dapat[$i]-$jml_beban[$i]-$jml_beban_operasional[$i]);
		echo number_format(nsi_round($jml_p))   ?></b></td>
	<?php } ?>
	</tr>-->
	<!--<tr><td colspan="<?php echo (($loop-$month)+2)?>"><b>Pendapatan non Operasional</b></td></tr>



	

	<?php
	$no_dapat = 1;
	$jml_dapat_non =array(0,0,0,0,0,0,0,0,0,0,0,0,0);
	foreach ($dapat_non_op as $row) {
		echo '
				<tr>
					<td class="h_tengah"> '.$no_dapat.' </td>
		';
		if(strlen($row->account_number)==4){

			echo '<td><b>'.$row->account_name.'</b></td>';
				
			}else if(strlen($row->account_number)==4){
				echo '<td>-'.$row->account_name.'</td>';
			}
			else if(strlen($row->account_number)==4){
					echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row->account_name.'</td>';
			}
		for ($i=($month-1); $i <$loop ;$i++) {

			$jml_akun = $this->Profitloss_model->get_jml_akun_month($row->id,($i+1),$year,$project,false,$status);
			$jumlah = $jml_akun->jum_debet + $jml_akun->jum_kredit;
				if(strlen($row->account_number)==4){
					echo '<td class="h_kanan"><b>'.number_format(nsi_round($jumlah), 0, 0, '.').'</b></td>';
						$jml_dapat_non[$i] += $jumlah;
					}else{
						echo '<td class="h_kanan">'.number_format(nsi_round($jumlah), 0, 0, '.').'</td>';
					}
			}
			echo '</tr>';
			$no_dapat++;
		
	}
	//print_r($jml_dapat);exit();
	?>
	<tr style="background: lightgrey">
		<td colspan="2" class="h_kanan"><b> Jumlah Pendapatan Non Operational</b></td>
		<?php for ($i=($month-1); $i <$loop ;$i++) {?>
		<td class="h_kanan"><b><?php $jml_p = $jml_dapat_non[$i];
		echo number_format(nsi_round($jml_p))   ?></b></td>
	<?php } ?>
	</tr>-->

<tr><td colspan="<?php echo (($loop-$month)+2)?>"><b>Pendapatan & Beban Non Operasional</b></td></tr>
	<?php 
		$no=1;
		$jml_beban_non =array(0,0,0,0,0,0,0,0,0,0,0,0,0);
		foreach ($data_biaya_other as $row) {
			echo '
				<tr>
					<td class="h_tengah"> '.$row->account_number.' </td>
		';
		
		if(strlen($row->account_number)==4){
			echo '<td><b>'.$row->account_name.'</b></td>';
				
			}else if(strlen($row->account_number)==4){
				echo '<td>-'.$row->account_name.'</td>';
			}else if(strlen($row->account_number)==4){
				echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row->account_name.'</td>';
			}
			for ($i=($month-1); $i <$loop ;$i++) {
				$jml_akun = $this->Profitloss_model->get_jml_akun_month($row->id,($i+1),$year,$project,false,$status);
				$jumlah = $jml_akun->jum_debet + $jml_akun->jum_kredit;
				if(strlen($row->account_number)==4){
					echo '<td class="h_kanan"><b>'.number_format(nsi_round($jumlah), 0, 0, '.').'</b></td>';
						$jml_beban_non[$i] += $jumlah;
				}else{
						echo '<td class="h_kanan">'.number_format(nsi_round($jumlah), 0, 0, '.').'</td>';
				}
		}
		echo '</tr>';
		$no_beban++;
	}
	?>
	<tr style="background: lightgrey">
		<td colspan="2" class="h_kanan"><b> Total Beban Non Operasional</b></td>
		<?php for ($i=($month-1); $i <$loop ;$i++) {?>
		<td class="h_kanan"><b><?php $jml_p = $jml_beban_non[$i];
		echo number_format(nsi_round($jml_p), 0, 0, '.')   ?></b></td>
	<?php } ?>
	</tr>
	<tr style="background: lightgrey">
		<td colspan="2" class="h_kanan"><b>Laba/Rugi Bersih Sebelum Pajak</b></td>
		<?php for ($i=($month-1); $i <$loop ;$i++) {?>
		<td class="h_kanan"><b><?php $jml_p = $jml_dapat[$i]-$jml_beban_operasional[$i]-$jml_beban_non[$i];
		echo number_format(nsi_round($jml_p), 0, 0, '.')   ?></b></td>
	<?php } ?>
	</tr>
	<tr style="background: lightgrey">
		<td colspan="2" class="h_kanan"><b>Pajak PPH 25 Badan</b></td>
		<?php for ($i=($month-1); $i <$loop ;$i++) {?>
		<td class="h_kanan"><b><?php $jml_p = ($jml_dapat[$i]-$jml_beban_operasional[$i]-$jml_beban_non[$i])*0.25;
		echo number_format(nsi_round($jml_p), 0, 0, '.')   ?></b></td>
	<?php } ?>
	</tr>
	<tr style="background: lightgrey">
		<td colspan="2" class="h_kanan"><b>Laba/Rugi Bersih Setelah Pajak</b></td>
		<?php for ($i=($month-1); $i <$loop ;$i++) {?>
		<td class="h_kanan"><b><?php $jml_p = ($jml_dapat[$i]-$jml_beban_operasional[$i]-$jml_beban_non[$i])-(($jml_dapat[$i]-$jml_beban_operasional[$i]-$jml_beban_non[$i])*0.25);
		echo number_format(nsi_round($jml_p), 0, 0, '.')   ?></b></td>
	<?php } ?>
	</tr>
	<!--<tr style="background: lightgrey">
		<td colspan="2" class="h_kanan"><b>Laba Kotor (Gross Profit)</b></td>
		<?php for ($i=($month-1); $i <$loop ;$i++) {?>
		<td class="h_kanan"><b><?php $jml_p = ($jml_dapat[$i]-$jml_beban[$i]);
		echo number_format(nsi_round($jml_p))   ?></b></td>
	<?php } ?>
	</tr>
	<tr style="background: lightgrey">
		<td colspan="2" class="h_kanan"><b>Net Profit</b></td>
		<?php for ($i=($month-1); $i <$loop ;$i++) {?>
		<td class="h_kanan"><b><?php $jml_p = ($jml_dapat[$i]+$jml_dapat_non[$i])-$jml_beban[$i]-$jml_beban_operasional[$i]-$jml_beban_non[$i];
		echo number_format(nsi_round($jml_p), 0, 0, '.')   ?></b></td>
	<?php } ?>
	</tr>-->
</table>



</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {

setDatePicker("#start");
   setDatePicker("#end");

});
</script>