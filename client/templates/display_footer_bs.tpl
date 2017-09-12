    </div>
    <!--
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    -->
    <script>
      $('input').on('focusin', function(){
        lastFocusedInput = $(this);
      });
      
      $('input').blur(function(e) {
        console.log(e.relatedTarget);
        console.log(e.currentTarget);
        if(clicky != null && clicky.attr("type") == "button") {
        e.currentTarget.willValidate = false
          console.log("prevent validation");
          e.stopImmediatePropagation();
          e.preventDefault();
        }
      });
    </script>
  </body>
</html>