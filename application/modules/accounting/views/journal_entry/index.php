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
        <div class="container-fluid mb-3">
            <div class="row g-2 align-items-end">
                <form action="" method="GET" role="form" class="form-inline d-flex justify-content-end gap-2 mb-3">
                    <input type="text" class="form-control" id="start" name="start" autocomplete="off" placeholder="START DATE" value="<?php echo $start; ?>">
                    <input type="text" class="form-control" id="end" name="end" autocomplete="off" placeholder="END DATE" value="<?php echo $end; ?>">
                    <select class="form-control form-control-sm" name="acc_filter" style="width: 175px;">
                            <option value="" <?php if($acc_filter == "") echo 'selected'; ?>>--Semua Akun--</option>
                                    <?php foreach($acc_dropdown as $key => $acc){ ?>
                                        <option value="<?php echo $key; ?>" <?php if($key == $acc_filter) echo 'selected'; ?>><?php echo $acc; ?></option>
                                    <?php } ?>
	                </select>
                    <button type="submit" name="search" class="btn btn-default" value="1"><iclass="fa fa-search"></i> Filter</button>
                </form>
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
    setDatePicker("#start");
    setDatePicker("#end");

    const defaultSearch = '<?php echo addslashes($defaultSearch); ?>'; // Nilai pencarian default dari PHP

    const table = $("#expenses-table").appTable({
        source: '<?php echo_uri("accounting/journal_entry/list_data?start=" . $start . "&end=" . $end . "&acc_filter=" . $acc_filter) ?>',
        order: [[1, 'desc']],
        columns: [
            {title: "Transaction Code #"},
            {title: "Date"},
            {title: "Nama Tamu"},
            {title: "Tanggal Datang"},
            {title: "Tanggal Pergi"},
            {title: "Deskripsi"},
            {title: "Total"},
            {title: '<i class="fa fa-bars"></i>', "class": "text-center option w150"}
        ],
        filterParams: {
            start: $("#start").val(),
            end: $("#end").val(),
            status: $("#status").val()
        },
        onInitComplete: function () {
            const dt = $('#expenses-table').DataTable();
            dt.search(defaultSearch).draw();

            // Tangkap event pencarian
            dt.on('search.dt', function () {
                const searchValue = dt.search();
                $.ajax({
                    url: '<?php echo_uri("accounting/journal_entry/save_search"); ?>',
                    type: 'POST',
                    data: { search: searchValue },
                    success: function (response) {
                        console.log('Search saved:', searchValue);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error saving search:', error);
                    }
                });
            });
        }
    });

    $("#filter-btn").click(function () {
        table.appTable({
            reload: true,
            filterParams: {
                start: $("#start").val(),
                end: $("#end").val(),
            }
        });
    });
});
</script>  