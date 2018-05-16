<?php //echo json_encode($primary_key);
foreach ($primary_key as $key1 => $value1) {
 if ($value1->primary_key==1)
 	$p_key = $value1->name;	
}
?>

<script type="text/javascript">
	function preview(){ 
	
				var excel=[] ;
				var database=[];
				var action=false;
				var num_rows=$("#num_rows").val();
				    $(".excel :selected").each(function(){
				       	 excel.push($(this).val());
				    });
				    $(".database :selected").each(function(){
				       	 database.push($(this).val());
				    });
				    $.ajax({
					  type: "POST",
					  url: "<?php echo base_url(); ?>"+"Indexcomp/preview",
					  data: {excel,database,action,num_rows},
					  cache: false,
					  success: function(data){
					   $( "#preview" ).append( data );
					   //$( "#preview" ).replaceWith( data );	
					  		  
						}
					});

			}

	function Upload(){ 
				
				var excel=[] ;
				var database=[];
				var action=true;
				var p_key="<?php echo $p_key;?>";
				var delete_data=$( '#delete_data' ).prop( "checked" );
				var update=$( '#update' ).prop( "checked" );
				    $(".excel :selected").each(function(){
				       	 excel.push($(this).val());
				    });
				    $(".database :selected").each(function(){
				       	 database.push($(this).val());
				    });

				    $.ajax({
					  type: "POST",
					  url: "<?php echo base_url(); ?>"+"Indexcomp/preview",
					  data: {excel,database,action,p_key,delete_data,update},
					  cache: false,
					  success: function(data){
					   alert(data);	
					   console.log(data);				  
						}
					});
		
		}		


</script>

<?php $formating="\n\t\t\t\t";?>
<div class="container-fluid header">
	<h3>Resulting fields to pair for table </h3><h2 class="name-stl"><b><?php echo $_SESSION['table_name'];?></b></h2>
	<div class="container row div-align">
		<button onclick="Upload()" type="submit" class="btn btn-danger btn-align">Upload</button>
		<button onclick="preview()" class="btn btn-primary btn-align">Preview</button>
		<button class="btn btn-dark btn-align"><a href="<?php echo base_url(); ?>Indexcomp/database_view" 
			style="text-decoration: none;">view data</a></button>
	</div>
	<div class="container row div-align">
		<input type="number" name="num_rows" id="num_rows">
	  	<input type="checkbox" name="delete_data" id="delete_data" class="checkbox">Delete</input>
	  	<input type="checkbox" name="update" id="update" class="checkbox">Update</input>
		
	</div>
</div>
	<div class="container-fluid row">
		<div class="bg-style-l col-md-5"><h1>EXCEL</h1>
			<table align="center">
				<?php
					foreach($database_fields as $key_db => $value_db){
						echo "<tr><td><span class='custom-dropdown custom-dropdown--white'>
						<select class='custom-dropdown__select custom-dropdown__select--white small excel' name='excel'>";
						foreach($excel_fields[1] as $key_ex => $value_ex){

							if($value_ex==$value_db){$selected='selected';}
							else{$selected=null;} 

							if($value_ex!=null)
							{echo "<option value=\"".$value_ex."\" ".$selected.">".$value_ex."</option>";}

					} 
						echo "</select></td></tr>";
				}?>
			</table>
		</div>
		<div class="bg-style-r col-md-5"><h1>DATABASE</h1>
			<table align="center">
				<?php 
					foreach($database_fields as $key_db => $value_db){
							if($value_db==$p_key){$pk="<i class='fas fa-key'></i>";}
							else{$pk=null;}

						echo "<tr><td>".$pk."</td><td>
						<span class='custom-dropdown custom-dropdown--white'>
						<select class='custom-dropdown__select custom-dropdown__select--white small database' name='excel'>";
						foreach($database_fields as $key => $value){

							if($value_db==$value){$selected='selected';}
							else{$selected=null;}

							if($value_db!=null)
							{echo "<option value=".$value." ".$selected.">".$value."</option>";}

					} 
						echo "</span></select></td></tr>";
				}?>
			</table>
		</div>
	</div>
	
<div id="preview" ></div>
