$(function () {
  LG.G.setupExpandable();
  $('.basic .birthday').datepicker({changeMonth: true, changeYear: true,
                                    maxDate: "-13y"});
});
