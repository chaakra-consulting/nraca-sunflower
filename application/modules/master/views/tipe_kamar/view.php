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
                    "readonly" => true
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
                    "readonly" => true
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
                                    "", // name kosong karena tidak akan dipakai kirim data
                                    $acc_dropdown,
                                    $coa_type->id,
                                    "class='select2 form-control' disabled"
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
                                "readonly" => true
                            ]);
                            ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
</div>
<div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span>
        <?php echo lang('close'); ?>
    </button>
</div>
<?php echo form_close(); ?>