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

    <?php if (!empty($stocks)): ?>
        <input type="text" id="stockSearchInput" onkeyup="filterStocks()" placeholder="Search for company name or symbol...">
        <table id="stockTable">
            <thead>
                <tr>
                    <th>Symbol</th>
                    <th>Name</th>
                    <th>Total % Change</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stocks as $stock): ?>
                    <tr>
                        <td><?php echo $stock['company_symbol']; ?></td>
                        <td><?php echo $stock['company_name']; ?></td>
                        <td><?php echo round($stock['total_change'], 2); ?>%</td>
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
  var input = document.getElementById("stockSearchInput");
  var filter = input.value.toUpperCase();
  var table = document.getElementById("stockTable");
  var tr = table.getElementsByTagName("tr");

  for (var i = 1; i < tr.length; i++) {
    var symbol = tr[i].getElementsByTagName("td")[0];
    var name = tr[i].getElementsByTagName("td")[1];
    if (symbol && name) {
      var textValue = symbol.textContent + " " + name.textContent;
      if (textValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
