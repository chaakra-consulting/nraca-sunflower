<?php echo form_open(get_uri("accounting/expenses/add_detail"), array("id" => "master_coa-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $info_header->id ?>">
    <input type="hidden" name="journal_code" value="<?php echo $info_header->code ?>">
    <input type="hidden" name="voucher_code" value="<?php echo $info_header->voucher_code ?>">
    <input type="hidden" name="fid_coa_header" value="<?php echo $info_header->fid_coa ?>">
    <input type="hidden" name="fid_project" value="<?php echo $info_header->fid_project ?>">
    
    <input type="hidden" name="date" value="<?php echo $info_header->date ?>">
    <div class="tab-content mt15">
        <div role="tabpanel" class="tab-pane active" id="general-info-tab">
            
            <div class="form-group">
                <label for="fid_coa" class=" col-md-3"> Accounts Expenses</label>
                <div class=" col-md-9">
                    <?php
                    echo form_dropdown("fid_coa", $acc_dropdown, "", "class='select2 validate-hidden' id='fid_coa' ");
                                                    
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="debet" class=" col-md-3">Amount</label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "debet",
                        "name" => "debet",
                        "class" => "form-control",
                        "placeholder" => '0',
                    ));
                    ?>
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
                        "placeholder" => 'Memo',
                        "data-msg-required" => lang("field_required")
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="status_pembayaran" class=" col-md-3">Status Pembayaran</label>
                <div class=" col-md-9">
                    <?php
                    echo form_dropdown("status_pembayaran", 
                    array(
                        "0" => "Belum Terverifikasi",
                        "1" => "Terverifikasi",
                    ), 
                    "", 
                    "class='select2 validate-hidden' id='status_pembayaran'");
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="foto" class=" col-md-3">Upload Bukti (PDF)</label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "foto",
                        "name" => "foto",
                        "class" => "form-control",
                        "type" => "file",
                        "accept" => "application/pdf"
                    ));
                    ?>
                    <div id="preview-container" class="mt-2">
                        <?php if (!empty($model_info->bukti_url)): ?>
                            <?php
                            $ext = pathinfo($model_info->bukti_url, PATHINFO_EXTENSION);
                            $is_image = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            ?>
                            <?php if ($is_image): ?>
                                <img id="preview-image" src="<?php echo $model_info->bukti_url; ?>" alt="Bukti" style="max-width: 75%; height: 400px; border:1px solid #ccc; padding:5px;">
                            <?php else: ?>
                                <a id="preview-link" href="<?php echo $model_info->bukti_url; ?>" target="_blank" class="btn btn-sm btn-info">Lihat Bukti</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
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
        $('#debet').maskMoney(
            {precision:0 
        });
        $('#debet').maskMoney(
            {precision:0 
        });
        
        $('input[name=debet]').change(function() {
            var value = $(this).val();
            
        });
        RELOAD_VIEW_AFTER_UPDATE = false; //go to invoice page
        $("#master_coa-form .select2").select2();
        setDatePicker("#date");
        $("#master_coa-form").appForm({
            onSuccess: function (result) {
                
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    window.location = "<?php echo site_url('accounting/expenses/entry'); ?>/" + result.id+ "/"+result.fid_coa;
                }
            },
        });
        $("#form-submit").click(function() {
            $("#master_coa-form").trigger('submit');
        }); 
    });

    document.getElementById('foto').addEventListener('change', function (e) {
        const previewContainer = document.getElementById('preview-container');
        const file = e.target.files[0];

        if (file && file.type === "application/pdf") {
            const fileReader = new FileReader();

            fileReader.onload = function (event) {
                previewContainer.innerHTML = "";

                const iframe = document.createElement("iframe");
                iframe.src = event.target.result;
                iframe.style.width = "75%";
                iframe.style.height = "400px";
                iframe.style.border = "1px solid #ccc";
                previewContainer.appendChild(iframe);
            };

            fileReader.readAsDataURL(file);
        } else {
            previewContainer.innerHTML = "<div class='text-danger'>File bukan PDF.</div>";
        }
    });
</script>