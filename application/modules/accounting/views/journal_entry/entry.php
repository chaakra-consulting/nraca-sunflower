<div id="page-content" class="clearfix m20">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1>Journal Entry</h1>
            <div class="title-button-group">
                <div class="btn-group" role="group">
                </div>
                <?php
                echo modal_anchor(get_uri("accounting/journal_entry/modal_form_edit"), "<i class='fa fa-pencil'></i> " . "Edit", array("class" => "btn btn-default", "title" => "Edit","data-post-id" => $info_header->id));
                ?>
            </div>
        </div>
        <div class="container">

            <div class="clearfix m20">
                
                <table class="table" style="font-size: 16px;font-weight: 500">
                    <tr>
                        <td width="200px">Transaction Code</td>
                        <td>:</td>
                        <td><?php echo $info_header->code ?></td>
                    </tr>
                    <tr>
                        <td width="200px">Voucher Code</td>
                        <td>:</td>
                        <td><?php echo $info_header->voucher_code ?></td>
                    </tr>
                    <tr>
                        <td width="200px">Date</td>
                        <td>:</td>
                        <td><?php echo format_to_date($info_header->date,false) ?></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #ddd; ">
                        <td width="200px">Description</td>
                        <td>:</td>
                        <td><?php echo $info_header->description ?></td>
                    </tr>
                    <?php if ($data['jenis_entry'] == 'Penjualan Kamar') { ?>
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td width="200px">Kode Kamar</td>
                            <td>:</td>
                            <td>
                                <?php 
                                echo (!empty($tipe_kamar) && isset($tipe_kamar[0]->tipe_kamar)) 
                                    ? htmlspecialchars($data['no_kamar'] . ' / ' . $tipe_kamar[0]->tipe_kamar) 
                                    : 'N/A';
                                ?>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td width="200px">Kelas Kamar</td>
                            <td>:</td>
                            <td>
                                <?php 
                                // Safely access tipe_kamar and kelas_kamar
                                echo (!empty($tipe_kamar) && isset($tipe_kamar[0]->kelas_kamar)) 
                                    ? htmlspecialchars($tipe_kamar[0]->kelas_kamar) 
                                    : 'N/A';
                                ?>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td width="200px">Nama Tamu</td>
                            <td>:</td>
                            <td>
                                <?php 
                                // Safely access nama_tamu from JSON data
                                echo isset($data['nama_tamu']) ? htmlspecialchars($data['nama_tamu']) : 'N/A';
                                ?>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td width="200px">Tanggal Datang</td>
                            <td>:</td>
                            <td>
                                <?php 
                                // Safely access and format tanggal_datang
                                echo isset($data['tanggal_datang']) ? format_to_date($data['tanggal_datang'], false) : 'N/A';
                                ?>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td width="200px">Tanggal Pergi</td>
                            <td>:</td>
                            <td>
                                <?php 
                                // Safely access and format tanggal_pergi
                                echo isset($data['tanggal_pergi']) ? format_to_date($data['tanggal_pergi'], false) : 'N/A';
                                ?>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #ddd;">
                        <td width="200px">Lama Inap</td>
                        <td>:</td>
                        <td>
                            <?php 
                            // Safely calculate the duration of stay
                            if (isset($data['tanggal_datang']) && isset($data['tanggal_pergi'])) {
                                try {
                                    $tanggal_datang = new DateTime($data['tanggal_datang']);
                                    $tanggal_pergi = new DateTime($data['tanggal_pergi']);
                                    $interval = $tanggal_datang->diff($tanggal_pergi);
                                    $lama_inap = $interval->days; // Get the difference in days
                                    echo $lama_inap . ' hari'; // Output: e.g., "2 hari"
                                } catch (Exception $e) {
                                    echo 'N/A'; // Handle invalid date formats
                                }
                            } else {
                                echo 'N/A'; // Handle missing dates
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                    </table>
                            <?php echo modal_anchor(get_uri("accounting/journal_entry/modal_form_detail"), "<i class='fa fa-plus-circle'></i> " . "Add Row", array("class" => "btn btn-primary", "title" => "Add Row","data-post-id" => $info_header->id)); ?>
        
            </div>
        </div>
        
        <div class="table-responsive" style="border: 1px solid #ddd; ">

            <table id="journal-table" class="display" cellspacing="0" width="100%"> 
<!-- 
                <tfoot>
                    <tr>
                        <th colspan="4" style="text-align:right">Total:</th>
                        <th></th>
                    </tr>
                </tfoot>     -->       
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var id = '<?php echo $this->uri->segment(4); ?>';
    $(document).ready(function () {

        $("#journal-table").appTable({
            source: '<?php echo_uri("accounting/journal_entry/list_data_entry/") ?>'+id,
            // order: [[1, "asc"]],
            columns: [
                {title: "ACCOUNT NAME"},
                {title: "REF #"},
                {title: "DESCRIPTION"},
                {title: "DEBET"},
                {title: "CREDIT"},
                {title: "METODE"},
                {title: "STATUS"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w150"}
            ]

        });


    });
</script>    
