<?php echo form_open(get_uri("master/tipe_kamar/add_tipe_kamar"), array("id" => "master_customers-form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix">

    <div class="col-md-12">
        <div class="form-group">
            <label for="tipe_kamar" class=" col-md-3">Tipe Kamar</label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "tipe_kamar",
                    "name" => "tipe_kamar",
                    "class" => "form-control",
                    "placeholder" => 'Tipe Kamar',
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="kelas_kamar" class=" col-md-3">Kelas Kamar</label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "kelas_kamar",
                    "name" => "kelas_kamar",
                    "class" => "form-control",
                    "placeholder" => 'Kelas Kamar',
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3">Entry Accounts & Tarif</label>
            <div class="col-md-9">
                <div id="entry-tarif-container">
                    <div class="entry-tarif-row row mb-2 align-items-center">
                        <div class="col-md-6">
                            <?php
                            echo form_dropdown("coa_type_id[]", $acc_dropdown, "", "class='select2 validate-hidden form-control'");
                            ?>
                        </div>
                        <div class="col-md-5">
                            <?php
                            echo form_input(array(
                                "name" => "jumlah[]",
                                "class" => "form-control",
                                "placeholder" => 'Tarif',
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm remove-entry-tarif"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-entry-tarif" class="btn btn-success btn-sm mt-2"><i class="fa fa-plus-circle"></i> Tambah</button>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">

    <button class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span>
        <?php echo lang('close'); ?>
    </button>

    <button id="form-submit" type="button" class="btn btn-primary "><span class="fa fa-check-circle"></span>
        <?php echo lang('save'); ?>
    </button>

</div>

<?php echo form_close(); ?>

<script type="text/javascript">

$(document).ready(function () {
    $("#master_customers-form .select2").select2();

    $("#master_customers-form").appForm({
        onSuccess: function (result) {
            if (result.success) {
                $("#master_customers-table").appTable({ newData: result.data, dataId: result.id });
            }
        }
    });

    $("#master_customers-form input").keydown(function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            $("#master_customers-form").trigger('submit');
        }
    });

    $("#form-submit").click(function () {
        $("#master_customers-form").trigger('submit');
    });

    // Tambah baris baru Entry Account & Tarif
    $("#add-entry-tarif").click(function () {
        var newRow = `
                <div class="entry-tarif-row row mt-2 mb-2 align-items-center">
                        <div class="col-md-6">
                            <?php
                            echo form_dropdown("coa_type_id[]", $acc_dropdown, "", "class='select2 validate-hidden form-control'");
                            ?>
                        </div>
                        <div class="col-md-5">
                            <?php
                            echo form_input(array(
                                "name" => "jumlah[]",
                                "class" => "form-control",
                                "placeholder" => 'Tarif',
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm remove-entry-tarif"><i class="fa fa-trash"></i></button>
                        </div>
                </div>
        `;
        $("#entry-tarif-container").append(newRow);
        $("#entry-tarif-container .select2").select2(); // inisialisasi ulang select2
    });

    // Hapus baris Entry Account & Tarif
    $(document).on("click", ".remove-entry-tarif", function () {
        $(this).closest(".entry-tarif-row").remove();
    });
});

</script>