<br><br>

     <!-- Footer
      ================================================== -->
      <hr>

      <footer id="footer">
        <p class="pull-right">
		&copy; ebildude123 on Github
		</p>
		
		Time updated: <?php echo date($timeFormat); ?> <br>
		<?php
			$endTime = microtime(true);
			$execTime = $endTime - $startTime;
			echo "Page loaded in " . $execTime . " seconds";
		?>
        
      </footer>

    </div><!-- /container -->



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="js/jquery.smooth-scroll.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootswatch.js"></script>
	<script src="js/prettify.js"></script>
	<script>
	  !function ($) {
		$(function(){
		  window.prettyPrint && prettyPrint()   
		})
	  }(window.jQuery)
	</script>

  </body>
</html>