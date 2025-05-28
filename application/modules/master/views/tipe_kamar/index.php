<div id="page-content" class="p20 clearfix">

    <div class="panel panel-default">

        <div class="page-title clearfix">

            <h1>Master Tipe Kamar</h1>

            <div class="title-button-group">

                <?php echo modal_anchor(get_uri("master/tipe_kamar/modal_form"), "<i class='fa fa-plus-circle'></i> " . "Add Tipe Kamar", array("class" => "btn btn-primary", "title" => "Add Tipe Kamar")); ?>

            </div>

        </div>

        <div class="table-responsive">

            <table id="master_tipe_kamar-table" class="display" cellspacing="0" width="100%">            

            </table>

        </div>

    </div>

</div>



<script type="text/javascript">

    $(document).ready(function () {

        



        $("#master_tipe_kamar-table").appTable({

            source: '<?php echo_uri("master/tipe_kamar/list_data") ?>',

            columns: [

                {title: "Tipe Kamar", "class": "text-center text-bold"},

                {title: "Kelas Kamar"},

                {title: "Platform"},

                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w150"}

            ],
            xlsColumns: [0, 1, 2, 3, 4, 5,6]

        });

    });

</script>