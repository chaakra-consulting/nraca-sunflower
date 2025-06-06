<?php echo form_open(get_uri("purchase/request/save_item"), array("id" => "request-item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>" />
    <input type="hidden" name="add_new_item_to_library" value="" id="add_new_item_to_library" />
    <div class="form-group">
        <label for="invoice_item_title" class=" col-md-3"><?php echo lang('item'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_title",
                "name" => "invoice_item_title",
                "value" => $model_info->title,"readonly" => true,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_or_create_new_item'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="invoice_item_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class=" col-md-3">Deskripsi Item</label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "description",
                "name" => "description","readonly" => true,
                "value" => $model_info->description,
                "class" => "form-control",
                "placeholder" => "Deskripsi",
            ));
            ?>
        </div>
    </div>
    <input type="hidden" name="fid_item" id="fid_item">
    <div class="form-group">
        <label for="category" class=" col-md-3">Category</label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_category",
                "name" => "category",
                "value" => $model_info->category,
                "class" => "form-control",
                "readonly" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_item_quantity" class=" col-md-3"><?php echo lang('quantity'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_quantity",
                "name" => "invoice_item_quantity","readonly" => true,
                "value" => $model_info->quantity ? to_decimal_format($model_info->quantity) : "",
                "class" => "form-control",
                "placeholder" => lang('quantity'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_item_basic" class=" col-md-3">Harga Beli</label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_basic",
                "name" => "invoice_item_basic","readonly" => true,
                "value" => $model_info->basic_price ? to_decimal_format($model_info->basic_price) : "",
                "class" => "form-control",
                "placeholder" => "0",
                // "type" => 'number'
            ));
            ?>
        </div>
    </div> 
    <div class="form-group">
        <label for="invoice_item_rate" class=" col-md-3">Harga Jual</label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_rate",
                "name" => "invoice_item_rate","readonly" => true,
                "value" => $model_info->rate ? to_decimal_format($model_info->rate) : 0,
                "class" => "form-control",
                "placeholder" => lang('rate'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
        $(document).ready(function () {
        $('#invoice_item_basic').maskMoney(
            {precision:0 
        });
        $('#invoice_item_rate').maskMoney(
            {precision:0 
        });
        
        $('input[name=invoice_item_rate]').change(function() {
            var value = $(this).val();
            
        });
        $("#request-item-form .select2").select2();
        $("#request-item-form").appForm({
            onSuccess: function (result) {
                $("#request-item-table").appTable({newData: result.data, dataId: result.id});
                $("#request-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            }
        });

        //show item suggestion dropdown when adding new item
        var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#invoice_item_title_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        })

    });

    function applySelect2OnItemTitle() {
        $("#invoice_item_title").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("purchase/request/get_item_suggestion"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        }).change(function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                $("#invoice_item_title").select2("destroy").val("").focus();
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("purchase/request/get_item_info_suggestion"); ?>",
                    data: {item_name: e.val},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                                $("#invoice_item_description").val(response.item_info.category);
                                $("#fid_item").val(response.item_info.id);
                                $("#title").val(response.item_info.title);

                                $("#invoice_unit_type").val(response.item_info.unit_type);
                                $("#invoice_item_category").val(response.item_info.category);
                                $("#description").val(response.item_info.description);
                                
                                $("#invoice_item_quantity").val("1");
                                $("#invoice_item_basic").val(response.item_info.basic_price);
                                $("#invoice_item_rate").val(response.item_info.price);
                        }
                    }
                });
            }

        });
    }




</script>