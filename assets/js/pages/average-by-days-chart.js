function chartDisp( chart_data ){

    var colorz = 'green';

    // Visualization API with the 'corechart' package.
    google.charts.load('visualization', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawLineChart);

    function drawLineChart( ) {
        
        // Loop through each data and populate the array.
        $.each(chart_data, function (first_index, first_value) {
            
            $.each(first_value, function (second_index, second_value) { 
                
                var arrSales = [['Index', second_index.replace(/_/g, " ")]];    // Define an array and assign columns for the chart.
                
                $.each(second_value, function (third_index, third_value) { 

                      arrSales.push([third_index, parseFloat(third_value) ]);
            
                });
                
                // Set chart Options.
                var options = {
                    title: second_index.replace(/_/g, " ") + ' of ' + first_index + ' days average',
                    colors: [colorz, 'red'],
                    pointSize: 5,
                    curveType: 'function',
                    legend: {position: 'bottom', textStyle: {color: colorz, fontSize: 14}}  // You can position the legend on 'top' or at the 'bottom'.
                };

                // Create DataTable and add the array to it.
                var figures = google.visualization.arrayToDataTable(arrSales)

                // Define the chart type (LineChart) and the container (a DIV in our case).
                var chart = new google.visualization.LineChart(document.getElementById(first_index + '_' + second_index + '_chart'));
                chart.draw(figures, options);      // Draw the chart with Options.
                
            });
        
        });
    }
}

var chart_data = $("#chart_data").data("all");

chartDisp( chart_data );