<?php
	if(isset($query)){
		$msql 	= mssql_connect("biak_agresso","agresso","sagresso");
		mssql_select_db('Agresso', $msql);
		$que0	= $query;
		$res0	= mssql_query($que0);
?>
<style>
	table {
		border-collapse: collapse;
	}

	table, th, td {
	   border: 1px solid black;
	}
</style>
<br/>
<table>
<?php
		echo "<tr>";
		for ($i = 0; $i < mssql_num_fields($res0); ++$i) {
			// Fetch the field information
			$row0 = mssql_fetch_field($res0, $i);

			// Print the row
			echo '<td>' . $row0->name . '</td>';
		}
		echo "</tr>";

		while($row0 = mssql_fetch_row($res0)){
			echo "<tr>";
			for($i = 0; $i < count($row0); $i++){
				echo '<td>' . $row0[$i] . '</td>';
			}
			echo "</tr>";
		}
?>	
</table>
<?php
	}
?>
