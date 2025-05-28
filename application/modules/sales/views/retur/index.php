<div id="page-content" class="m20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1>Retur Penjualan</h1>
            <div class="title-button-group">
              <!--  <div class="btn-group" role="group">
                    <?php
                    echo modal_anchor(get_uri("sales/s_retur/add_receipt"), "<i class='fa fa-plus-circle'></i> " . "Add Sales Retur", array("class" => "btn btn-primary", "title" => "Add Sales Retur","data-modal-lg"=>1));
                
                ?>
                </div>-->

                <div class="btn-group" role="group">
                    <a href="<?=base_url();?>sales/s_retur/data_retur" class="btn btn-primary" title="Data Retur Penjualan"><i class='fa fa-eye'></i> Data Retur Penjualan </a>
                </div>
               
            </div>
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
            source: '<?php echo_uri("sales/s_retur/list_data") ?>',
           // order: [[2, "desc"]],
            columns: [
                {title: "INVOICE #"},
                {title: "NAMA KASIR"},
                {title: "TANGGAL"},
                {title: "TOTAL", "class": "text-right"},
                {title: '<i class="fa fa-exchange"></i>', "class": "text-center option w50"}
            ],
            printColumns: [0, 1, 2, 3],
            xlsColumns: [0, 1, 2, 3]

        });

    });
</script>    
