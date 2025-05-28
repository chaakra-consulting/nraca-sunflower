<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div id="page-content" class="m20 clearfix">
    <canvas id="myChart" width="400" height="350"></canvas>
</div>

<?php
// Decode chart data from the controller
$chart_data = json_decode($chart_data, true);

// Prepare data for Chart.js
$labels = [];  // Tahun (x-axis)
$salesData = [];  // Total penjualan per tahun (y-axis)
foreach ($chart_data as $data) {
    $labels[] = $data['year'];  // Tahun
    $salesData[] = $data['total_sales'];  // Total penjualan
}
?>

<form action="" method="get">
    <div class="form-group">
        <label for="customer_id">Perusahaan</label>
        <select name="customer_id" id="customer_id" class="form-control">
            <option selected disabled>-- Select Perusahaan --</option>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= $customer->id; ?>" <?= ($customer->id == $selected_customer ? "selected" : ""); ?>>
                    <?= $customer->name; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mt-2">
        <button type="submit" class="btn btn-primary">Pilih</button>
    </div>
</form>

<script>
    // Data for the chart
    var labels = <?= json_encode($labels) ?>;
    var data = <?= json_encode($salesData) ?>;
    var customerName = "<?php
    foreach ($customers as $customer) {
        if ($customer->id == $selected_customer) {
            echo $customer->name;
            break;
        }
    }
    ?>";

    // Chart.js Data Configuration
    var chartData = {
        labels: labels,  // Tahun
        datasets: [{
            label: "Total Pendapatan " + customerName + " per Tahun",
            backgroundColor: "#36A2EB",  // Color for bars
            borderColor: "#0369a1",  // Border color for bars
            data: data
        }]
    };

    // Chart.js Configuration
    var options = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                ticks: {
                    callback: function (value, index, values) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(value);
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function (context) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(context.raw);
                    }
                }
            }
        }
    };

    // Render the chart
    var ctx = document.getElementById("myChart").getContext("2d");
    var myChart = new Chart(ctx, {
        type: 'bar',  // Bar chart type
        data: chartData,
        options: options
    });
</script>