
    /*
     * @author : ZAHIR
     * DESC: On change stock date
     */
    function changeSectorDate(e, date_type) {
        //alert(date_type)
        //        alert(e.target.value);

        if (date_type == 'from') {
            
            $(".underlying_date").attr('value', e.target.value);
            $(".underlying_date").attr('value', e.target.value);

            loadFlatPickerDate(e.target.value);
            
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
    function loadFlatPickerDate(date) {
        //alert(date)

        var date_flat_pick_class = 'date_flat_pickz';

        flatpickr("." + date_flat_pick_class, {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            minDate: date,
            maxDate: "today",
            "disable": [
                function (date) {
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
    
//    alert($(this).data('sortby'));
//    alert($(this).data('sortby'));
    
    var sortby = $(this).data('sortby');
    var select = $(this).data('select');
    
    $(".sort_by_selection").attr('name', sortby).attr('value', select);
    
    $('.apply-btn-actionz').click();

});

/*
 * @author: ZAHIR
 * DESC: Apply custom condition
 */

$(document).on('click', '.apply_condition', function () {
    
//    alert($(this).data('sortby'));
//    alert($(this).data('sortby'));
    
    var condition = $(this).data('condition');
    
//    $(".custom_condition").attr('name', 'custom_condition').attr('value', condition);
    $(".custom_condition").attr('value', condition);
    
    $('.apply-btn-actionz').click();

});

/*
 * @author: ZAHIR
 * DESC: Bullish Probability slider
 */
$(function () {
    
    var bullish_probability_min = $('.bullish_probability_min').attr('value');
    var bullish_probability_max = $('.bullish_probability_max').attr('value');
    
    $("#slider-3").slider({
        range: true,
        min: 0,
        max: 100,
        values: [bullish_probability_min, bullish_probability_max],
        slide: function (event, ui) {
            $("#bullish_probability_slider").val( ui.values[ 0 ] + " - " + ui.values[ 1 ]);
            
            $(".bullish_probability_min").attr('name', 'bullish_probability_min').attr('value', ui.values[ 0 ]);
            $(".bullish_probability_max").attr('name', 'bullish_probability_max').attr('value', ui.values[ 1 ]);
        }
    });
    $("#bullish_probability_slider").val($("#slider-3").slider("values", 0) +
            " - " + $("#slider-3").slider("values", 1));
});

/*
 * @author: ZAHIR
 * DESC: Bearish Probability slider
 */
$(function () {
    
    var bearish_probability_min = $('.bearish_probability_min').attr('value');
    var bearish_probability_max = $('.bearish_probability_max').attr('value');
    
    $("#bearish-slider-3").slider({
        range: true,
        min: 0,
        max: 100,
        values: [bearish_probability_min, bearish_probability_max],
        slide: function (event, ui) {
            $("#bearish_probability_slider").val( ui.values[ 0 ] + " - " + ui.values[ 1 ]);
            
            $(".bearish_probability_min").attr('name', 'bearish_probability_min').attr('value', ui.values[ 0 ]);
            $(".bearish_probability_max").attr('name', 'bearish_probability_max').attr('value', ui.values[ 1 ]);
        }
    });
    $("#bearish_probability_slider").val($("#bearish-slider-3").slider("values", 0) +
            " - " + $("#bearish-slider-3").slider("values", 1));
});

/*
 * Search by time
 */
$(document).on('click', '.change_script_start_time', function () {
    
    var script_start_time = $(this).data('script_start_time');    
    
    $(".script_start_time").attr('value', script_start_time);
    
    $('.apply-btn-actionz').click();

});