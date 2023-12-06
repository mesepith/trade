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
            
            var date_or_time = new Date(value.underlying_date);
            
//            console.log(date_or_time);
            if( plot_data === 'close_price' ){
                
                if( market_running === 'live' ){
                    
                    arrSales.push([date_or_time, parseFloat(value.last_price) ]);
                    
                }else{
                    
                    arrSales.push([date_or_time, parseFloat(value.close_price) ]);
                
                }
                
            }else if( plot_data === 'total_traded_volume' ){
             
                arrSales.push([date_or_time, parseFloat(value.total_traded_volume) ]);
                
            }else if( plot_data === 'money_flow' ){
             
                arrSales.push([date_or_time, parseFloat(value.money_flow) ]);
                
            }else if( plot_data === 'oi' ){
             
                arrSales.push([date_or_time, parseFloat(value.oi) ]);
                
            }else if( plot_data === 'no_of_contracts_traded' ){
             
                arrSales.push([date_or_time, parseFloat(value.no_of_contracts_traded) ]);
                
            }else if( plot_data === 'total_turnover' ){
             
                arrSales.push([date_or_time, parseFloat(value.total_turnover) ]);
                
            }else if( plot_data === 'traded_volume' ){
             
                arrSales.push([date_or_time, parseFloat(value.traded_volume) ]);
                
            }else if( plot_data === 'vmap' ){
             
                arrSales.push([date_or_time, parseFloat(value.vmap) ]);
                            
            }else if( plot_data === 'daily_volatility' ){
             
                arrSales.push([date_or_time, parseFloat(value.daily_volatility) ]);
                
            }else if( plot_data === 'annual_volatility' ){
             
                arrSales.push([date_or_time, parseFloat(value.annual_volatility) ]);
                
            }else if( plot_data === 'total_buy_sell_quantity' ){
             
                arrSales.push([date_or_time, parseFloat(value.total_buy_quantity), parseFloat(value.total_sell_quantity) ]);
                
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

    chartDisp( chart_data, 'close_price', 'Close Price', 'green' );
    chartDisp( chart_data, 'money_flow', 'Money Flow', 'blue' );
    chartDisp( chart_data, 'oi', 'OI', 'blue' );
    chartDisp( chart_data, 'no_of_contracts_traded', 'No of contract Traded', 'blue' );
    chartDisp( chart_data, 'total_turnover', 'Total Turnover (Lakhs)', 'blue' );
    chartDisp( chart_data, 'traded_volume', 'Traded Volume', 'blue' );
    chartDisp( chart_data, 'vmap', 'VMAP', 'blue' );
    chartDisp( chart_data, 'total_no_of_trades', 'Total No of Trades', 'blue' );
    
    chartDisp( chart_data, 'daily_volatility', 'Daily Volatility', 'blue' );
    chartDisp( chart_data, 'annual_volatility', 'Annual Volatility', 'blue' );
    
    chartDisp( chart_data, 'total_buy_sell_quantity', 'Total Buy Sell Quantity', 'green' ); 

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
