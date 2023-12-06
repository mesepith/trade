function chartDisp( chart_data, plot_data, plot_data_name, colorz ){

    // Visualization API with the 'corechart' package.
    google.charts.load('visualization', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawLineChart);

    function drawLineChart( ) {
        
        // Loop through each data and populate the array.
        $.each(chart_data, function (first_index, first_value) {
            
            var arrSales = [['Date', first_index]];    // Define an array and assign columns for the chart.
            
            $.each(first_value, function (index, value) {                
                
                arrSales.push([index, parseFloat(value) ]);
                
            });
            
            console.log('arrSales');
            console.log(arrSales);
            
            
            // Set chart Options.
            var options = {
                title: first_index,
                colors: [colorz, 'red'],
                pointSize: 5,
                curveType: 'function',
                legend: {position: 'bottom', textStyle: {color: colorz, fontSize: 14}}  // You can position the legend on 'top' or at the 'bottom'.
            };
            
            // Create DataTable and add the array to it.
            var figures = google.visualization.arrayToDataTable(arrSales)

            // Define the chart type (LineChart) and the container (a DIV in our case).
            var chart = new google.visualization.LineChart(document.getElementById(first_index + '_chart'));
            chart.draw(figures, options);      // Draw the chart with Options.
            
        });
    }
}

var quarter_chart_data = $("#quarter_chart_data").data("all");
var monthly_chart_data = $("#monthly_chart_data").data("all");
var weekly_chart_data = $("#weekly_chart_data").data("all");

//console.log(quarter_chart_data);

function chartOnLoad(){

    chartDisp( quarter_chart_data, 'ltp_quarter', 'LTP Quarter', 'green' );
    chartDisp( monthly_chart_data, 'ltp_quarter', 'LTP Quarter', 'green' );
    chartDisp( weekly_chart_data, 'ltp_quarter', 'LTP Quarter', 'green' );
   
}

chartOnLoad();