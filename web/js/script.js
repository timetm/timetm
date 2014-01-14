$(function() {
  
  /*
   * -- AJAX call for day navigation
   */
  $(document).on( "click" , "#PanelMonthCal td", function () {
    var url = $(this).attr('data-ajax');
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
    console.log( 'clicked ' + url);
  });
  
  /*
   * -- AJAX call for panel calendar navigation
   */
  $(document).on( "click" , "#panelCalendarQuickNav li", function () {
    var url = $(this).attr('data-ajax') + '/panel';
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
   * -- AJAX call for calendar navigation
   */
  $(document).on( "click" , "#panelCalendarNav td, #panelCalendarMode td", function () {
    var url = $(this).attr('data-ajax');
    if (url) {
      $.ajax({
        type: "GET",
        url: url,
        cache: true,
        success: function(data){
          $("#container").html(data);
        }
      });
    }
    console.log( 'clicked in navigation : ' + url);
  });
  
});