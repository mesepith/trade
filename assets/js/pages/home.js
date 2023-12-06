/* 
 * @author: ZAHIR
 */

$(document).on('click', '.display_good_stock', function () {

    $(".are_all_good_stock_no").hide();
    $(".display_good_stock").addClass('d-none');
    $(".display_all_stock").removeClass('d-none');

});
$(document).on('click', '.display_all_stock', function () {

    $(".are_all_good_stock_no").show();
    $(".display_good_stock").removeClass('d-none');
    $(".display_all_stock").addClass('d-none');

});

/*
 * @author: ZAHIR
 * DESC: on sort by select
 */

$(document).on('click', '.sort_by', function () {
    
//    alert(this.attr("[data-sortby]"));
//    alert($(this).data('sortby'));
    
    var sortby = $(this).data('sortby');
    var select = $(this).data('select');
    var sortdate = $(this).data('sortdate');
    
    $(".sort_by_selection").attr('name', sortby).attr('value', select);
    $(".sort_date").attr('name', 'sort_date').attr('value', sortdate);
    
    $('.apply-btn-actionz').click();

});

/*
 *@author : ZAHIR
 *DESC: Display horizontal scroll  both at top and bottom of table
 **/
var arescrolling = 0;
function scroller(from,to) {
    if (arescrolling) return; // avoid potential recursion/inefficiency
    arescrolling = 1;
    // set the other div's scroll position equal to ours
    document.getElementById(to).scrollLeft =
            document.getElementById(from).scrollLeft;
    arescrolling = 0;
    
}

/*
 *@author : ZAHIR
 *DESC: Hide horizontal scroll at the top if bottom does not have horizontal scroll in table
 **/

$(function(){
    
    if(!($('#scrollme').hasHorizontalScrollBar())){
        
        $("#scroller").css('display','none');

    }
    
    /* Set the width of fake div according tables width start*/
    $(".fake-img").css('width', $('.stock-list-table').width());
    /* Set the width of fake div according tables width end*/
    
});

(function($) {
    $.fn.hasHorizontalScrollBar = function() {
        return this.get(0).scrollWidth > this.width();
    }
})(jQuery);

/*Hide horizontal scroll at the top if bottom does not have horizontal scroll in table End*/

/*
 * @author: ZAHIR
 * DESC: Set height of table to make its header fixed
 */

$(".table-responsive").css('height',(screen.height-180));

/* Set height of table to make its header fixed End*/

/*
 *@author : ZAHIR
 *DESC: Hide vertical scroll if the table does not have vertical scroll
 **/

$(function(){
    
    if(!($('.table-responsive').hasVerticalScrollBar())){

        $(".table-responsive").css('height','auto');
    }
});

(function($) {
    $.fn.hasVerticalScrollBar = function() {
        return this.get(0).scrollHeight > this.height();
    }
})(jQuery);


/* Hide vertical scroll if the table does not have vertical scroll End*/

/*
 * @author: ZAHIR
 * DESC: Delivery to traded quantity slider
 */
$(function () {
    
    var delivery_to_traded_quantity_min = $('.delivery_to_traded_quantity_min').attr('value');
    var delivery_to_traded_quantity_max = $('.delivery_to_traded_quantity_max').attr('value');
    
    $("#slider-3").slider({
        range: true,
        min: 1,
        max: 100,
        values: [delivery_to_traded_quantity_min, delivery_to_traded_quantity_max],
        slide: function (event, ui) {
            $("#delivery_to_traded_quantity_slider").val( ui.values[ 0 ] + " - " + ui.values[ 1 ]);
            $(".delivery_to_traded_quantity_min").attr('value', ui.values[ 0 ]);
            $(".delivery_to_traded_quantity_max").attr('value', ui.values[ 1 ]);
        }
    });
    $("#delivery_to_traded_quantity_slider").val($("#slider-3").slider("values", 0) +
            " - " + $("#slider-3").slider("values", 1));
});
         
/* Delivery to traded quantity slider end */

/*
 * @author: ZAHIR
 * DESC: On select delivery to traded quantity date
 */

$(document).on('click', '.delivery_to_traded_quantity_date_selct', function () {
    
    var select_date = $(this).data('date');
    $(".dtq__selected_date").html(' ' + select_date + ' : ');
    $(".delivery_to_traded_quantity_date").attr('value', select_date);
});
/*
 * @author: ZAHIR
 * DESC: On select total traded volume date
 */

$(document).on('click', '.total_traded_volume_date_selct', function () {
    
    var select_date = $(this).data('date');
    $(".ttv__selected_date").html(' ' + select_date + ' : ');
    $(".total_traded_volume_date").attr('value', select_date);
});
/*
 * @author: ZAHIR
 * DESC: On select delivery quantity date
 */

$(document).on('click', '.delivery_quantity_date_selct', function () {
    
    var select_date = $(this).data('date');
    $(".dq__selected_date").html(' ' + select_date + ' : ');
    $(".delivery_quantity_date").attr('value', select_date);
});
