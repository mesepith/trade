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

$(document).on('click', '.sort_by_rollover', function () {
   
     var rollover_sortby = $(this).data('rollover-sortby');
     
     $(".rollover_sortby").attr('name', 'rollover_sortby').attr('value', rollover_sortby);
     
     $('.apply-btn-actionz').click();
    
});

$(document).on('click', '.sort_by_rollcost', function () {
   
     var rollcost_sortby = $(this).data('rollcost-sortby');
     
     $(".rollcost_sortby").attr('name', 'rollcost_sortby').attr('value', rollcost_sortby);
     
     $('.apply-btn-actionz').click();
    
});