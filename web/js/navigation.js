$(function() {

    setCellHeight();

    /*
     * handle new event from calendar - show create form
     */
    $(document).on( "click" , "#MonthCal td", function (e) {

        $('#container').css('opacity' , 0.2);

        var url = $(this).attr('data-url');
        url = url.replace(/-/g, '/');
        url = '/event/new/' + url;
      
        $.ajax({
            type: "GET",
            url: url,
            cache: true,
            success: function(data){
                $('body').append(data);
            }
        });
    });

    /*
     * prevent previous handler to executed on link click
     */
    $(document).on( "click" , "#MonthCal td a", function (e) {
        e.prevenPropagation();
    });


    /*
     * handle create event from calendar - send create form
     */
    $(document).on( 'click' , '#timetm_eventbundle_event_save', function (e) {

        var form = $('#event_save');
        /*
         * Throw the form values to the server!
         */
        console.log('sending ajax.');
      
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            success: function(data){
                console.log('in success');
                $('#ajaxFrame').remove();
                $('#container').css('opacity' , 1);
                $.ajax({
                    type: "GET",
                    url: '/month',
                    cache: true,
                    success: function(data){
                        $("#container").html(data);
                        setCellHeight();
                    }
                });
            },
            error:function(data) {
                console.log(data.responseText);
                $('#ajaxFrame').remove();
                $('body').append(data.responseText);
            }
        }); 
    });


    /*
     * close ajax frame 
     */
    $(document).on( 'click' , '#ajaxFrame', function (e) {
        $('#ajaxFrame').remove();
        $('#container').css('opacity' , 1);
    });

    /*
     * prevent closing ajax frame when clicking on ajaxContent
     */
    $(document).on( 'click' , '#ajaxContent', function (e) {
        return false;
    });


    /*
     * -- AJAX call for panel "quick navigation" day links
     */
    $(document).on( "click" , "#PanelMonthCal td a", function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        console.log( 'clicked 1 ' + url);
        if ( url.match(/month/) != null ) {
            var day = $(this).val();
            url += '/content';
            console.log( 'matched ' + url);
        }
        $.ajax({
            type: "GET",
            url: url,
            cache: true,
            success: function(data){
                $("#container").html(data);
            }
        });
        console.log( 'clicked 2 ' + url);
    });
  
    /*
     * -- AJAX call for panel "quick navigation" prev/next links
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
        console.log( 'clicked in quick nav : ' + url);
    });


    /*
     * -- AJAX call for calendar prev/next links
     */
    $(document).on( "click" , "#panelCalendarNav td a, #panelCalendarMode td a", function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        if (url) {
            $.ajax({
                type: "GET",
                url: url,
                cache: true,
                success: function(data){
                    $("#container").html(data);
                    setCellHeight();
                }
            });
        }
        console.log( 'clicked in navigation : ' + url);
    });
  
});


function setCellHeight() {

    var displayHeight = $('#MonthCal').height();
    var rowCount = $('#MonthCal tr').length;
    var cellHeight = (displayHeight - (rowCount * 10))  / rowCount;
    
    $('#MonthCal td').css( 'height' , cellHeight );
}