/*
 * @author : ZAHIR
 * DESC: On change investment date
 */
function changeStockDate(e, date_type) {
	//alert(date_type)
//        alert(e.target.value);
        
    var company_id = $('.company_id').val();
    
    if( company_id > 0){
        
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

$(document).on('click', '.sort_by_encumb_p_val', function () {
   
     var encumb_p_sortby= $(this).data('encumb-p-sortby');
     
     $(".encumb_p_sortby").attr('name', 'encumb_p_sortby').attr('value', encumb_p_sortby);
     
     $('.apply-btn-actionz').click();
    
});

$(document).on('click', '.sort_by_dmat_pldg_p_val', function () {
   
     var dmat_pldg_p_sortby= $(this).data('dmat-pldg-p-sortby');
     
     $(".dmat_pldg_p_sortby").attr('name', 'dmat_pldg_p_sortby').attr('value', dmat_pldg_p_sortby);
     
     $('.apply-btn-actionz').click();
    
});

$(document).on('click', '.sort_by_prmtr_hldng_p_val', function () {
   
     var prmtr_hldng_p_sortby= $(this).data('prmtr-hldng-p-sortby');
//     alert(prmtr_hldng_p_sortby);
     
     $(".prmtr_hldng_p_sortby").attr('name', 'prmtr_hldng_p_sortby').attr('value', prmtr_hldng_p_sortby);
     
     $('.apply-btn-actionz').click();
    
});
