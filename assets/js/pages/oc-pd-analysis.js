/*
 * @author : ZAHIR
 * DESC: On change stock date
 */
function changeSectorDate(e, date_type) {
	//alert(date_type)
//        alert(e.target.value);

	if(date_type=='from'){
		
		$(".underlying_date_end").attr('value', e.target.value);
		
		loadFlatPickerDate(date_type, e.target.value);
	}else if(date_type=='to'){
		
		$(".underlying_date_end_to").attr('value', e.target.value);
		$('.apply-btn-actionz').click();
	}

	
}


$(document).ready(function () {
    
    loadFlatPickerDate();    
});

/*
 * @author: ZAHIR
 * DESC: Flatdate picker
 */
function loadFlatPickerDate( date_type =false, date=false ){
	//alert(date)
	
	var date_flat_pick_class= 'date_flat_pickz';
	
	if( date_type && date ){
		
		date_flat_pick_class = 'date_flat_pickz_to';
	}
	
	flatpickr("." + date_flat_pick_class, {
			altInput: true,
			altFormat: "F j, Y",
			dateFormat: "Y-m-d",
			minDate: date,
			maxDate: "today",
			"disable": [
			function(date) {
				// return true to disable, disable saturday and sunday
				return (date.getDay() === 0 || date.getDay() === 6);

			}
			],
			"locale": {
				"firstDayOfWeek": 1 // start week on Monday
			}
	});
}

$(document).on('click', '.select_expiry', function () {
   
     var expiry = $(this).data('expiry');
     
     $(".expiry").attr('name', 'expiry').attr('value', expiry);
     
     $('.apply-btn-actionz').click();
    
});


/* Chart Display Start */

var market_running = $("#market_running").data("val");

function chartDisp( chart_data, plot_data, plot_data_name, colorz ){

    // Visualization API with the 'corechart' package.
    google.charts.load('visualization', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawLineChart);

    function drawLineChart( ) {
        
        if( plot_data === "avg_decay"){
            
            var arrSales = [['Time' , 'Call Average Decay', 'Put Average Decay' ]];
        }
        
        // Loop through each data and populate the array.
        $.each(chart_data, function (index, value) {
            
            if( market_running === 'live' ){
              
                var date_or_time = value.underlying_date_end + ' ' + value.underlying_time_end;
              
            }else{
            
                var date_or_time = new Date(value.underlying_date_end);
            }
            
            if( plot_data === 'avg_decay' ){
                
                arrSales.push([date_or_time, parseFloat(value.call_avg_decay), parseFloat(value.put_avg_decay) ]);
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
    
    chartDisp( chart_data, 'avg_decay', 'Average Decay', 'green' );
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

/* Chart Display End */