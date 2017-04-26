<?php

	$csv_data = file_get_contents($_FILES['fromfile']['tmp_name']);
	$to_sql = (array_key_exists('tosql',$_POST)) ? $_POST['tosql'] : "";
	$perpage = $_POST['perpage'];
	$table_name = $_POST['table_name'];
	$csv_array    = explode("\n", $csv_data);
	// $column_names = $csv_array[0];
    mkdir($_FILES['fromfile']['name']);
	$file = "./".$_FILES['fromfile']['name']."/".$_FILES['fromfile']['name']."- part-";
	$cnt = 0;
	$part = 1;
	$tofile = "";

	if($to_sql=="checked"){

	 $base_query = "INSERT INTO `".$table_name."` VALUES (";
		$i = 0;
		for($i = 0; $i < count($csv_array)-1;$i++){
			if($cnt== $perpage){ 
				$fh = fopen($file."-".$part.".sql", 'w')
				or die("Unable to write to file " . $tofile . ".");
				fwrite($fh, $tofile);
				fclose($fh);
				$part++;
				$cnt = 0;
				$tofile = "";
			}
			$csv_row    = explode(",", $csv_array[$i]);
			$tofile .= $base_query;
			
			$counter = 0;
			foreach($csv_row as $val){
				//Dont add comma (,) at the last column value
				if(($counter+1) == count($csv_row)){
					$tofile .="'".$val."'";
				}else{
					$tofile .="'".$val."',";
				}
				$counter++;
			}
			$tofile .= ");\n";
			$cnt++;
		}
		$fh = fopen($file."-".$part.".sql", 'w')
				or die("Unable to write to file " . $tofile . ".");
				fwrite($fh, $tofile);
				fclose($fh);

	}else{
		foreach($csv_array as $row){

			if($cnt== $perpage){ 
				$fh = fopen($file."-".$part.".csv", 'w')
				or die("Unable to write to file " . $tofile . ".");
				fwrite($fh, $tofile);
				fclose($fh);
				$part++;
				$cnt = 0;
				$tofile = "";
			}
			$tofile .= $row."\n";
			$cnt++;
		}
	$fh = fopen($file."-".$part.".csv", 'w')
				or die("Unable to write to file " . $tofile . ".");
				fwrite($fh, $tofile);
				fclose($fh);
	}
?>
<h2>CSV File Spliter & SQL Converter</h2>
<fieldset>
<div>
	File Size : <?php echo round(($_FILES['fromfile']['size']/1024)/1024,2)?>MB<br/>
	Number of Split : <?php echo $part?> parts<br/>
</div>
<a href='index.html'>< <<< BACK</a>
</fieldset>
