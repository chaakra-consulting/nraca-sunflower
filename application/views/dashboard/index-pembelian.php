<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div id="page-content" class="m20 clearfix">

<canvas id="stackedBarChart" width="300" height="140"></canvas>
    </div>
    <?php
$year = $_GET["search"];
if (empty($year) || !is_numeric($year)) {
    $year = date('Y');
}
?>

<form action="">
    <div class="form-group">
        <label for="search">Tahun</label>
        <select name="search" id="" class="form-control">
            <option selected disabled>-- Pilih Tahun --</option>
            <?php for ($i = 20; $i < 30; $i++) : ?>
                <option <?= ($year == "20" . $i ? "selected" : ""); ?> value="20<?= $i; ?>">20<?= $i; ?></option>
            <?php endfor ?>
        </select>
    </div>
    <input type="hidden" name="stack" value="pembelian">
    <div class="mt-2">
        <button type="submit" class="btn btn-primary">Cari</button>
        <a href="<?= base_url("Dashboard?stack=pembelian"); ?>" class="btn btn-success">Reload</a>
    </div>
</form>
<?php
// load_js(array(
//     "assets/js/flot/jquery.flot.min.js",
//     "assets/js/flot/jquery.flot.pie.min.js",
//     "assets/js/flot/jquery.flot.resize.min.js",
//     "assets/js/flot/curvedLines.js",
//     "assets/js/flot/jquery.flot.tooltip.min.js",
//     "assets/js/chart.js",
//     "assets/js/gauge.js",
// ));
?>
<script>
const ctx = document.getElementById('stackedBarChart').getContext('2d');
const stackedBarChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [
            {
                label: 'Debet',
                data: <?= json_encode($expenseDebet) ?>,
                backgroundColor: 'rgba(255, 99, 132, 1)',
                stack: 'Expense'
            },
            {
                label: 'Credit',
                data: <?= json_encode($expenseCredit) ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                stack: 'Expense'
            },
        ]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Chart'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.dataset.label || '';
                        const value = context.parsed.y || 0;
                        return label + ': Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            x: {
                stacked: true
            },
            y: {
                stacked: true,
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});

</script>