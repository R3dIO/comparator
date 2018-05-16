
    <h1 style="margin-top: 50px;">Comparator</h1>
<div class="container-fluid row" >
    <div class="col-md-5 bg-style-l" style="margin-top: 50px;">
    	<h1>EXCEL</h1>
			<?php echo form_open_multipart('Indexcomp/do_upload');?>
			<?php echo "<input type='file' name='userfile' size='20' required class='btn btn-success'/>"; ?>

    </div>
    <div class="col-md-5 bg-style-r" style="margin-top: 50px;">
    	<h1>DATABASE</h1>
	    	<?php
		    	echo "<select name='table' id='table'>";
		    		 foreach($tables as $key => $val)
		    		 {
		  				echo "<option value='".$val."'>".$val."</option>";
		  				//echo "<input type='radio' name='table' value='".$val."'>".$val."</input><br>";
		  				}
				echo "</select>";
			?>
			<br><br>
			<?php echo "<input type='submit' name='submit' value='upload' class='btn btn-dark' /> ";?>
			<?php echo "</form>";?>
    </div>
</div>

