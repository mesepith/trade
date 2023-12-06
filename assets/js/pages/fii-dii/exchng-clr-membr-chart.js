var market_running = $("#market_running").data("val");

function chartDisp( chart_data, plot_data, plot_data_name, colorz ){

    // Visualization API with the 'corechart' package.
    google.charts.load('visualization', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawLineChart);

    function drawLineChart( ) {

//        console.log(chart_data);
//        var arrSales = [['Date', plot_data_name]]; 
        
//        if( plot_data === "buy_sale" ){
            
            var arrSales = [['Date', plot_data_name ]];
            
//        }

        // Loop through each data and populate the array.
        $.each(chart_data, function (index, value) {
            
            var date_or_time = new Date(value.market_date);
            
//            console.log(date_or_time);
            if( plot_data === 'total_volume' ){
             
                arrSales.push([date_or_time, parseFloat(value.total_volume) ]);
                
            }else if( plot_data === 'total_turnover' ){
             
                arrSales.push([date_or_time, parseFloat(value.total_turnover) ]);
                
            }else if( plot_data === 'index_futures_vol' ){
             
                arrSales.push([date_or_time, parseFloat(value.index_futures_vol) ]);
                
            }else if( plot_data === 'index_futures_trnvr' ){
             
                arrSales.push([date_or_time, parseFloat(value.index_futures_trnvr) ]);
                                
            }else if( plot_data === 'stock_futures_vol' ){
             
                arrSales.push([date_or_time, parseFloat(value.stock_futures_vol) ]);
                
            }else if( plot_data === 'stock_futures_trnvr' ){
             
                arrSales.push([date_or_time, parseFloat(value.stock_futures_trnvr) ]);
                
            }else if( plot_data === 'index_option_vol' ){
             
                arrSales.push([date_or_time, parseFloat(value.index_option_vol) ]);
                
            }else if( plot_data === 'index_option_trnvr' ){
             
                arrSales.push([date_or_time, parseFloat(value.index_option_trnvr) ]);
                
            }else if( plot_data === 'index_option_trnvr_prm' ){
             
                arrSales.push([date_or_time, parseFloat(value.index_option_trnvr_prm) ]);
                
            }else if( plot_data === 'stock_option_vol' ){
             
                arrSales.push([date_or_time, parseFloat(value.stock_option_vol) ]);
                
            }else if( plot_data === 'stock_option_trnvr' ){
             
                arrSales.push([date_or_time, parseFloat(value.stock_option_trnvr) ]);
                
            }else if( plot_data === 'stock_option_trnvr_prm' ){
             
                arrSales.push([date_or_time, parseFloat(value.stock_option_trnvr_prm) ]);
                
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

    chartDisp( chart_data, 'total_volume', 'Total Volume', 'green' );
    chartDisp( chart_data, 'total_turnover', 'Total Turnover', 'green' );
    
    chartDisp( chart_data, 'index_futures_vol', 'Index Futures Volume', 'green' );
    chartDisp( chart_data, 'index_futures_trnvr', 'Index Futures Turnover', 'green' );
    chartDisp( chart_data, 'stock_futures_vol', 'Stock Futures Volume', 'green' );
    chartDisp( chart_data, 'stock_futures_trnvr', 'Stock Futures Turnover', 'green' );
    chartDisp( chart_data, 'index_option_vol', 'Index Option Volume', 'green' );
    chartDisp( chart_data, 'index_option_trnvr', 'Index Option Turnover', 'green' );
    chartDisp( chart_data, 'index_option_trnvr_prm', 'Index Option Turnover Premium', 'green' );
    chartDisp( chart_data, 'stock_option_vol', 'Stock Option Volume', 'green' );
    chartDisp( chart_data, 'stock_option_trnvr', 'Stock Option Turnover', 'green' );
    chartDisp( chart_data, 'stock_option_trnvr_prm', 'Stock Option Turnover Premium', 'green' );

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