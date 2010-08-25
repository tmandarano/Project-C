$(function () {
  LG.G.setupExpandable(true);
  $('.basic .birthday').datepicker({changeMonth: true, changeYear: true,
                                    maxDate: "-13y"});
});
