    /*
 * @author: ZAHIR
 * DESC: on select of expiry date
 */

$(document).on('click', '.change_expiry_date', function () {
    
    var searching_underlying_date = $(this).data('searching_underlying_date');
    var searching_expiry_date = $(this).data('searching_expiry_date');      
    
    $(".searching_underlying_date").attr('value', searching_underlying_date);
    $(".searching_expiry_date").attr('value', searching_expiry_date);
//    alert(underlying_date);
    
//    window.location.replace(location.href + '/'+ underlying_date + '/'+ expiry_date);
    
    $('.apply-btn-actionz').click();
});
    /*
 * @author: ZAHIR
 * DESC: on select of underlying time
 */

$(document).on('click', '.change_underlying_time', function () {
    
    var searching_underlying_date = $(this).data('searching_underlying_date');
    var searching_expiry_date = $(this).data('searching_expiry_date');      
    var searching_underlying_time = $(this).data('searching_underlying_time');      
    
    $(".searching_underlying_date").attr('value', searching_underlying_date);
    $(".searching_expiry_date").attr('value', searching_expiry_date);
    $(".searching_underlying_time").attr('value', searching_underlying_time);

    
    $('.apply-btn-actionz').click();
});

/*
 * @author : ZAHIR
 * DESC: On change under lying date
 */
function changeUnderLyingDate(e){
    
//  alert(e.target.value);
  $(".searching_underlying_date").attr('value', e.target.value);
  $(".searching_expiry_date").attr('value', '');
  $(".searching_underlying_time").attr('value', '');
  
  $('.apply-btn-actionz').click();
}
function zoomInBody(){
    
    var Page = document.getElementById('body_page'); var zoom = parseInt(Page.style.zoom) + 10 +'%'; Page.style.zoom = zoom; return false;

}

function zoomOutBody(){

    var Page = document.getElementById('body_page');
    var zoom = parseInt(Page.style.zoom) - 10 +'%'
    Page.style.zoom = zoom;
    return false;
    
}

/*
 * Option Gamma Calculate Start
 */

var last_t_bill = $("#last_t_bill").data("all");

console.log(' last_t_bill ' + last_t_bill);

var chart_data = $("#chart_data").data("all");

$.each(chart_data, function (index, value) {
   
//   if( value.strike_price != 10000 ){ return; }
   
//    console.log(value.underlying_date + ' ' + value.underlying_time);
//    console.log('calls_ltp : ' + value.calls_ltp);
//    console.log('puts_ltp : ' + value.puts_ltp);
//    console.log(new Date( value.expiry_date + ' 15:30:00') );
    
    var expiry = value.expiry_date + ' 15:30:00';
          
    var date_expiry = new Date(expiry),
    date_now = new Date();
    
//    console.log('date_expiry');
//    console.log(date_expiry);    
        
//    console.log('date_expiry');
//    console.log(date_expiry);
    
    var seconds = Math.floor((date_expiry - (date_now)) / 1000),
                minutes = Math.floor(seconds / 60),
                hours = Math.floor(minutes / 60),
                delta_t = (Math.floor(hours / 24)) / 365.0;
    
    if (hours < 24) {
        error = "Please select a later date and time . Expiry should be minimum 24 hours from now " + value.strike_price;
            
        console.log(error);
        
        $('.option_greek').addClass('d-none');
        
        return false;
        
    } else {
        
        var row_id = value.id;
        
        var spot = parseFloat(value.underlying_price),
            strike = parseFloat(value.strike_price),
            int_rate = parseFloat(last_t_bill);
        
        var calls_iv = parseFloat(value.calls_iv);
        var puts_iv = parseFloat(value.puts_iv);
        
        calcOptionGreek('call', calls_iv, delta_t, spot, strike, int_rate, puts_iv, false, row_id, value.calls_ltp, value.puts_ltp);
        
    }
    
});

/*
 * Option Gamma Calculate End
 */

