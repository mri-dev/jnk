<script type="text/javascript">
  (function($){
    autoHeightCat();

    $(window).resize(function(){
      autoHeightCat();
    });

    function autoHeightCat() {
      var tkhw = $('.tematikus-kategoria-holder.style-boxed').width();
      $('.tematikus-kategoria-holder.style-boxed').css({
        height: (tkhw / 5)
      });
    }
  })(jQuery);
</script>
