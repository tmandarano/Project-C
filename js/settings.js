$(function () {
  $('.expandable').each(function () {
    var pane = this;
    var header = $('h1', pane);
    var content = $('.content', pane);
    function show() {
      header.animate({width: '100%'}, 'fast', function () {
        content.slideDown();
      });
      header.attr('expanded', 'true');
    }
    function hide() {
      content.slideUp(function () {
        header.animate({width: '30%'});
      });
      header.attr('expanded', 'false');
    }
    hide();
    header.click(function () {
      if (header.attr('expanded') == 'true') {
        hide();
      } else {
        show();
      }
    });
  });
  $('.basic .birthday').datepicker({changeMonth: true, changeYear: true,
                                    maxDate: "-13y"});
});
