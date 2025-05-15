<?php $this->load->helper('function_helper'); ?>
<style>
#stockSearchInput {
  width: 100%;
  font-size: 16px;
  padding: 12px 20px;
  margin-bottom: 12px;
  border: 1px solid #ddd;
}
#stockTable {
  width: 100%;
  border-collapse: collapse;
  font-size: 16px;
}
#stockTable th, #stockTable td {
  padding: 10px;
  border: 1px solid #ddd;
  text-align: left;
}
#stockTable tr:hover {
  background-color: #f1f1f1;
}
</style>

<div class="container">
    <h1><?php echo $title; ?></h1>
    
    <div style="margin: 15px 0; border : 1px solid #ddd; padding: 10px 15px; border-radius: 5px;">
       <label id="capRangeLabel" style="font-weight:bold; font-size:18px; color:mediumvioletred; display: flex; justify-content: space-between;">
        <span>Min: <span id="capMinVal">0.1</span> Cr.</span>
        <span>Max: <span id="capMaxVal">60,000</span> Cr.</span>
      </label>
      <div id="capRangeSlider" style="margin-top: 10px;"></div>
    </div>


    <?php if (!empty($stocks)): ?>
        <input type="text" id="stockSearchInput" onkeyup="filterStocks()" placeholder="Search for company name or symbol...">
        <table id="stockTable">
            <thead>
                <tr>
                    <th>Symbol</th>
                    <th>Name</th>
                    <th>Total % Change</th>
                    <th>Market Cap(Cr.)</th>
                    <th>Current 5 Days Change</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stocks as $stock): ?>
                    <tr>
                        <td>
                            <a href="<?php echo base_url('daily-log/'.$stock['company_id'].'/' . base64_url_encode($stock['company_symbol'])); ?>">
                                <?php echo $stock['company_symbol']; ?>
                            </a>
                        </td>
                        <td><?php echo $stock['company_name']; ?></td>
                        <td><?php echo round($stock['total_change'], 2); ?>%</td>
                        <td><?php echo number_format($stock['total_market_cap'], 2); ?></td>
                        <td>
                          <?php
                          $changes = explode('|', $stock['price_change_5']);
                          foreach ($changes as $i => $change) {
                              $trimmed = trim($change);
                              if ($trimmed === '-') {
                                  echo '<span style="color: gray;">-</span>';
                              } elseif (floatval($trimmed) > 0) {
                                  echo '<span style="color: green;">' . $trimmed . '%</span>';
                              } elseif (floatval($trimmed) < 0) {
                                  echo '<span style="color: red;">' . $trimmed . '%</span>';
                              } else {
                                  echo '<span>' . $trimmed . '%</span>';
                              }

                              // Add pipe (|) separator between values
                              if ($i < count($changes) - 1) echo ' | ';
                          }
                          ?>
                      </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No stocks found matching the criteria.</p>
    <?php endif; ?>
</div>

<script>

function filterStocks() {
    const input = document.getElementById("stockSearchInput").value.toUpperCase();
    const minCap = $("#capMinVal").data("raw");
    const maxCap = $("#capMaxVal").data("raw");

    const table = document.getElementById("stockTable");
    const tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        const symbol = tr[i].getElementsByTagName("td")[0];
        const name = tr[i].getElementsByTagName("td")[1];
        const marketCap = tr[i].getElementsByTagName("td")[3];

        if (symbol && name && marketCap) {
            const textValue = (symbol.textContent + " " + name.textContent).toUpperCase();
            const rawCapText = marketCap.textContent.replace(/[^0-9.]/g, '');
            const marketCapValue = parseFloat(rawCapText);

            if (isNaN(marketCapValue)) {
                tr[i].style.display = "none";
                continue;
            }

            const matchesSearch = textValue.indexOf(input) > -1;
            const inRange = marketCapValue >= minCap && marketCapValue <= maxCap;

            tr[i].style.display = (matchesSearch && inRange) ? "" : "none";
        }
    }
}


$(function () {
    const sliderMin = 0.1;
    const sliderMax = 60000;

    $("#capRangeSlider").slider({
        range: true,
        min: sliderMin,
        max: sliderMax,
        values: [sliderMin, sliderMax],
        step: 1,
        slide: function (event, ui) {
            const minFormatted = ui.values[0].toLocaleString("en-IN", {
                minimumFractionDigits: 1,
                maximumFractionDigits: 2
            });
            const maxFormatted = ui.values[1].toLocaleString("en-IN", {
                maximumFractionDigits: 2
            });

            $("#capMinVal").text(minFormatted);
            $("#capMaxVal").text(maxFormatted);

            // Save raw values for filter logic
            $("#capMinVal").data("raw", ui.values[0]);
            $("#capMaxVal").data("raw", ui.values[1]);

            filterStocks();
        }
    });

    // On page load: set label text and store raw values
    const initialVals = $("#capRangeSlider").slider("values");
    $("#capMinVal")
        .text(initialVals[0].toLocaleString("en-IN", { minimumFractionDigits: 1 }))
        .data("raw", initialVals[0]);

    $("#capMaxVal")
        .text(initialVals[1].toLocaleString("en-IN", { maximumFractionDigits: 2 }))
        .data("raw", initialVals[1]);
});


</script>
