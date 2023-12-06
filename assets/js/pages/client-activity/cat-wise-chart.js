var market_running = $("#market_running").data("val");

function chartDisp( chart_data, plot_data, plot_data_name, colorz ){

    // Visualization API with the 'corechart' package.
    google.charts.load('visualization', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawLineChart);

    function drawLineChart( ) {

//        console.log(chart_data);
//        var arrSales = [['Date', plot_data_name]]; 
        
        if( plot_data === "buy_sale" ){
            
            var arrSales = [['Date', 'Buy' , 'Sale' ]];
            
        }

        // Loop through each data and populate the array.
        $.each(chart_data, function (index, value) {
            
            var date_or_time = new Date(value.market_date);
            
//            console.log(date_or_time);
            if( plot_data === 'buy_sale' ){
             
                arrSales.push([date_or_time, parseFloat(value.buy_value), parseFloat(value.sell_value) ]);
                
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

    chartDisp( chart_data, 'buy_sale', 'Buy Sale', 'green' );

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