<?php echo form_open(get_uri("purchase/request/add"), array("id" => "request-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="form-group">
        <label for="code" class=" col-md-3">Code</label>
        <div class="col-md-9">
        <?php 
                echo form_dropdown(
                    "code", array(
                        "501" => "501 - Operasional",
                        "502" => "502 - Transpaort",
                        "503" => "503 - Perlengapan Kantor",
                        "504" => "504 - Konsumsi"
                        ), "", "class='select2'"
                    );
                        ?>
        </div>
    </div> 
    <div class="form-group">
        <label for="fid_vendor" class="col-md-3">Vendor</label>
        <div class=" col-md-9">
            <?php
            echo form_dropdown("fid_vendor", $vendor_dropdown, $model_info->fid_vendor, "class='select2 validate-hidden' id='fid_vendor' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="email_to" class="col-md-3">Email To</label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "email_to",
                "name" => "email_to",
                "class" => "form-control",
                "placeholder" => "client@example.com"
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="inv_address" class=" col-md-3">Alamat</label>
        <div class="col-md-9">
             <?php 
                echo form_textarea(array(
                "id" => "inv_address",
                "name" => "inv_address",
                "class" => "form-control",
                "placeholder" => "Alamat Invoice",
                ));
            ?>
        </div>
    </div>
    <!--<div class="form-group">
        <label for="delivery_address" class=" col-md-3">Alamat Pengiriman</label>
        <div class="col-md-9">
             <?php 
                echo form_textarea(array(
                "id" => "delivery_address",
                "name" => "delivery_address",
                "class" => "form-control",
                "placeholder" => "Alamat Pengiriman",
                // "data-rule-required" => true,
                // "data-msg-required" => lang("field_required"),
            ));
                        ?>
        </div>
    </div>-->

    <div class="form-group">
        <label for="fid_tax" class=" col-md-3"><?php echo lang('tax'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("fid_tax", $taxes_dropdown, array($model_info->fid_tax), "class='select2 tax-select2'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="currency" class=" col-md-3">Mata Uang</label>
        <div class="col-md-9">
             <?php 
                echo form_dropdown(
                    "currency", array(
                        "IDR" => "IDR",
                        "USD" => "USD",
                        "EUR" => "EUR",
                        "JPY" => "JPY"
                        ), "", "class='select2 mini'"
                    );
                        ?>
        </div>
    </div>
    
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {

        $("#request-form .select2").select2();

        $("#request-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    window.location = "<?php echo site_url('purchase/request/view'); ?>/" + result.id;
                }
            },
            onAjaxSuccess: function (result) {
                if (!result.success && result.next_recurring_date_error) {
                    $("#next_recurring_date").val(result.next_recurring_date_value);
                    $("#next_recurring_date_container").removeClass("hide");

                    $("#invoice-form").data("validator").showErrors({
                        "next_recurring_date": result.next_recurring_date_error
                    });
                }
            }
        });
        $("#fid_vendor").select2().on("change", function () {
            var vendor_id = $(this).val();
            if ($(this).val()) {
                // $('#invoice_project_id').select2("destroy");
                // $("#invoice_project_id").hide();
                // appLoader.show({container: "#invoice-porject-dropdown-section"});
                $.ajax({
                    url: "<?php echo get_uri("master/vendors/getId") ?>" + "/" + vendor_id,
                    dataType: "json",
                    // data: data,
                    type:'GET',
                    success: function (data) {

                         $.each(data, function(index, element) {

                            $("#email_to").val(element.email);
                            $("#inv_address").val(element.address);
                            $("#delivery_address").val(element.address);
                         });
                    }
                });
            }
        });
        $("#fid_order").select2().on("change", function () {
            var client_id = $(this).val();
            if ($(this).val()) {
                // $('#invoice_project_id').select2("destroy");
                // $("#invoice_project_id").hide();
                // appLoader.show({container: "#invoice-porject-dropdown-section"});
                $.ajax({
                    url: "<?php echo get_uri("purchase/request/getOrderId") ?>" + "/" + client_id,
                    dataType: "json",
                    // data: data,
                    type:'POST',
                    success: function (data) {

                         $.each(data, function(index, element) {
                            $("#fid_cust").val(element.id).select2();     
                         });
                    }
                });
                $.ajax({
                    url: "<?php echo get_uri("purchase/request/getOrderIdd") ?>" + "/" + client_id,
                    dataType: "json",
                    // data: data,
                    type:'POST',
                    success: function (data) {

                         $.each(data, function(index, element) {
                            $("#fid_custt").val(element.id).select2();      
                         });
                    }
                });
            }
        });

    });
</script>