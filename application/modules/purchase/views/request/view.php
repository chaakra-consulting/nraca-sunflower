<div id="page-content" class="clearfix">
    <div style="max-width: 1000px; margin: auto;">
        <div class="page-title clearfix mt15">
            <h1> <?php echo "REQUEST : ".get_request_id($invoice_info->code); ?>
                
            </h1>
            <div class="title-button-group">
                <span class="dropdown inline-block">
                    <button class="btn btn-info dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class='fa fa-cogs'></i> <?php echo lang('actions'); ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">            
                        <li role="presentation"><?php echo anchor(get_uri("purchase/request/download_pdf/" . $invoice_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'))); ?> </li>
                        <li role="presentation"><?php echo anchor(get_uri("purchase/request/preview/" . $invoice_info->id . "/1"), "<i class='fa fa-search'></i> " . 'Preview', array("title" => 'Preview'), array("target" => "_blank")); ?> </li>                       
                    </ul>
                </span> 
                <?php if($invoice_info->status != "approval" ) echo modal_anchor(get_uri("purchase/request/item_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_item'), array("class" => "btn btn-default", "title" => lang('add_item'), "data-post-invoice_id" => $invoice_info->id)); ?>

            </div>
        </div>

        <div id="request-status-bar">
            <?php $this->load->view("request/request_status_bar"); ?>
        </div>

        

        <div class="mt15">
            <div class="panel panel-default p15 b-t">
                
                <div class="clearfix p20">
                    <div class="col-sm-6">
                        <table style="font-size:14px;">
                            <tr>
                                <td width="200">Request Code</td>
                                <td width="20">:</td>
                                <td> <strong>#<?php echo $invoice_info->code ?></strong></td>
                            </tr>
                            <tr>
                                <td>Created Date</td>
                                <td>:</td>
                                <td> <?php echo format_to_date($invoice_info->created_at,true) ?></td>
                            </tr>
                           
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <table style="font-size:14px;" class="display">
                            <tr>
                                <td width="200">Vendor Name</td>
                                <td width="20">:</td>
                                <td> <strong><?php echo $client_info->code." - ".$client_info->name ?></strong></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td> <?php echo $client_info->address ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="clearfix">
                    
                
                </div>

                <div class="table-responsive mt15 pl15 pr15">
                    <table id="request-item-table" class="display" width="100%">            
                    </table>
                </div>

                <div class="clearfix">
                    <div class="col-sm-8">

                    </div>
                    <div class="col-sm-4" id="request-total-section">
                        <?php $this->load->view("request/request_total_section"); ?>
                    </div>
                </div>

            </div>
        </div>


        

           
    </div>
</div>



<script type="text/javascript">
    RELOAD_VIEW_AFTER_UPDATE = true;
    $(document).ready(function () {
        $("#request-item-table").appTable({
            source: '<?php echo_uri("purchase/request/item_list_data/". $invoice_info->id) ?>',
            order: [[0, "asc"]],
            hideTools: true,
            columns: [
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"},
                {title: '<?php echo lang("item") ?> '},
                {title: '<?php echo lang("category") ?> '},
                {title: '<?php echo lang("description") ?> '},
                {title: '<?php echo lang("quantity") ?>', "class": "text-right w15p"},
                {title: 'Harga beli', "class": "text-right w15p"},
                {title: '<?php echo lang("total") ?>', "class": "text-right w15p"},
                
            ],
            onDeleteSuccess: function (result) {
                $("#request-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            },
            onUndoSuccess: function (result) {
                $("#request-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            }
        });

        
    });

    updateInvoiceStatusBar = function (invoiceId) {
        $.ajax({
            url: "<?php echo get_uri("purchase/request/get_invoice_status_bar"); ?>/" + invoiceId,
            success: function (result) {
                if (result) {
                    $("#request-status-bar").html(result);
                }
            }
        });
    };

</script>

<?php
//required to send email 

load_css(array(
    "assets/js/summernote/summernote.css",
));
load_js(array(
    "assets/js/summernote/summernote.min.js",
));
?>

