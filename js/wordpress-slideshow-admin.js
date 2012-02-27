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
    $('.slides').sortable()
                .on('sortstop',function(event,ui){

                  var updated_order = [];
                  $('.slide').each(function(index,element){

                    $('.slide-no',$(element)).attr('value',index+1);
                    updated_order.push({
                      'slide_id': $('[name="slide"]',$(element)).attr('value'),
                      'slide_no': index+1
                    });
                  });

                  var post_data = {

                    'action': 'wordpress-slideshow-update-order',
                    'updated_order': updated_order
                  };

                  $.post(ajaxurl, post_data, function(response){

                    console.log('Update successful');
                  });
                });;

    $('label[for="custom-slide-no"]').hide();
  }

  $(document).ready(function(){

    make_slides_collapsible();
    make_slides_sortable();
  });
})(jQuery,window,document)
