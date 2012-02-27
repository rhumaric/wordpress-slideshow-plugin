(function($, window, document, undefined){

  /**
   * Allow the admin to collapse the slides forms
   */
  function make_slides_collapsible(){
    console.log('Make slide forms collapsible');

    $('.slide .menu-item-handle').on('click',function(){

      $(this).siblings('.menu-item-settings').toggleClass('collapsed');
    });
  }

  /**
   * Allow the admin to change the order of the slides
   * using drag'n'drop
   */
  function make_slides_sortable(){
    $('.slides').sortable();
  }

  $(document).ready(function(){

    make_slides_collapsible();
    make_slides_sortable();
  });
})(jQuery,window,document)
