/*
 * @author : ZAHIR
 * DESC: On change investment date
 */
function changeStockDate(e, date_type) {
	//alert(date_type)
//        alert(e.target.value);
        
    var company_id = $('.company_id').val();
    
    var acq_disp_name = $('.acq_disp_name').data('valz');
    
    if( company_id > 0 || ( ( typeof(acq_disp_name) !== 'undefined' ) && acq_disp_name !== '' ) ){
        
        if(date_type =='from'){
		
		$(".broadcaste_date").attr('value', e.target.value);
		
		loadFlatPickerDate(date_type, e.target.value);
	}else if(date_type=='to'){
		
		$(".broadcaste_date_to").attr('value', e.target.value);
		$('.apply-btn-actionz').click();
	}
        
    }else{
        
        if(date_type =='from'){

            $(".broadcaste_date").attr('value', e.target.value);

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

$(".select_acq_disp").change(function() {
    
    $('.apply-btn-actionz').click();
});

$(".select_acq_mode").change(function() {
    
    $('.apply-btn-actionz').click();
});

$(".sum_sec_val_by_comp").change(function() {
    
    $('.apply-btn-actionz').click();
});

$(document).on('click', '.sort_by_security_val', function () {
   
     var security_sortby = $(this).data('security-sortby');
//     alert(sector);
     
     $(".security_sortby").attr('name', 'security_sortby').attr('value', security_sortby);
     
     $('.apply-btn-actionz').click();
    
});

$(".sel_date_period").change(function() {
    
    $(".broadcaste_date").attr('value', this.value);
    
    var today_date = $("#today_date").data("valz");
    
    $(".broadcaste_date_to").attr('value', today_date);
    
    $('.apply-btn-actionz').click();
});