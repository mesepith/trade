/*
 * https://www.encodedna.com/javascript/practice-ground/default.htm?pg=line_chart_using_core_chart
 */

var market_running = $("#market_running").data("val");

function chartDisp( chart_data, plot_data, plot_data_name, colorz ){

    // Visualization API with the 'corechart' package.
    google.charts.load('visualization', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawLineChart);

    function drawLineChart( ) {

//        console.log(chart_data);

        var arrSales = [['Time', plot_data_name]];    // Define an array and assign columns for the chart.

        // Loop through each data and populate the array.
        $.each(chart_data, function (index, value) {
            
            if( market_running === 'live' ){
                
                var date_or_time = value.underlying_time;
            }else{
                
//                var date_or_time = value.underlying_date;
                
                var date_or_time = new Date(value.underlying_date);
            }
//            console.log(date_or_time);
            if( plot_data === 'calls_ltp' ){
             
                arrSales.push([date_or_time, parseFloat(value.calls_ltp) ]);
                
            }else if( plot_data === 'money_flow_calls' ){
                
                arrSales.push([date_or_time, parseFloat(value.money_flow_calls) ]);
                
            }else if( plot_data === 'calls_iv' ){
                
                arrSales.push([date_or_time, parseFloat(value.calls_iv) ]);
                
            }else if( plot_data === 'calls_oi' ){
                
                arrSales.push([date_or_time, parseFloat(value.calls_oi) ]);
                
            }else if( plot_data === 'calls_volume' ){
                
                arrSales.push([date_or_time, parseFloat(value.calls_volume) ]);
                
            }else if( plot_data === 'call_chng_in_oi_by_vol' ){
                
                arrSales.push([date_or_time, parseFloat(value.call_chng_in_oi_by_vol) ]);
                
            }else if( plot_data === 'money_flow_puts' ){
             
                arrSales.push([date_or_time, parseFloat(value.money_flow_puts) ]);
                
            }else if( plot_data === 'puts_ltp' ){
             
                arrSales.push([date_or_time, parseFloat(value.puts_ltp) ]);
                
            }else if( plot_data === 'puts_iv' ){
             
                arrSales.push([date_or_time, parseFloat(value.puts_iv) ]);
                
            }else if( plot_data === 'puts_oi' ){
             
                arrSales.push([date_or_time, parseFloat(value.puts_oi) ]);
                
            }else if( plot_data === 'puts_volume' ){
             
                arrSales.push([date_or_time, parseFloat(value.puts_volume) ]);
                
            }else if( plot_data === 'put_chng_in_oi_by_vol' ){
             
                arrSales.push([date_or_time, parseFloat(value.put_chng_in_oi_by_vol) ]);
                
            }else if( plot_data === 'underlying_price' ){
                
//                arrSales.push([value.underlying_time, parseFloat(value.underlying_price) ]);
                arrSales.push([date_or_time, parseFloat(value.underlying_price) ]);
            }

        });

        // Set chart Options.
        var options = {
            title: plot_data_name,
            colors: [colorz],
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

function chartOnLoad(){

    chartDisp( chart_data, 'money_flow_calls', 'Money Flow Calls', 'red' );
    chartDisp( chart_data, 'calls_ltp', 'Calls LTP', 'red' );
    chartDisp( chart_data, 'calls_iv', 'Calls IV', 'red' );
    chartDisp( chart_data, 'calls_oi', 'Calls OI', 'red' );
    chartDisp( chart_data, 'calls_volume', 'Calls Volume', 'red' );
    chartDisp( chart_data, 'call_chng_in_oi_by_vol', 'Calls Change In OI / Volume', 'red' );

    chartDisp( chart_data, 'money_flow_puts', 'Money Flow Puts', 'blue' );
    chartDisp( chart_data, 'puts_ltp', 'Puts LTP', 'blue' );
    chartDisp( chart_data, 'puts_iv', 'Puts IV', 'blue' );
    chartDisp( chart_data, 'puts_oi', 'Puts OI', 'blue' );
    chartDisp( chart_data, 'puts_volume', 'Puts Volume', 'blue' );
    chartDisp( chart_data, 'put_chng_in_oi_by_vol', 'Puts Change In OI / Volume', 'blue' );
    
    chartDisp( chart_data, 'underlying_price', 'Underlying Price', 'green' );

}

chartOnLoad();

$('.chart_dsgn').on('click',function(e){
    
    if( screen.width < 500 ){ return false; }
    
    var plot_data = $(this).data('plot_data');
    var plot_data_name = $(this).data('plot_data_name');
    var colorz = $(this).data('colorz');
    
    var full_screen = $(this).data('full_screen');
    
    if( full_screen ==  0 ){                
        
        $(this).data('full_screen', 1);
        
        $(this).css({
            width:screen.width + "px",
            height:screen.height + "px",
            margin:"0",
            border:"none"
        });
                
        $(".chart_dsgn").parents('div').hide();
        $("#" + this.id).parents('div').show();
        
        chartDisp( chart_data, plot_data, plot_data_name, colorz );
        
    }else{
        
        $(this).removeAttr("style");
        
        $("#" + this.id).parents('div').hide();
        $(".chart_dsgn").parents('div').show();
        
        $(this).data('full_screen', 0);
        
        chartOnLoad();
    }    
    
});