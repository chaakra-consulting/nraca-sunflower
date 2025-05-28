<div id="page-content" class="clearfix">
    <?php load_css(array("assets/css/invoice.css")); ?>
    <div style="max-width: 1000px; margin: auto;">

        <div id="invoice-status-bar" class="panel panel-default p5 no-border m0">
            <form action="" method="GET" role="form" class="general-form">
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <input type="text" class="form-control" id="start_date" name="start" autocomplete="off"
                                placeholder="START DATE"
                                value="<?php echo isset($_GET['start']) ? $_GET['start'] : ''; ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" id="end_date" name="end" autocomplete="off"
                                placeholder="END DATE" value="<?php echo isset($_GET['end']) ? $_GET['end'] : ''; ?>">
                        </td>
                        <td>
                            <button type="submit" name="search" class="btn btn-default" value="1"><i
                                    class="fa fa-search"></i> Filter</button>
                            <a href="#" name="print" class="btn btn-default"
                                onclick="tableToExcel('table-print', 'Lap_Penjualan')"><i
                                    class="fa fa-file-excel-o"></i> Excel</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="mt15">
            <div class="panel panel-default p15 b-t">
                <div>
                    <table class="table table-bordered" id="table-print">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Transaction Code</th>
                                <th style="text-align: center;">Jenis Entry</th>
                                <th style="text-align: center;">Deskripsi</th>
                                <th style="text-align: center; width: 180px;">Nama Tamu / Kelas Kamar</th>
                                <th style="text-align: center; width: 180px;">Debet</th>
                                <th style="text-align: center; width: 180px;">Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $jumlah = 0;
                            foreach ($sales_report as $row) { ?>
                                <tr>
                                    <td><?php echo $row->transaction_code ?? '-'; ?></td>
                                    <td><?php echo $row->jenis_entry; ?></td>
                                    <td><?php echo $row->description; ?></td>
                                    <td><?php echo $row->nama_tamu_kelas_kamar ?? '-'; ?></td>
                                    <td style="text-align: right;">
                                        <?php echo to_currency($row->debet, false);
                                        $jumlah_debet += $row->debet; ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?php echo to_currency($row->credit, false);
                                        $jumlah_credit += $row->credit; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" style="text-align: right;">TOTAL :</th>
                                <th style="text-align: right;"><?php echo to_currency($jumlah_debet, false); ?></th>
                                <th style="text-align: right;"><?php echo to_currency($jumlah_credit, false); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        setDatePicker("#start_date");
        setDatePicker("#end_date");

        $('#table-print').DataTable({
            "order": [[2, "desc"]], // Default sorting by total amount in descending order
            "columnDefs": [
                {"targets": [0, 1], "type": "string"},
                {"targets": [2, 3], "type": "num-fmt", "orderSequence": ["desc", "asc"]}
            ],
            "paging": false,
            "info": false, 
            "searching": true 
        });
    });
</script>