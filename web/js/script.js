$(function() {
  
  /*
   * -- AJAX call for TODO
   */
  $(document).on( "click" , "#PanelMonthCal td", function () {
    var url = $(this).attr('id');
//    var url = 'month/' + id;
    $.ajax({
      type: "GET",
      url: url,
      cache: true,
      success: function(data){
         $("#panel").html(data);
      }
    });
    console.log( 'clicked 1 ' + url);
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
        $("#test").html(data);
      }
    });
    console.log( 'clicked 2 ' + url);
  });
  
  /*
   * -- AJAX call for calendar navigation
   */
  $(document).on( "click" , "#panelCalendarNav li", function () {
    var url = $(this).attr('data-ajax');
    $.ajax({
      type: "GET",
      url: url,
      cache: true,
      success: function(data){
        $("#container").html(data);
      }
    });
    console.log( 'clicked 3 ' + url);
  });
  

});