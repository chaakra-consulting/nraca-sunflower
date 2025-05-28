<div id="page-content" class="m20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1>Data Retur Penjualan</h1>
        </div>
       

        
        <div class="table-responsive" style="min-height: 500px">
            <table id="payments-table" class=" display dataTable no-footer" cellspacing="0" width="100%" >    
                       
            </table>
        </div>


    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $("#payments-table").appTable({
            source: '<?php echo_uri("sales/s_retur/list_data_retur") ?>',
            // order: [[1, "asc"]],
            columns: [
                {title: "INVOICE #"},
                {title: "RETUR DATE"},
                {title: "MEMO"},
                {title: "ALASAN RETUR"},
                {title: "QUANTITY","class": "text-center"},
                {title: "RATE", "class": "text-right"},
                {title: "TOTAL", "class": "text-right"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4,5,6,7],
            xlsColumns: [0, 1, 2, 3, 4,5,6,7]

        });

    });
</script>    
