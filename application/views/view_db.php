<?php 

		$first=0;
		if($view_db!=null)
		{
			echo "<table class='table'>";
			foreach ($view_db as $key => $value) {
				echo "<tr>";
				if($first==0){
					foreach ($value as $heads => $vals) {
						echo "<th>".$heads."</th>" ;
					}$first=1;}
				echo "</tr>";

				echo "<tr>";
					foreach ($value as $key_in => $value_in) {
						echo "<td>".$value_in."</td>" ;
					}
				echo "</tr>";	
			}
			echo "</table>";
		}
		else{
			echo "<h1>no data to show</h1>";
		}
?>					