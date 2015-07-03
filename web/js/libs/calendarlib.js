(function($) {

    // test function
    $.ttm_test = function() {
        console.log('calendarlib.js loaded');
    }


    /*
     * -- calculate calendar cell height
     * 
     */
    $.ttm_getCellHeight = function() {

    var cal = '#calendar',
    rows = cal + ' tr';

    // get container height
    var displayHeight = $('#content').height();

    // get number of rows
    var rowCount = $(rows).length;

        // for weeks divide by 7days
        if ( $(cal).attr('data-week') != undefined ) {
            rowCount = ($(rows).length / 7);
        }

        // calculate cell height ( we remove rowCount = 1px border )
        return (displayHeight - rowCount )  / rowCount;
    }


    /*
     * -- set calendar cell height
     * 
     */
    $.ttm_setCellHeight = function(cellHeight) {

        // get cell height
        if (!cellHeight) {
            var cellHeight = $.ttm_getCellHeight();            
        }

        // set cell heigth
        $('#calendar td').css( 'height' , cellHeight );
    }


    /*
     * -- set event cell height
     * 
     */
    $.ttm_setEventHeight = function(cellHeight) {

        // get cell height
        if (!cellHeight) {
            var cellHeight = $.ttm_getCellHeight();            
        }

        var cellList = $('#calendar .event');

        $(cellList).each(function() {

            var scale = $(this).attr('data-duration');

            var startmins = $(this).attr('data-startmins') / 0.6;

            var top = startmins * cellHeight / 100;

            var evenHeight = ( cellHeight ) * scale;

            $(this).css( 'height' , evenHeight );
            $(this).css( 'top' , top );
        });
    }

    /*
     * -- set event cell height
     * 
     */
    $.ttm_sizeCalendar = function() {

        // get cell height
        var cellHeight = $.ttm_getCellHeight();

        $.ttm_setCellHeight(cellHeight);
        $.ttm_setEventHeight(cellHeight);
    }

}(jQuery));
