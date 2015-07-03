$(function() {

    // onload
    $.ttm_test();
    $.ttm_sizeCalendar();


    // on resize
    $(window).resize(function() {
        $.ttm_sizeCalendar();
    });


    /*
     * -- handle new event from calendar - show create form
     * 
     */
    $(document).on( "click" , "#calendar td:not(.outOfMonth), table.inner td", function (e) {

        $('#container').css('opacity' , 0.2);

        var url = $(this).attr('data-url');
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
     * -- prevent previous handler to executed on link click
     * 
     */
    $(document).on( "click" , "#calendar td a", function (e) {
        e.stopPropagation();
    });


    /*
     * -- handle create event from calendar - send create form
     * 
     */
    $(document).on( 'click' , '#timetm_eventbundle_event_save', function (e) {

        var form = $('#event_save');

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            success: function(data){
                $('#ajaxFrame').remove();
                $('#container').css('opacity' , 1);
                $.ajax({
                    type: "GET",
                    url: data.referer,
                    cache: true,
                    success: function(data){
                        $("#container").html(data);
                        $.ttm_sizeCalendar();
                    }
                });
            },
            error:function(data) {
                $('#ajaxFrame').remove();
                $('body').append(data.responseText);
            }
        }); 
    });

});
