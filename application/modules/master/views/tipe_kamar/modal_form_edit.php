<?php echo form_open(get_uri("master/tipe_kamar/save"), array("id" => "master_customers-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <div class="form-group">
            <label for="tipe_kamar" class=" col-md-3">Tipe Kamar</label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "tipe_kamar",
                    "name" => "tipe_kamar",
                    "class" => "form-control",
                    "value" => isset($model_info[0]) ? $model_info[0]->tipe_kamar : "",
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
                    "value" => isset($model_info[0]) ? $model_info[0]->kelas_kamar : "",
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
                    <?php foreach ($model_info[0]->coa_types as $index => $coa_type): ?>
                        <div class="entry-tarif-row mb-2 d-flex align-items-center">
                            <div class="col-md-6">
                                <?php
                                // Dropdown COA
                                echo form_dropdown(
                                    "coa_type_id[]", // name
                                    $acc_dropdown,   // options (array: id => name)
                                    $coa_type->id,   // selected value
                                    "class='select2 validate-hidden form-control'"
                                );
                                ?>
                            </div>
                            <div class="col-md-5">
                            <?php
                            $jumlah_value = isset($jumlah[$coa_type->id]) ? $jumlah[$coa_type->id] : "";
                            // Input Jumlah (Tarif)
                            echo form_input([
                                "name" => "jumlah[]",
                                "class" => "form-control",
                                "placeholder" => "Tarif",
                                "value" => $jumlah_value,
                                "data-rule-required" => "true",
                                "data-msg-required" => lang("field_required"),
                                // "style" => "width: 45%; margin-left: 10px;"
                            ]);
                            ?>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm remove-entry-tarif"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="add-entry-tarif" class="btn btn-success btn-sm mt-2"><i class="fa fa-plus-circle"></i> Tambah</button>
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
        $("#code").focus();


        $("#form-submit").click(function () {
            $("#master_customers-form").trigger('submit');
        });

        // Tambah baris baru Entry Account & Tarif
        $("#add-entry-tarif").click(function () {
            var newRow = `
                <div class="entry-tarif-row row mt-2 mb-2 align-items-center">
                    <div class="col-md-6">
                        <select name="coa_type_id[]" class="select2 validate-hidden form-control">
                            <?php foreach ($acc_dropdown as $key => $val): ?>
                                <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="jumlah[]" class="form-control" placeholder="Tarif" required data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>">
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