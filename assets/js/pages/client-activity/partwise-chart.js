var market_running = $("#market_running").data("val");

function chartDisp( chart_data, plot_data, plot_data_name, colorz ){

    // Visualization API with the 'corechart' package.
    google.charts.load('visualization', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawLineChart);

    function drawLineChart( ) {

//        console.log(chart_data);
//        var arrSales = [['Date', plot_data_name]]; 
        
        if( plot_data === "future_index_long_short" || plot_data === "future_stock_long_short"){
            
            var arrSales = [['Date', 'Long' , 'Short' ]];
            
        }else if( ( plot_data === "option_index_call_put_long_short" ) || ( plot_data === "option_stock_call_put_long_short" ) ){
            
            var arrSales = [['Date', 'Call Long' , 'Put Long', 'Call Short' , 'Put Short' ]];
        }else{
            var arrSales = [['Date', plot_data_name]];    // Define an array and assign columns for the chart.
        
        }

        // Loop through each data and populate the array.
        $.each(chart_data, function (index, value) {
            
            var date_or_time = new Date(value.market_date);
            
//            console.log(date_or_time);
            if( plot_data === 'future_index_long_short' ){
             
                arrSales.push([date_or_time, parseFloat(value.future_index_long), parseFloat(value.future_index_short) ]);
                
            }else if( plot_data === 'future_stock_long_short' ){
             
                arrSales.push([date_or_time, parseFloat(value.future_stock_long), parseFloat(value.future_stock_short) ]);
                
            }else if( plot_data === 'option_index_call_put_long_short' ){
             
                arrSales.push([date_or_time, parseFloat(value.option_index_call_long), parseFloat(value.option_index_put_long), parseFloat(value.option_index_call_short), parseFloat(value.option_index_put_short) ]);
                
            }else if( plot_data === 'option_stock_call_put_long_short' ){
             
                arrSales.push([date_or_time, parseFloat(value.option_stock_call_long), parseFloat(value.option_stock_put_long), parseFloat(value.option_stock_call_short), parseFloat(value.option_stock_put_short) ]);
                
            }

        });

        // Set chart Options.
        var options = {
            title: plot_data_name,
            colors: [colorz, 'red', 'blue', 'yellow'],
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

    chartDisp( chart_data, 'future_index_long_short', 'Future Index Long Short', 'green' );
    chartDisp( chart_data, 'future_stock_long_short', 'Future Stock Long Short', 'green' );
    
    chartDisp( chart_data, 'option_index_call_put_long_short', 'Option Index Call/Put Long/Short', 'green' );
    chartDisp( chart_data, 'option_stock_call_put_long_short', 'Option Stock Call/Put Long/Short', 'green' );

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
            display:"block",
            margin:"0 auto",
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