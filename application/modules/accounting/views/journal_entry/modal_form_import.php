<?php echo form_open(get_uri("excel_import/import"), array(
    "id" => "master_coa-form",
    "class" => "general-form",
    "role" => "form",
    "enctype" => "multipart/form-data"
)); ?>

<div class="modal-body clearfix">
    <div class="tab-content mt15">
        <div role="tabpanel" class="tab-pane active" id="general-info-tab">
            <div class="form-group">
                <label for="file" class="col-md-3">Upload Excel</label>
                <div class="col-md-12">
                <?php
                    echo form_input(array(
                        "id" => "file",
                        "name" => "file",
                        "class" => "form-control",
                        "type" => "file",
                        "accept" => ".xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    ));
                ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal">
        <span class="fa fa-close"></span> <?php echo lang('close'); ?>
    </button>
    <button type="submit" class="btn btn-primary">
        <span class="fa fa-check-circle"></span> <?php echo lang('save'); ?>
    </button>
</div>

<?php echo form_close(); ?>
