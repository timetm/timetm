(function($) {

    // test function
    $.ttm_test = function() {
        console.log('calendarlib.js loaded');
    };


    /*
     *
     * -- DISPLAY -------------------------------------------------------------
     *
     */

    /*
     * -- calculate calendar cell width
     *
     */
    $.ttm_setCellWidth = function() {

         var cellWidth =
            Math.floor(
                parseInt(
                    $("#ttm_calendar").width()) / parseInt($("#ttm_calendar").find("tr:first td").length
                )
            ) - 1; // border

         $(".monthEvent").css('width', cellWidth);
         $(".singleEvent").css('width', cellWidth);
     };

    /*
     * -- calculate calendar cell height
     *
     */
    $.ttm_getCellHeight = function() {

        var cal = '#ttm_calendar',
        rows = cal + ' tr';

        // get container height
        var displayHeight = $('#ttm_contentWithPanel').height();

        // get number of rows
        var rowCount = $(rows).length;


        if ( $(cal).attr('data-month') !== undefined ) {
            rowCount = Math.ceil(rowCount / 16);
        }


        // for weeks divide by 7days
        if ( $(cal).attr('data-week') !== undefined ) {
            rowCount = ($(rows).length / 7);
        }

        if ( $(cal).attr('data-month') !== undefined ) {
            return ((displayHeight -  rowCount)   / rowCount -1 ) ;
        }

        // calculate cell height ( we remove rowCount = 1px border )
        return (displayHeight - rowCount)  / rowCount;
    };

    /*
     * -- set calendar cell height
     *
     */
    $.ttm_setCellHeight = function(cellHeight) {

        // get cell height
        if (!cellHeight) {
            cellHeight = $.ttm_getCellHeight();
        }

        // set cell heigth
        $('#ttm_calendar td').css( 'height' , cellHeight );

        if (document.querySelector('.singleEvent') !== null) {
            $('#ttm_calendar .singleEvent a.event').css( 'line-height' , cellHeight + 'px' );
        }
    };

    /*
     * -- set event cell height
     *
     */
    $.ttm_setEventHeight = function(cellHeight) {

        // get cell height
        if (!cellHeight) {
            cellHeight = $.ttm_getCellHeight();
        }

        var cellList = $('#ttm_calendar .singleEvent');

        $(cellList).each(function() {

            var scale = $(this).attr('data-duration');

            var startmins = $(this).attr('data-startmins') / 0.6;

            var top = startmins * cellHeight / 100;

            var evenHeight = ( cellHeight ) * scale;

            $(this).css( 'height' , evenHeight );
            $(this).css( 'top' , top );
        });
    };

    /*
     * -- handle number of events to display based on screen size
     *
     */
    $.ttm_handleMonthEvents = function(cellHeight) {

        // undo previous cell hiding
        $('#ttm_calendar .monthEventWrapper div').css('display' , 'block');

        // remove more link
        $('.moreLink').remove();

        // get cell height
        if (!cellHeight) {
            cellHeight = $.ttm_getCellHeight();
        }

        // 20 = height of meeting in px
        var maxEvents = parseInt((cellHeight / 20) - 1);

        var cellList = $('#ttm_calendar .monthEventWrapper');

        $(cellList).each(function() {

            $.ttm_hideMonthEvents($(this), maxEvents);
        });
    };

    /*
     * -- hide events from one cell
     *
     */
    $.ttm_hideMonthEvents = function(cell, maxEvents) {

        var numItems = cell.find('div').length;

        // if we have only one item do nothing
        if (numItems === 1) {
            return;
        }

        // get number of event
        var eventCount = cell.children('div').length;

        var numItemsToRemove = eventCount - maxEvents + 1;

        if ( numItemsToRemove > 1 ) {

            // hide event which have no place
            cell.find('div:nth-last-child(-n + ' + parseInt(numItemsToRemove - 1) + ')').css('display' , 'none');

            var moreLink = $('<a></a>')
                .addClass('moreLink')
                .addClass('align-center')
                .text(parseInt(numItemsToRemove - 1) +  ' more')
            ;

            cell.append(moreLink);
        }
    };

    /*
     * -- set event cell height
     *
     */
    $.ttm_sizeCalendar = function() {

        // console.log('run');

        // get cell height
        var cellHeight = $.ttm_getCellHeight();

        // remove "view all events" close links
        $('.closeLink').remove();

        // reset event width
        $('.monthEventWrapper').css('position' ,  'relative');
        $('.monthEventWrapper').css('width' ,  'auto');

        $.ttm_setCellHeight(cellHeight);
            $.ttm_setEventHeight(cellHeight);
            $.ttm_handleMonthEvents(cellHeight);

        $.ttm_setCellWidth();


    };

    /*
     * -- size dashboard table
     *
     */
    $.ttm_sizeDashboardTable = function() {

        var panelWidth = 0;
        if ($("#ttm_panel").is(':visible')) {
            panelWidth = $("#ttm_panel").width();
        }

        var windowsWidth = $(window).width();
        var tableWidth = windowsWidth - panelWidth;

        var widthToSplit = tableWidth - 160;

        var cellWidth = (widthToSplit / 5) - (5 * 10);

        $(".titleCell").css('width', cellWidth);
        $(".titleCell").css('max-width', cellWidth);
    }

    /*
     * -- size contact table
     *
     */
     $.ttm_sizeContactTable = function() {

         if ($( window ).height() > 700 ) {

             var rowCount = $('#contactList tr[data-href]').length;

             console.log(rowCount);

             if (rowCount > 9) {

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
     }

    /*
     * -- show mobile menu
     *
     */
    $.ttm_showMobileMenu = function() {

        $(".showForMediumInlineBlock").toggleClass('showMenu');
        $(".showForMediumInlineBlock li").toggleClass('showMenuItem');
        $("#logo").toggleClass('hide');
        $("#closeMenu").toggleClass('show');
    }

    /*
     * -- highlight form errors
     *
     */
    $.ttm_highlightFormErrors = function() {

        if ($(".formError").length > 0) {
            $(".formError").each(function() {
                $(this).parent().css('padding-bottom', '10px').css('background-color', '#900');
            });
        }
    }


    /*
     *
     * -- FORMS ---------------------------------------------------------------
     *
     */

    /*
     * -- init event startDate and endDate datetimepicker
     *
     */
    $.ttm_initEventDatetimepicker = function() {

        $("#timetm_eventbundle_event_startdate").datetimepicker({
            format:'d/m/Y H:i',
            step: 15,
            onChangeDateTime:function(dp,$input){
                $.ttm_updateEndDateField(dp,$input);
            }
        });


        $("#timetm_eventbundle_event_enddate").datetimepicker({
            format:'d/m/Y H:i',
            step: 15
        });
    }

    /*
     * -- init task duedate datetimepicker
     *
     */
    $.ttm_initTaskDatetimepicker = function() {

        $("#task_duedate").datetimepicker({
            format:'d/m/Y',
            timepicker:false,
        });
    }

    /*
     * -- set leading 0 to numbers smaller than 10
     *
     */
    $.ttm_intToString = function(param) {

        return param < 10 ? param = '0' + param: param;
    }

    /*
     * -- update event end date field on change in event start date
     *
     */
    $.ttm_updateEndDateField = function(dp,$input) {

        // get field value
        var dateInput = $input.val();

        // reverse date ( d/m/y -> y/m/d )
        var buffer = dateInput.split(' ');

        var date = buffer[0];
        var time = buffer[1];

        buffer = date.split('/');
        buffer.reverse();
        date = buffer.join('/');

        dateInput = date + ' ' + time;

        // create date object from date input
        var startDate = new Date(dateInput);

        // create new date object
        var dateOutput = new Date();

        // set new date at now +1h
        dateOutput.setTime(startDate.getTime() + (60*60*1000));

        // formatting
        var day = $.ttm_intToString(dateOutput.getDate());
        var hours = $.ttm_intToString(dateOutput.getHours());
        var mins = $.ttm_intToString(dateOutput.getMinutes());

        var month = dateOutput.getMonth();

        month += 1;

        if ( month == 12 ) {
            month = 0;
        }

        month = $.ttm_intToString(month);

        dateOutputString = day + '/' + month + '/' + dateOutput.getFullYear() + ' ' + hours + ':' + mins;

        $("#timetm_eventbundle_event_enddate").val(dateOutputString);
    }


    /*
     * -- set leading 0 to numbers smaller than 10
     *
     */
    $.ttm_updateParticipantsField = function(param) {

        var selected = $( "#timetm_eventbundle_event_contacts option:selected" );

        if ( selected.val() === '') {
            return;
        }
        console.log(selected.val());

        // get the selected Contact
        var newContact = selected.text();

        // get the content of participants field
        var contacts = $( "#timetm_eventbundle_event_participants").val();

        // if name is already in field do nothing
        var regexp = new RegExp(newContact);
        if ( contacts.match(regexp) ) {
            return;
        }

        if (contacts) {
            contacts += ', ';
        }
        contacts += newContact;
        $( "#timetm_eventbundle_event_participants").val(contacts);
    }



    /*
     * -- init on load, on resize
     *
     */
    $.ttm_init = function() {

        var referer = document.referrer.replace(/^[^:]+:\/\/[^/]+/, '').replace(/#.*/, '').replace(/\?.*/, '');

        /**
         * path   : /
         * action : size dashboard table
         */
        if (window.location.pathname == '/') {
            $.ttm_sizeDashboardTable();
        }
        /**
         * path    : /contact/
         * action  :
         *     get  : size dashboard table
         *     post : highlight errors fields
         */
        else if (window.location.pathname == '/contact/') {
            if (referer == '/contact/new') {
                $.ttm_highlightFormErrors();
            }
            else {
                $.ttm_sizeContactTable();
            }
        }
        /**
         * path    : /contact/id
         * referer : /contact/id, /contact/id/edit
         * action  : highlight errors fields
         */
        else if (/^\/contact\/\d+$/.test(window.location.pathname)) {
            if (/^\/contact\/\d+\/edit$/.test(referer) || /^\/contact\/\d+$/.test(referer) ) {
                $.ttm_highlightFormErrors();
            }
        }
        /**
         * path    : /event/
         * action  : highlight errors fields
         */
        else if (window.location.pathname == '/event/') {
            if (referer == '/event/new') {
                $.ttm_highlightFormErrors();
            }
        }
        /**
         * path    : /event/id
         * referer : /event/id, /event/id/edit
         * action  : highlight errors fields
         */
        else if (/^\/event\/\d+$/.test(window.location.pathname)) {
            if (/^\/event\/\d+\/edit$/.test(referer) || /^\/event\/\d+$/.test(referer) ) {
                $.ttm_highlightFormErrors();
            }
        }
        /**
         * path    : /event/new , /event/id/edit
         * action  : init date time pickers
         */
        else if (window.location.pathname == '/event/new' || /^\/event\/\d+\/edit$/.test(window.location.pathname)) {
            $.ttm_initEventDatetimepicker();

            $(document).on( 'change focusout' , '#timetm_eventbundle_event_contacts', function (e) {
                $.ttm_updateParticipantsField();
            });
        }
        /**
         * path    : /task/new , /task/id/edit
         * action  : init date time picker
         */
        else if (window.location.pathname == '/task/new' || /^\/task\/\d+\/edit$/.test(window.location.pathname)) {
            $.ttm_initTaskDatetimepicker();
        }
    }

}(jQuery));
