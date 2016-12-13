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

        var url = State.data.urlPath;

        // setPageTitle(url);


        /*
        *  Handle dashboard and event index and contact index
        */
        if (url === '/' || url === '/event/' ||
            url === '/contact/' || url === '/task/') {

            $.ajax({
                type: "GET",
                url: url,
                cache: true,
                success: function(data){
                    $("#ttm_contentWithPanel").html(data);
                    setPageTitle(url);
                    if (url === '/contact/' || url === '/task/') {
                        $.ttm_sizePaginatedTable();
                    }
                }
            });
        }
        /*
        *  Handle calendar navigation
        */
        else if ( url.match(/month/) || url.match(/week/) || url.match(/day/) ) {

            $.ajax({
                type: "GET",
                url: url,
                cache: true,
                success: function(data) {
                    $("#ttm_calendarContainer").html(data);
                    $.ttm_sizeCalendar();
                    // $("#ttm_panel").toggleClass("showPanel");
                    $('title').html('TimeTM - Calendar - ' + $("#dateDisplay").text());
                }
            });
        }
        /*
        *  handle event new/show and contact new/show
        */
        else if ( url.match(/new/) || /^\/event\/\d+$/.test(url) ||
            /^\/contact\/\d+$/.test(url) || /^\/task\/\d+$/.test(url) ) {

            $.ajax({
                type: "GET",
                url: url,
                cache: true,
                success: function(data) {

                    // setPageTitle(url);

                    $('body').append(data);


                    $("title").html('TimeTM - ' + $.ttm_ucFirst($("#ajaxFrame .listContainer h1").text()));


                    if (/^\/event\/new((\/\d+)+)?$/.test(window.location.pathname)) {

                        $.ttm_initEventDatetimepicker();
                    }
                    else if (window.location.pathname == '/task/new') {
                        $.ttm_initTaskDatetimepicker();
                    }
                    console.log(window.location.pathname);
                    // console.log("DEBUG");
                }
            });
        }
        /*
        *  handle pagination
        */
        else if ( url.match(/\?page/) || url.match(/\?sort/) ) {

            $.ajax({
                type: "GET",
                    url: url,
                })
                .done(function( msg ) {
                    $('#ttm_contentWithPanel').html(msg);
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                    $.ttm_sizePaginatedTable();
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                });
        }

        // Log the history object to your browser's console
        console.log("History url : " + url);
        console.log("History State.data.urlPath: " + State.data.urlPath);
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
            $.ttm_showMobileMenu();
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

        $.ttm_showMobileMenu();
    });


    /*
    * -- clickable tr
    */
    $(document).on( 'click' , 'tr[data-href]:not(.no-ajax)', function(e) {
        var url = $(this).data('href');
        History.pushState({urlPath: url}, null, url);
        console.log('catched 2');
    });


    /*
    * -- clickable tr no ajax
    */
    $(document).on( 'click' , 'tr[data-href].no-ajax', function(e) {
        var url = $(this).data('href');
        window.location.href = url;
        console.log('catched 3');
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
    * -- click on event, contact, task edit in ajaxframe
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

                console.log('url 1 : ' + url);

                var urlParts = getUrlParts(url);

                // setPageTitle(url);
                $("title").html('TimeTM - ' + $.ttm_ucFirst($("#ajaxFrame .listContainer h1").text()));

                if (/^\/event\/\d+$/.test(window.location.pathname)) {

                    $.ttm_initEventDatetimepicker();
                }
                else if (/^\/task\/\d+$/.test(window.location.pathname)) {
                    $.ttm_initTaskDatetimepicker();
                }

                // console.log(window.location.pathname);
            }
        });

        console.log( 'clicked on button on ajaxframe : ' + url);
    });


    /*
    * -- click on new contact, new event
    *    .no-ajax (exlude clic on profile buttons)
    */
    $(document).on( 'click' , 'a.button:not(#ajaxFrame .button, .no-ajax, .polo)', function (e) {

        e.preventDefault();

        if (window.location.pathname == '/event/') {

            $.ttm_initEventDatetimepicker();
        }

        var url = $(this).attr('href');
        console.log(">>> DEBUG 1");
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
                $('#ajaxFrame').remove();
                $('body').append(data.responseText);
                $.ttm_highlightFormErrors();
            }
        });
    });


    /*
    * -- handle ajax create task - send create form
    */
    $(document).on( 'click' , '#ajaxFrame #task_save', function (e) {

        var form = $('#task_save_form');

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
                $('#ajaxFrame').remove();
                $('body').append(data.responseText);
                $.ttm_highlightFormErrors();
                $.ttm_initTaskDatetimepicker();
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
                $('body').append(data.responseText);
            }
        });

    });


    /*
    * -- handle pagination and sortable
    */
    $(document).on('click', ".pagination a, a.sortable" , function(e) {

        e.preventDefault();

        var url = $(this).attr('href');

        History.pushState({urlPath: url}, null, url);
    });


    /*
    *  on load
    */
    $.ttm_init();


    /*
    *  on resize
    */
    var timer;

    $(window).resize(function() {

        if(timer) {
            window.clearTimeout(timer);
        }

        timer = window.setTimeout(function() {

            $.ttm_init();
        }, 30);
    });

});

function getUrlParts(url) {

    var urlParts = url.split('/');

    //remove empty items
    urlParts = urlParts.filter(function(e){return e});

    return urlParts;
}

/**
 * create page title
 *
 */
function setPageTitle(url) {

    var pageTitlePrefix = 'TimeTM - ';
    var pageTitle = pageTitlePrefix;

    var urlParts = getUrlParts(url);


    /**
     *  Dashboard, Calendar, Conatcts, Tasks
     */
    if (urlParts.length == 1) {
        pageTitle += $.ttm_ucFirst(urlParts[0]);
    }
    /**
     *  New, Show
     */
    else if (urlParts.length == 2) {
        if (urlParts[1] == 'new') {
            // pageTitle += $.ttm_ucFirst(urlParts[1]) + " " + urlParts[0];
        }
        else {
            // pageTitle += $("#ajaxFrame .listContainer h1").text();
        }
    }
    /**
     *  Edit
     */
    else if (urlParts.length == 3) {

        if (urlParts[0] == 'week') {
            pageTitle += "Calendar " + urlParts[1] + " " + urlParts[0] + " " + urlParts[2];
            // console.log($("#dateDisplay").text

        }
        else {
            pageTitle += $.ttm_ucFirst(urlParts[2])         // Edit
                + " " + urlParts[0];                        // task
        }
    }
    /**
     *  Add event from month, week and day
     */
    else if (urlParts.length == 5 || urlParts.length == 7) {
        pageTitle += $.ttm_ucFirst(urlParts[1])   // New
            + " " + urlParts[0]                         // event
            + " for "                                   // for
            + urlParts[4]                               // day
            + "/" + urlParts[3]                         // month
            + "/" + urlParts[2];                        // year
    }

    /**
     *  Add event from week and day
     */
    if (urlParts.length == 7) {
        pageTitle += " " +  urlParts[5]                 // hour
            + ":" + urlParts[6]                         // event

    }


    $('title').html(pageTitle);

    console.log('url parts BUG ' + urlParts);
    console.log('url parts ' + urlParts.length);

}
