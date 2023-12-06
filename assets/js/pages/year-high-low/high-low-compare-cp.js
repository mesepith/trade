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
 * @author: ZAHIR
 * DESC: on sort by select
 */

$(document).on('click', '.sort_by', function () {
    
    var sortby = $(this).data('sortby');
    var select = $(this).data('select');
    
    $(".sort_by_selection").attr('name', sortby).attr('value', select);
    
    $('.apply-btn-actionz').click();

});
$(document).on('click', '.sort_by_date', function () {
    
    var sortby = $(this).data('sortby');
    var select = $(this).data('select');
    
    $(".sort_by_selection_date").attr('name', sortby).attr('value', select);
    
    $('.apply-btn-actionz').click();

});
