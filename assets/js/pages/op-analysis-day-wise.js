/*
 * @author : ZAHIR
 * DESC: On change stock date
 */
function changeSectorDate(e, date_type) {
    //alert(date_type)
    //        alert(e.target.value);

    if (date_type == 'from') {

        $(".underlying_date_end").attr('value', e.target.value);

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