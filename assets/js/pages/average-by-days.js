$(".sel_date_period").change(function() {
    
    $('.apply-btn-actionz').click();
});


$(document).on('click', '.select_tb_clm', function () {
    
    var tb_column = $(this).data('tb-clm');
    
    $(".all_clmn_tb").hide();
    $(".tb_clm_" + tb_column).show();
    
    $(".selc-clm").html('Column - ' + tb_column.replace(/_/g, " "));
    
//    $(".tb_column").attr('name', 'tb_column').attr('value', tb_column);
//
//    $('.apply-btn-actionz').click();
    
});
$(document).on('click', '.select_tbl_or_chart', function () {
    
    var tb_or_chart = $(this).data('tb-or-chart');
    
    $(".table_only").hide();
    $(".chart_only").hide();
    
    $("."+tb_or_chart+"_only").show();
    
    $(".selc-tb-or-chart").html('Show Only- ' + tb_or_chart );
    
});