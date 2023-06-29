var BASE_URL = "http://laravel.test/api";
var GOOGLE_PUBLIC = "6LdclqoUAAAAAAuPnnM8GL5erpT9Mg4si_BXq1aA";
function validateCaptcha($ = jQuery, token, element) {
    $.ajax({
        url: BASE_URL+"/validate-captcha",
        type: "POST",
        data: {captcha_token: token},
        success: function(data)
        {
            bookHotel($, element);
        },
        error: function(data) {
            $('#response').show().html('Invalid Captcha!')
        }
    });
}
function bookHotel($ = jQuery, element) {
    $.ajax({
        url: BASE_URL+"/book",
        type: "POST",
        data: element.serialize(),
        success: function(data)
        {
            $(element).unbind('submit').submit();
           $('#response').show().html('Booking was successful')
        },
        error: function(data) {
            $('#response').show().html('Invalid Captcha!')
        }
    });
}

function fetchRoomTypes($ = jQuery) {
    $.ajax({
        url: BASE_URL+"/room_types",
        type: "GET",
        contentType: false,
        cache: false,
        processData:false,
        success: function(data)
        {
            var options = ""
            var data = data.data
            // console.log(JSON.stringify(data, null, 4))
            for(var i in data) {
                options += ` <div class="form-group agileits_circles">
                        <div class="wthree_radio">
                            <span class="fa fa-home" aria-hidden="true"></span>
                            <label class="radio">
                                <input value="${data[i].id}" type="radio" name="room_type_id" id="room_type_id">
                                <i></i>${data[i].name}
                            </label>
                        </div>
                    </div>`
            }

            $('#room_types').html(options)
        },
        error: function (data) {
            console.log("Error", JSON.stringify(data, null, 4))
        }
    });
}

function fetchRoomCapacities($ = jQuery) {
    $.ajax({
        url: BASE_URL+"/room_capacities",
        type: "GET",
        contentType: false,
        cache: false,
        processData:false,
        success: function(data)
        {
            var options = ""
            var data = data.data
            for(var i in data) {
                options += ` <div class="form-group agileits_circles">
                        <div class="wthree_radio">
                            <span class="fa fa-icon-home" aria-hidden="true"></span>
                            <label class="radio">
                                <input value="${data[i].id}" type="radio" name="room_capacity_id" id="room_capacity_id" >
                                <i></i>${data[i].name}
                            </label>
                        </div>
                    </div>`
            }

            $('#room_capacities').html(options)
        }
    });
}

function fetchRooms($ = jQuery) {
    var room_type_id = 0;
    var room_capacity_id = 0;
    if($('#room_type_id').val()) room_type_id = $('#room_type_id').val()
    if($('#room_capacity_id').val()) room_capacity_id = $('#room_capacity_id').val()
    $.ajax({
        url: BASE_URL+"/rooms?room_type_id="+room_type_id+"&room_capacity_id="+room_capacity_id,
        type: "GET",
        contentType: false,
        cache: false,
        processData:false,
        success: function(data)
        {
            var options = ""
            var data = data.data
            for(var i in data) {
                options += "<option value='"+data[i].id+"'>"+data[i].name+" ("+data[i].name+" USD)</option>"
            }

            $('#rooms').html(options)
        }
    });
}

function fetchPrices($ = jQuery) {
    var room_type_id = 0;
    var room_capacity_id = 0;
    var room_id = 0;
    var start_date = 0;
    var end_date = 0;
    if($('#room_type_id').val()) room_type_id = $('#room_type_id').val()
    if($('#room_capacity_id').val()) room_capacity_id = $('#room_capacity_id').val()
    if($('#room_id').val()) room_id = $('#room_id').val()
    if($('#date_from').val()) start_date = $('#date_from').val()
    if($('#date_to').val()) end_date = $('#date_to').val()
    $.ajax({
        url: BASE_URL+"/prices?room_type_id="+room_type_id+"&room_capacity_id="+room_capacity_id+"&room_id="+room_id+"&date_from="+start_date+"&date_to="+end_date,
        type: "GET",
        contentType: false,
        cache: false,
        processData:false,
        success: function(data)
        {
            var options = ""
            var data = data.prices
            for(var i in data) {
                options += "<option value='"+data[i].id+"'>"+data[i].amount+" USD</option>"
            }

            $('#prices').html(options)
        }
    });
}

function fetchStates(id, $ = jQuery) {
    $.ajax({
        url: BASE_URL+"/states/"+id,
        type: "GET",
        contentType: false,
        cache: false,
        processData:false,
        success: function(data)
        {
            var options = ""
            var data = data.data
            for(var i in data) {
                options += `<option value="${data[i].id}">${data[i].name}</option>`
            }

            $('#states').html(options)
            $('.states').html(options)
        }
    });
}

function fetchCities(id, $ = jQuery) {
    $.ajax({
        url: BASE_URL+"/cities/"+id,
        type: "GET",
        contentType: false,
        cache: false,
        processData:false,
        success: function(data)
        {
            var options = ""
            var data = data.data
            for(var i in data) {
                options += `<option value="${data[i].id}">${data[i].name}</option>`
            }

            $('#cities').html(options)
            $('.cities').html(options)
        }
    });
}

function fetchCountries($ = jQuery) {
    $.ajax({
        url: BASE_URL+"/countries",
        type: "GET",
        contentType: false,
        cache: false,
        processData:false,
        success: function(data)
        {
            var options = ""
            var data = data.data
            for(var i in data) {
                options += `<option value="${data[i].id}">${data[i].name}</option>`
            }

            $('#countries').html(options)
        }
    });
}