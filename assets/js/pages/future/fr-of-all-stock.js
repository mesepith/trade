    /*
 * @author: ZAHIR
 * DESC: on select of expiry date
 */

$(document).on('click', '.change_expiry_date', function () {
    
    var searching_underlying_date = $(this).data('searching_underlying_date');
    var searching_expiry_date = $(this).data('searching_expiry_date');      
    
    $(".searching_underlying_date").attr('value', searching_underlying_date);
    $(".searching_expiry_date").attr('value', searching_expiry_date);
    
    $('.apply-btn-actionz').click();
});


/*
 * @author : ZAHIR
 * DESC: On change stock date
 */
function changeStockDate(e, date_type) {
    
	if(date_type=='from'){
		
            $(".searching_underlying_date").attr('value', e.target.value);
		
            loadFlatPickerDate(date_type, e.target.value);
            
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

$(document).on('click', '.sort_by_turnover', function () {
   
     var turnover_sortby = $(this).data('turnover-sortby');
     
     $(".turnover_sortby").attr('name', 'turnover_sortby').attr('value', turnover_sortby);
     
     $('.apply-btn-actionz').click();
    
});

$(document).on('click', '.sort_by_volume', function () {
   
     var volume_sortby = $(this).data('volume-sortby');
     
     $(".volume_sortby").attr('name', 'volume_sortby').attr('value', volume_sortby);
     
     $('.apply-btn-actionz').click();
    
});

$(document).on('click', '.sort_by_oi', function () {
   
     var oi_sortby = $(this).data('oi-sortby');
     
     $(".oi_sortby").attr('name', 'oi_sortby').attr('value', oi_sortby);
     
     $('.apply-btn-actionz').click();
    
});

$(document).on('click', '.sort_by_change_oi', function () {
   
     var change_oi_sortby = $(this).data('change-oi-sortby');
     
     $(".change_oi_sortby").attr('name', 'change_oi_sortby').attr('value', change_oi_sortby);
     
     $('.apply-btn-actionz').click();
    
});

$(document).on('click', '.sort_by_change_oi_p', function () {
   
     var change_oi_p_sortby = $(this).data('change-oi-p-sortby');
     
     $(".change_oi_p_sortby").attr('name', 'change_oi_p_sortby').attr('value', change_oi_p_sortby);
     
     $('.apply-btn-actionz').click();
    
});

$(document).on('click', '.sort_by_daily_volatility', function () {
   
     var daily_volatility_sortby = $(this).data('daily-volatility-sortby');
     
     $(".daily_volatility_sortby").attr('name', 'daily_volatility_sortby').attr('value', daily_volatility_sortby);
     
     $('.apply-btn-actionz').click();
    
});