function calcOptionGreek( put_or_call, volt, delta_t, spot, strike, int_rate, puts_iv=false, call_delta=false, row_id, calls_ltp=false, puts_ltp ){
    
//    if( strike != 10000 ){ return; }
    
    if (isNaN(spot) || isNaN(strike) || isNaN(volt) || ( volt ==0 ) ){
     
//        console.log("Data NA " + put_or_call + " , " + strike );
        
    }else{
    
    
        volt = volt / 100,
        int_rate = int_rate / 100;
        
        var d1 = (Math.log(spot / strike) + (int_rate + Math.pow(volt, 2) / 2) * delta_t) / (volt * Math.sqrt(delta_t)),
        d2 = (Math.log(spot / strike) + (int_rate - Math.pow(volt, 2) / 2) * delta_t) / (volt * Math.sqrt(delta_t));

//        console.log('d1 ' + put_or_call + ' , volt : ' + volt + " , delta_t: " + delta_t + " , strike: " +strike + " , int_rate: " + int_rate );
//        console.log(d1);
//        console.log('d2 ' + put_or_call + ' , volt : ' + volt + " , delta_t: " + delta_t + " , strike: " +strike + " , int_rate: " + int_rate );
//        console.log(d2);
        
        var fv_strike = (strike) * Math.exp(-1 * int_rate * delta_t);
        
//        console.log('strike : ' + strike);
        //For calculating CDF and PDF using gaussian library
        var distribution = gaussian(0, 1);

        
        if( put_or_call === 'call' ){
            
            //Premium Price
            var call_premium = spot * distribution.cdf(d1) - fv_strike * distribution.cdf(d2);
            
            //Option greeks
            var call_delta = distribution.cdf(d1);            
            $(".each_row_" + row_id + " td.calls_delta").text(call_delta.toFixed(3));
            
            var call_gamma = distribution.pdf(d1) / (spot * volt * Math.sqrt(delta_t));
            $(".each_row_" + row_id + " td.calls_gamma").text(call_gamma.toFixed(4));
            
            var call_vega = spot * distribution.pdf(d1) * Math.sqrt(delta_t) / 100;
            $(".each_row_" + row_id + " td.calls_vega").text(call_vega.toFixed(3));
            
            var call_theta = (-1 * spot * distribution.pdf(d1) * volt / (2 * Math.sqrt(delta_t)) - int_rate * fv_strike * distribution.cdf(d2)) / 365;
            $(".each_row_" + row_id + " td.calls_theta").text(call_theta.toFixed(3));
            
            var call_theta_decay_p = ( ( call_theta/calls_ltp ) * 100 );
            $(".each_row_" + row_id + " td.calls_theta_ltp_decay_p").text(Math.abs(call_theta_decay_p.toFixed(2)));
            
            var call_rho = fv_strike * delta_t * distribution.cdf(d2) / 100;
            $(".each_row_" + row_id + " td.calls_rho").text(call_rho.toFixed(3));
            
//            console.log("call_delta : " + call_delta.toFixed(3) );
            
            calcOptionGreek('put', puts_iv, delta_t, spot, strike, int_rate, false, call_delta, row_id, false, puts_ltp);
            
        }else if( put_or_call === 'put' ){
            
            //Premium Price
            var put_premium = fv_strike * distribution.cdf(-1 * d2) - spot * distribution.cdf(-1 * d1);
            
            //Option greeks
            var put_delta = call_delta - 1;
            $(".each_row_" + row_id + " td.puts_delta").text(put_delta.toFixed(3));
            
            var put_gamma = distribution.pdf(d1) / (spot * volt * Math.sqrt(delta_t));
            $(".each_row_" + row_id + " td.puts_gamma").text(put_gamma.toFixed(4));
            
            var put_vega = spot * distribution.pdf(d1) * Math.sqrt(delta_t) / 100;
            $(".each_row_" + row_id + " td.puts_vega").text(put_vega.toFixed(3));
            
            var put_theta = (-1 * spot * distribution.pdf(d1) * volt / (2 * Math.sqrt(delta_t)) + int_rate * fv_strike * distribution.cdf(-1 * d2)) / 365;
            $(".each_row_" + row_id + " td.puts_theta").text(put_theta.toFixed(3));
            
            var put_theta_decay_p = ( ( put_theta/puts_ltp ) * 100 );
            $(".each_row_" + row_id + " td.puts_theta_ltp_decay_p").text(Math.abs(put_theta_decay_p.toFixed(2)));
            
            var put_rho = -1 * fv_strike * delta_t * distribution.cdf(-1 * d2) / 100;
            $(".each_row_" + row_id + " td.puts_rho").text(put_rho.toFixed(3));
            
//            console.log("put_delta : " + put_delta.toFixed(3) );
        }
            
    }
}

$(".show_hide_bid_ask_qty_col").change(function() {
    
    if($(this).prop('checked') == true){
        
        $(".bid-ask").removeClass('d-none');
        
    }else{
        
        $(".bid-ask").addClass('d-none');
    }
});

$(".show_hide_oi_vol_col").change(function() {
    
    if($(this).prop('checked') == true){
        
        $(".oi-vol").removeClass('d-none');
        
    }else{
        
        $(".oi-vol").addClass('d-none');
    }
});