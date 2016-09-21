$(function() {

    /*
    * -- enable History
    */
    var History = window.History;
    if (History.enabled) {
        State = History.getState();
        // set initial state to first page that was loaded
        History.pushState({urlPath: window.location.pathname}, $("title").text(), State.urlPath);
        State = History.getState();
        console.log("History : " + State.data.urlPath);
    } else {
        return false;
    }


    /*
    * -- handle history events
    */
    History.Adapter.bind(window, 'statechange', function() {

        var State = History.getState();

        /*
        *  Handle dashboard and event index and contact index
        */
        if (State.data.urlPath === '/' || State.data.urlPath === '/event/' || State.data.urlPath === '/contact/') {
            $.ajax({
                type: "GET",
                url: State.url,
                cache: true,
                success: function(data){
                    $("#ttm_contentWithPanel").html(data);
                }
            });
        }
        /*
        *  Handle calendar navigation
        */
        else if ( State.url.match(/month/) || State.url.match(/week/) || State.url.match(/day/) ) {

            $.ajax({
                type: "GET",
                url: State.url,
                cache: true,
                success: function(data){
                    $("#ttm_calendarContainer").html(data);
                    $.ttm_sizeCalendar();
                    // $("#ttm_panel").toggleClass("showPanel");
                }
            });
        }
        /*
        *  handle event new/show and new/contact show
        */
        else if ( State.data.urlPath.match(/new/) || /^\/event\/\d+$/.test(State.data.urlPath) || /^\/contact\/\d+$/.test(State.data.urlPath) ) {

            $.ajax({
                type: "GET",
                url: State.url,
                cache: true,
                success: function(data){
                    $('body').append(data);
                }
            });
        }
        /*
        *  handle pagination
        */
        else if ( State.data.urlPath.match(/\?page/) || State.data.urlPath.match(/\?sort/) ) {

            $.ajax({
                type: "GET",
                    url: State.url,
                })
                .done(function( msg ) {
                    $('#ttm_contentWithPanel').html(msg);
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                    sizeTable();
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                });
        }

        // Log the history object to your browser's console
        console.log("History : " + State.data.urlPath);
    });


    /*
    * -- small screens
    *
    * -- close calendar quick nav - click on close icon
    */
    $(document).on( 'click' , '#dateDisplay span', function(e) {

        $("#ttm_panel").toggleClass("showPanel");
    });


    /*
    * -- small screens
    *
    * -- show calendar quick nav
    */
    $(document).on( 'click' , '#mobilePanel', function (e) {

        e.preventDefault();

        // hide mobile menu
        if ($(".showForMediumInlineBlock").hasClass('showMenu')) {
            showMobileMenu();
        }

        // size panel calendar
        $("#PanelMonthCal td").toggleClass("sizeMobileNavCalendar");

        // show panel
        $("#ttm_panel").toggleClass("showPanel");
    });


    /*
    * -- small screens
    *
    * -- show mobile menu
    */
    $(document).on( 'click' , '#mobileMenu, #closeMenu a', function(e) {

        e.preventDefault();

        if ($("#ttm_panel").hasClass("showPanel")) {
            $("#ttm_panel").toggleClass("showPanel");
        }

        showMobileMenu();
    });


    /*
    * -- clickable tr
    */
    $(document).on( 'click' , 'tr[data-href]:not(.no-ajax)', function(e) {
        var url = $(this).data('href');
        History.pushState({urlPath: url}, null, url);
    });

    /*
    * -- clickable tr no ajax
    */
    $(document).on( 'click' , 'tr[data-href].no-ajax', function(e) {
        var url = $(this).data('href');
        window.location.href = url;
    });


    /*
    * -- close ajax frame
    */
    $(document).on( 'click' , '#ajaxFrame, #ajaxFrame #eventBackButton', function (e) {

        e.preventDefault();
        e.stopImmediatePropagation();

        $('#ajaxFrame').remove();

        var referer = History.getStateByIndex(History.getCurrentIndex() - 1).data.urlPath;

        History.pushState({urlPath: referer}, null, referer);
    });


    /*
    * -- prevent closing ajax frame when clicking on ajaxContent
    */
    $(document).on( 'click' , '#ajaxContent', function (e) {
        return false;
    });


    /*
    * -- re-enable contact and event checkboxes after above
    */
    $(document).on('click', "#timetm_contactbundle_contact_client, #timetm_contactbundle_contact_company, #timetm_eventbundle_event_fullday" , function(e) {
        e.stopPropagation();
        return true;
    });


    /*
    * -- handle main calendar prev/next links
    */
    $(document).on( "click" , "#panelCalendarNav td a, #panelCalendarMode td a", function (e) {

        e.preventDefault();

        var url = $(this).attr('href');
        if (url) {
            History.pushState({urlPath: url}, null, url);
        }
        console.log( 'clicked in navigation : ' + url);
    });


    /*
    * -- handle "quick navigation" prev/next links
    */
    $(document).on( "click" , "#panelCalendarQuickNav td a", function (e) {
        e.preventDefault();
        var url = $(this).attr('href') + '/panel';
        $.ajax({
            type: "GET",
            url: url,
            cache: true,
            success: function(data){
                $("#panelCalendar").html(data);
            }
        });
        console.log( 'clicked in quick nav prev/next : ' + url);
    });


    /*
    * -- handle "quick navigation" day links
    */
    $(document).on( "click" , "#PanelMonthCal td a", function (e) {

        e.preventDefault();
        var url = $(this).attr('href');

        if ( url.match(/month/) !== null ) {
            var day = $(this).val();
            url += '/content';
            console.log( 'matched ' + url);
        }

        History.pushState({urlPath: url}, null, url);
        console.log( 'clicked in quick nav day : ' + url);
    });


    /*
    * -- handle click on event
    */
    $(document).on( "click" , "#ttm_calendar td a.event", function (e) {

        e.preventDefault();

        var url = $(this).attr('href');

        History.pushState({urlPath: url}, null, url);
        console.log( 'clicked in calendar event : ' + url);
    });


    /*
    * -- click on event edit in ajaxframe
    */
    $(document).on( "click" , "#ajaxFrame .button", function (e) {

        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            type: "GET",
            url: url,
            cache: true,
            success: function(data) {
                $('#ajaxFrame').remove();
                $('body').append(data);
            }
        });

        console.log( 'clicked on button on ajaxframe : ' + url);
    });


    /*
    * -- click on new contact, new event
    *    .no-ajax (exlude clic on profile buttons)
    */
    $(document).on( 'click' , 'a.button:not(#ajaxFrame .button, .no-ajax)', function (e) {

        e.preventDefault();

        var url = $(this).attr('href');
        History.pushState({urlPath: url}, null, url);
    });


    /*
    * -- handle ajax create contact - send create form
    */
    $(document).on( 'click' , '#ajaxFrame #timetm_contactbundle_contact_save', function (e) {

        var form = $('#contact_save');

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            success: function(data) {
                $("#ajaxFrame").remove();
                History.pushState({urlPath: data.referer}, null, data.referer);
            },
            error:function(data) {
                alert('form send error');
                $('#ajaxFrame').remove();
                $('body').append(data.responseText);
            }
        });
    });


    /*
    * -- handle agenda switch
    */
    $(document).on( 'change' , '#agendaSwitch', function (e) {

        console.log('fired');

        var form = $('#agendaSwitch');

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            success: function(data) {

                if (data.referer.match(/month/) || data.referer.match(/day/) || data.referer.match(/week/) ) {

                    $.ajax({
                        type: "GET",
                        url: data.referer,
                        cache: true,
                        success: function(data2) {

                            $("#ttm_calendarContainer").html(data2);
                            $.ttm_sizeCalendar();
                        }
                    });
                }
            },
            error:function(data) {
                alert('form send error');
                $('body').append(data.responseText);
            }
        });

    });

    sizeTable();

    // on resize
    var timer;

    $(window).resize(function() {

        if(timer) {
            window.clearTimeout(timer);
        }

        timer = window.setTimeout(function() {
            sizeTable();
        }, 30);
    });


    /*
    * -- handle pagination and sortable
    */
    $(document).on('click', ".pagination a, a.sortable" , function(e) {

        e.preventDefault();

        var url = $(this).attr('href');

        History.pushState({urlPath: url}, null, url);
    });

});

function showMobileMenu() {

    $(".showForMediumInlineBlock").toggleClass('showMenu');
    $(".showForMediumInlineBlock li").toggleClass('showMenuItem');
    $("#logo").toggleClass('hide');
    $("#closeMenu").toggleClass('show');
}

function sizeTable() {

    if ($( window ).height() > 700 ) {

        var rowCount = $('#contactList tr[data-href]').length;

        var trHeight =
            (
                $("#ttm_contentWithPanel").height() -
                $("#ttm_contentWithPanel h1").height() -
                $('#ttm_contentWithPanel tr').first().height() -
                $('#ttm_contentWithPanel tr').last().height() -
                $('#ttm_contentWithPanel tr:last').prev().height()
            ) / ( rowCount  );

        $('#contactList tr[data-href]').each(function() {
            $( this ).css('height', trHeight);
        });
    }
}
