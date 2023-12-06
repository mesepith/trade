/*
 * Display Chart Start
 */

var market_running = $("#market_running").data("val");


function chartDisp( chart_data, plot_data, plot_data_name, colorz ){

    // Visualization API with the 'corechart' package.
    google.charts.load('visualization', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawLineChart);

    function drawLineChart( ) {

//        console.log(chart_data);
        if( plot_data === "total_buy_sell_quantity"){
            
            var arrSales = [['Time', 'Total Buy' , 'Total Sell' ]];
        }else{
            var arrSales = [['Time', plot_data_name]];    // Define an array and assign columns for the chart.
        
        }
        
        // Loop through each data and populate the array.
        $.each(chart_data, function (index, value) {
            
            var date_or_time = value.stock_time;
            
//            console.log(date_or_time);
            if( plot_data === 'ltp' ){
                
                arrSales.push([date_or_time, parseFloat(value.ltp) ]);
                
            }
        });
        
        

        // Set chart Options.
        var options = {
            title: plot_data_name,
            colors: [colorz, 'red'],
            pointSize: 5,
            curveType: 'function',
            legend: {position: 'bottom', textStyle: {color: colorz, fontSize: 14}}  // You can position the legend on 'top' or at the 'bottom'.
        };

        // Create DataTable and add the array to it.
        var figures = google.visualization.arrayToDataTable(arrSales)

        // Define the chart type (LineChart) and the container (a DIV in our case).
        var chart = new google.visualization.LineChart(document.getElementById(plot_data + '_chart'));
        chart.draw(figures, options);      // Draw the chart with Options.

    }
}


var chart_data = $("#chart_data").data("all");

console.log(chart_data);


function chartOnLoad(){

    $.ajax({url: "nifty-live", success: function(result){
    
        console.log("result");    
        console.log(result.length);
        console.log(JSON.parse(result).length);  
        
        var parse_result = JSON.parse(result);
        
        console.log(JSON.parse(result));    
        chartDisp(( JSON.parse(result)), 'ltp', 'Close Price', 'green' );
        
        niftyLatestPriceDisp( parse_result );
        
    }});
    
}

chartOnLoad();

setInterval(function(){ chartOnLoad() }, 30000);

function niftyLatestPriceDisp( parse_result ){
    
    var total = parse_result.length;
    var nifty_price = parse_result[total-1]['ltp'];
    
    var nifty_change = parse_result[total-1]['change'];
    var nifty_change_in_percent = parse_result[total-1]['change_in_percent'];
    
    console.log('nifty_price');
    console.log(nifty_price);
    
    $(".nifty_latest_price").html(nifty_price);
    $(".nifty_latest_chng").html(nifty_change + ' (' + nifty_change_in_percent + '%) ');
    
    if( nifty_change > 0 ){
        
        $('#nifty-green-red').removeClass('nifty-red').addClass('nifty-green');
    }else{
        
        $('#nifty-green-red').removeClass('nifty-green').addClass('nifty-red');
    }
}
