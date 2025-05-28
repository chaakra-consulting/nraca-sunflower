<?php echo form_open(get_uri("sales/s_retur/pay_save"), array("id" => "order-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">

    <input type="hidden" name="fid_cust" value="<?php echo $model_info->fid_cust; ?>" />
    <input type="hidden" name="fid_inv" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="paid" value="<?php echo $model_info->paid; ?>" />
    <input type="hidden" name="coa_sales" value="<?php  echo $model_info->coa_sales; ?>">




    
    <div class="form-group">
        <label for="total" class="col-md-3">QUANTITY</label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "total",
                "name" => "total",
                "class" => "form-control",
                "value" => $retur_info->quantity,

                "placeholder" => "",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
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
        RELOAD_VIEW_AFTER_UPDATE = false; //go to invoice page
        

        $("#order-form .select2").select2();
        setDatePicker("#inv_date");
        setDatePicker("#pay_date");
        $("#order-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    window.location = "<?php echo site_url('sales/s_payments/prints/'); ?>"+ result.id ;
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
        
        
    });
</script>