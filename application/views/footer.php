	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>js/bootstrap-select.js"></script>

    <script type="text/javascript">
	var colors = ['1abc9c', '2c3e50', '2980b9', '7f8c8d', 'f1c40f', 'd35400', '27ae60'];

	colors.each(function (color) {
	  $$('.color-picker')[0].insert(
	    '<div class="square" style="background: #' + color + '"></div>'
	  );
	});

	$$('.color-picker')[0].on('click', '.square', function(event, square) {
	  background = square.getStyle('background');
	  $$('.custom-dropdown select').each(function (dropdown) {
	    dropdown.setStyle({'background' : background});
	  });
	});
	</script>
</body>
</html>