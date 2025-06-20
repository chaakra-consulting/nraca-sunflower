<div id="page-content" class="clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1>Journal Entry</h1>
            <div class="title-button-group">
                <div class="btn-group" role="group">
                </div>
                <?php
                    echo modal_anchor(get_uri("accounting/journal_entry/modal_form_import"), "Import Penjualan Kamar", array("class" => "btn btn-success", "title" => "Import Data"));                
                ?>
                <?php
                    echo modal_anchor(get_uri("accounting/journal_entry/modal_form"), "<i class='fa fa-plus-circle'></i> " . "Add Item", array("class" => "btn btn-primary", "title" => "Add Item"));                
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="expenses-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#expenses-table").appTable({
            source: '<?php echo_uri("accounting/journal_entry/list_data") ?>',
            columns: [
                {title: "Transaction Code #"},
                {title: "Voucher Code"},
                {title: "Date"},
                {title: "Nama Tamu"},
                {title: "Tanggal Datang"},
                {title: "Tanggal Pergi"},
                {title: "Deskripsi"},
                // {title: "STATUS"}, // Add new column for status
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w150"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8], // Include status column in print
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8]   // Include status column in Excel export
        });
    });
</script>    
