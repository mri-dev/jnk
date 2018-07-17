<script type="text/javascript">
  (function($){
    autoHeightCat();

    $(window).resize(function(){
      autoHeightCat();
    });

    function autoHeightCat() {
      var pw = $(document).width();
      var db = 4;
      if ( pw <= 1000) {
        db = 2;
      }
      var tkhw = $('.tematikus-kategoria-holder.style-boxed').width();
      $('.tematikus-kategoria-holder.style-boxed .programs .program').css({
        height: (tkhw / db)
      });
    }
  })(jQuery);
</script>
