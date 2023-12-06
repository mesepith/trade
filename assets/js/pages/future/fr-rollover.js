/*
 * @author : ZAHIR
 * DESC: On change stock date
 */
function changeStockDate(e, date_type) {
	//alert(date_type)
//        alert(e.target.value);

	if(date_type=='from'){
		
		$(".searching_underlying_date").attr('value', e.target.value);
		
		loadFlatPickerDate(date_type, e.target.value);
	}else if(date_type=='to'){
		
		$(".searching_underlying_date_to").attr('value', e.target.value);
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

/*
 * Display Average and Total section Start
 */

function show_avg_total_data_chkbox( flag_value ){
    
    if( flag_value == 'yes' ){
        
        $(".db_data").hide();
        
    }else if( flag_value == 'no' ){
        
        $(".db_data").show();
    }
}

var avg_total_data_flag = $("#show_avg_total_data_chkbox").val();

show_avg_total_data_chkbox( avg_total_data_flag );

$(".show_avg_total_data").change(function() {
    
    if( this.value == 'yes' ){
        
        $(this).val('no');
        
        $(".db_data").show();
        
    }else{
        
        $(".db_data").hide();
        
        $(this).val('yes');
    }
});

/*
 * Display Average and Total section End
 */

$(".sel_date_period").change(function() {
    
    $(".searching_underlying_date").attr('value', this.value);
    
    var today_date = $("#today_date").data("valz");
    
    $(".searching_underlying_date_to").attr('value', today_date);
    
    $('.apply-btn-actionz').click();
});
