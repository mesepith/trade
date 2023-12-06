/*
 * @author : ZAHIR
 * DESC: On change investment date
 */
function changeStockDate(e, date_type) {
	//alert(date_type)
//        alert(e.target.value);

	if(date_type=='from'){
		
		$(".market_date").attr('value', e.target.value);
		
		loadFlatPickerDate(date_type, e.target.value);
	}else if(date_type=='to'){
		
		$(".market_date_to").attr('value', e.target.value);
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

$(".select_category").change(function() {
    
    var selected_chkbox = this.value;
    
//    $('.select_client_type').prop('checked', false); // Unchecks it
    $('#' + selected_chkbox + "_chkbox").prop('checked', true); // Checks it
    
//    alert(this.value);
    if(this.checked) {
        //Do stuff
    }
    
    $('.apply-btn-actionz').click();
});

$(".sel_date_period").change(function() {
    
    $(".market_date").attr('value', this.value);
    
    var today_date = $("#today_date").data("valz");
    
    $(".market_date_to").attr('value', today_date);
    
    $('.apply-btn-actionz').click();
});

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

