<?php $this->load->helper('function_helper'); ?>
<style>
* {
  box-sizing: border-box;
}

#myInput {
  background-image: url('/css/searchicon.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 16px;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}

#myTable {
  border-collapse: collapse;
  width: 100%;
  border: 1px solid #ddd;
  font-size: 18px;
}

#myTable th, #myTable td {
  text-align: left;
  padding: 12px;
}

#myTable tr {
  border-bottom: 1px solid #ddd;
}

#myTable tr.header, #myTable tr:hover {
  background-color: #f1f1f1;
}
</style>

<div class="container">
    <h2>Company List</h2>  

    <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for Company.." title="Type in a name">

    <table class="table table-striped" id="myTable">
        <thead>
            <tr>
                <th>Company Name</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($company_list AS $company_list_value) { ?>

                <tr>
                    <td><a href="<?php echo base_url('whole-day-data/' . $company_list_value->id . '/' . base64_url_encode($company_list_value->symbol) ); ?>">
                        <?php echo $company_list_value->name . ' (' . $company_list_value->symbol . ')'; ?>
                        </a>
                    </td>
                </tr>

            <?php } ?>

        </tbody>
    </table>
</div>
<script>
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}



</script>
