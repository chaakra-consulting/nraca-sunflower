<?php echo form_open(get_uri("accounting/journal_entry/save"), array("id" => "master_coa-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id ?>">
    <div class="tab-content mt15">
        <div role="tabpanel" class="tab-pane active" id="general-info-tab">
            
            <!-- <div class="form-group">
                <label for="fid_coa" class=" col-md-3"> Paid from To</label>
                <div class=" col-md-9">
                    <?php
                    echo form_dropdown("fid_coa", $kas_dropdown, $model_info->fid_coa, "class='select2 validate-hidden' id='fid_coa' ");
                                                    
                    ?>
                </div>
            </div> -->
            <div class="form-group">
                <label for="code" class=" col-md-3">Transaction Code</label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "code",
                        "name" => "code",
                        "class" => "form-control",
                        "value" => $model_info->code,
                        // "readonly" => true,
                        "placeholder" =>  'Journal Code',
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="voucher_code" class=" col-md-3">Voucher Code</label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "voucher_code",
                        "name" => "voucher_code",
                        "value" => $model_info->voucher_code,
                        "class" => "form-control",
                        "data-rule-required" => true,
                        "placeholder" => 'Voucher Number',
                        "data-msg-required" => lang("field_required")
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="date" class=" col-md-3">Date</label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "date",
                        "name" => "date",
                        "class" => "form-control",
                        "placeholder" => 'Y-m-d',
                        "value" => $model_info->date,
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="jenis_entry" class=" col-md-3">Jenis Entry</label>
                <div class=" col-md-9">
                    <?php
                    echo form_dropdown("jenis_entry", 
                    array(
                        // "" => "",
                        "Penjualan Kamar" => "Penjualan Kamar",
                        "Penjualan Lainnya" => "Penjualan Lainnya",
                    ), 
                    $data['jenis_entry'], 
                    "class='select2 validate-hidden' id='jenis_entry'");
                    ?>
                </div>
            </div>
            <div id="penjualan-kamar-fields" style="display: none;">
                <div class="form-group">
                    <label for="tipe_kamar" class=" col-md-3">Kode & Kelas Kamar</label>
                    <div class=" col-md-9">
                        <?php
                        echo form_dropdown(
                            "tipe_kamar", 
                            $tipe_kamar_dropdown, 
                            $data['tipe_kamar'], 
                            "class='select2 validate-hidden' 
                            id='tipe_kamar'");
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_kamar" class=" col-md-3">No. Kamar</label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "no_kamar",
                            "name" => "no_kamar",
                            "class" => "form-control",
                            "value" => $data['no_kamar'],
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="nama_tamu" class=" col-md-3">Nama Tamu</label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "nama_tamu",
                            "name" => "nama_tamu",
                            "class" => "form-control",
                            "value" => $data['nama_tamu'],
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tanggal_datang" class=" col-md-3">Tanggal Datang</label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "tanggal_datang",
                            "name" => "tanggal_datang",
                            "class" => "form-control",
                            "placeholder" => 'Y-m-d',
                            "value" => $data['tanggal_datang'],
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tanggal_pergi" class=" col-md-3">Tanggal Pergi</label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "tanggal_pergi",
                            "name" => "tanggal_pergi",
                            "class" => "form-control",
                            "placeholder" => 'Y-m-d',
                            "value" =>  $data['tanggal_pergi'],
                        ));
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="description" class=" col-md-3">Description</label>
                <div class=" col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "description",
                        "name" => "description",
                        "class" => "form-control",
                        "value" => $model_info->description,
                        "data-rule-required" => true,
                        "placeholder" => 'Memo',
                        "data-msg-required" => lang("field_required")
                    ));
                    ?>
                </div>
            </div>          
        </div>
    </div>

</div>


<div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button id="form-submit" type="button" class="btn btn-primary "><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#master_coa-form .select2").select2();
        setDatePicker("#date");
        setDatePicker("#tanggal_datang");
        setDatePicker("#tanggal_pergi");
        $("#master_coa-form").appForm({
            onSuccess: function (result) {
                 if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    location.reload();
                }
            },
        });
        
        // Function to toggle required validation for code field
        function toggleCodeRequired() {
            var statusPembayaran = $("select[name='status_pembayaran']").val();
            var codeField = $("#code");

            if (statusPembayaran === "1") {
                codeField.attr("data-rule-required", "true"); // Make code required
                codeField.attr("data-msg-required", "<?php echo lang('field_required'); ?>");
            } else {
                codeField.removeAttr("data-rule-required"); // Remove required rule
            }

            // Re-validate form to apply changes
            $("#master_coa-form").appForm({
                onSuccess: function (result) {
                    if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                        location.reload();
                    } else {
                        window.location = "<?php echo site_url('accounting/journal_entry/entry'); ?>/" + result.id;
                    }
                }
            });
        }

        // Initial call to set validation based on default status_pembayaran
        toggleCodeRequired();

        // Update validation when status_pembayaran changes
        $("select[name='status_pembayaran']").on("change", function () {
            toggleCodeRequired();
        });

        $("#form-submit").click(function() {
            $("#master_coa-form").trigger('submit');
        });
             
    });
    function togglePenjualanKamarFields() {
        var jenisEntry = $("select[name='jenis_entry']").val();
        if (jenisEntry === "Penjualan Kamar") {
            $("#penjualan-kamar-fields").slideDown();
        } else {
            $("#penjualan-kamar-fields").slideUp();
        }
    }

    $(document).ready(function () {
        togglePenjualanKamarFields(); // untuk load awal

        $("select[name='jenis_entry']").on("change", function () {
            togglePenjualanKamarFields();
        });
    });
</script>