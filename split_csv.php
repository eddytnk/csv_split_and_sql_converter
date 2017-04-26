<?php
	$csv_data = file_get_contents($_FILES['fromfile']['tmp_name']);
	$perpage = $_POST['perpage'];
	$csv_array    = explode("\n", $csv_data);
	// $column_names = $csv_array[0];
    mkdir($_FILES['fromfile']['name']);
	$file = "./".$_FILES['fromfile']['name']."/".$_FILES['fromfile']['name']."- part-";
	
	$cnt = 0;
	$part = 1;
	$tofile = "";

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
	
?>
<h2>CSV File Spliter</h2>
<fieldset>
<div>
	File Size : <?php echo round(($_FILES['fromfile']['size']/1024)/1024,2)?>MB<br/>
	Number of Split : <?php echo $part?> parts<br/>
</div>
<a href='index.html'>< <<< BACK</a>
</fieldset>
