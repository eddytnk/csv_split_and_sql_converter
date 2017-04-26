<?php

	$csv_data = file_get_contents($_FILES['fromfile']['tmp_name']);
	$to_sql = (array_key_exists('tosql',$_POST)) ? $_POST['tosql'] : "";
	$table_name = $_POST['table_name'];
	$csv_array    = explode("\n", $csv_data);
	$cnt = 0;
	$part = 1;
	$tofile = "";
	
	 $column_names = explode(",", $csv_array[0]);
	 $num_columns = count($column_names);

		//SET DEfualt col size to 1;
	 $table_columns_sizes = [];
     for( $k =0; $k<$num_columns;$k++){
		$table_columns_sizes[$k] = 1;
	} 
	 $base_query = "INSERT INTO `".$table_name."` VALUES (";
		$i = 0;
		for($i = 1; $i < count($csv_array)-1;$i++){			
			$csv_row    = explode(",", $csv_array[$i]);
			$tofile .= $base_query;
			
			$counter = 0;
			foreach($csv_row as $val){
				//Dont add comma (,) at the last column value
					$cont = str_replace('"','',$val);
				if(($counter)+1 == count($csv_row)){
					$tofile .="\"".$cont."\"";
				}else{
					$tofile .="\"".$cont."\",";
				}
				//SET table col size
				if(strlen($cont)>$table_columns_sizes[$counter]){
					$table_columns_sizes[$counter] = strlen($cont);
				}
				$counter++;
			}
			$tofile .= ");\n";
			$cnt++;
		}

		//CREATE TABLE
		 $create_table = "CREATE TABLE `".$table_name."` (";
				$k = 0;
			foreach($column_names as $val){
				if(($k)+1 == count($column_names)){
					$create_table .= "`".$val."` varchar(".$table_columns_sizes[$k].")\n";
				}else{
					$create_table .= "`".$val."` varchar(".$table_columns_sizes[$k]."),\n";
				}
				$k++;
			}
			$create_table .=");\n\n\n"; 

    mkdir($_FILES['fromfile']['name']);
	$file_dir = "./".$_FILES['fromfile']['name']."/".$table_name;

		$fh = fopen($file_dir.".sql", 'w')
		or die("Unable to write to file " . $tofile . ".");
		fwrite($fh, $create_table.$tofile);
		fclose($fh);	
?>
<h2>CSV To SQL Converter</h2>
<fieldset>
<div>
	File Converted successfully
</div>
<a href='index.html'>< <<< BACK</a>
</fieldset>
