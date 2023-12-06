/*
 * @author : ZAHIR
 * DESC: On change investment date
 */
function changeStockDate(e, date_type) {
	//alert(date_type)
//        alert(e.target.value);
        
    var company_id = $('.company_id').val();
    
    var client_name = $('.client_name').data('valz');
    
    if( company_id > 0 || ( ( typeof(client_name) !== 'undefined' ) && client_name !== '' ) ){
        
        if(date_type =='from'){
		
		$(".market_date").attr('value', e.target.value);
		
		loadFlatPickerDate(date_type, e.target.value);
	}else if(date_type=='to'){
		
		$(".market_date_to").attr('value', e.target.value);
		$('.apply-btn-actionz').click();
	}
        
    }else{
        
        if(date_type =='from'){

            $(".market_date").attr('value', e.target.value);

            loadFlatPickerDate(date_type, e.target.value);

            $('.apply-btn-actionz').click();
        }
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
//				return (date.getDay() === 0 || date.getDay() === 6);

			}
			],
			"locale": {
				"firstDayOfWeek": 1 // start week on Monday
			}
	});
}

$(".select_exchange").change(function() {
    
    $('.apply-btn-actionz').click();
});

$(".select_deal").change(function() {
    
    $('.apply-btn-actionz').click();
});

$(".select_buy_or_sale").change(function() {
    
    $('.apply-btn-actionz').click();
});

$(document).on('click', '.sort_by_quantity_traded', function () {
   
     var quantity_traded_sortby = $(this).data('quantity-traded-sortby');
//     alert(sector);
     
     $(".quantity_traded_sortby").attr('name', 'quantity_traded_sortby').attr('value', quantity_traded_sortby);
     
     $('.apply-btn-actionz').click();
    
});