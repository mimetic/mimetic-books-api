<?php

/*
	Convert a chapter array to a Mimetic Books chapter array
	that can be easily exported as XML.
	
	Array2XML from:	http://www.lalit.org/lab/convert-php-array-to-xml-with-attributes/
	
*/

class MB_API_Funx
{
	public $themes, $themes_list;
	
	
	
	/* 
	Constructor 
	*/
 	function MB_API_Funx()
	{
	}
	
	
	// Field value popup menu:
	// Build a <select> pop-up selector in HTML from an array
	// $values is the array of ($value, $name) used in <OPTION VALUE=$value>$NAME</OPTION>
	// $listname is the name for the selection in HTML
	// $checked is array of checked values (value matches value in $values)
	// $sort = true, sort $values by value
	// $extraline = array ('value'=>$value, 'checked'=>$checked, 'label'=>$label) where $checked should be text "CHECKED" or ""
	// Two fields are retrieved: $set and $fieldlabel
	// example: $ArtistIDList = OptionListFromArray ($values, "ID", array("1"), true, true, "", array("0"=>"empty"));
	
	public function OptionListFromArray ($values, $listname, $checked = array(), $sort = TRUE, $size = true, $extrahtml="", $extraline = array()) {

		// internal use only for ease of reading
		$OPTION_LIST_IS_POPUP = true;
		$OPTION_LIST_IS_MULTI = true;

		is_array($values) || $values = array();
	
		if ($sort)
			asort ($values);
	
		if (!is_array($checked))
			$checked = array ($checked);
	
		$optionlist = "";
	
		$extraline && $optionlist .= "<OPTION VALUE=\"" . $extraline['value'] . "\" " . $extraline['checked'] . ">" . $extraline['label'] ."</OPTION>\n";
	
		reset($values);
		$k = 1;
		while (list($ID, $name) = each ($values)) {
			$ID = trim($ID);
			$name = trim($name);
			if ($name[0] != "/") {
				in_array($ID, $checked) ? $check = " selected" : $check = "";
				$optionlist .= "<OPTION VALUE=\"$ID\" $check>$name</OPTION>\n";
				$k++;
			}
		}
		if ($size === $OPTION_LIST_IS_POPUP) {
			$size = "";
		} elseif (!$size) {
			$k > 10 ? $size = $OPTION_LIST_MAXSIZE : $size = $k;
			$size = 'SIZE="' . $size . '" MULTIPLE';
		} else {
			$size = 'SIZE="' . $size . '" MULTIPLE';
		}
		$block = "\n<SELECT NAME=\"$listname\" $size $extrahtml>\n$optionlist</SELECT>\n";
	
		return $block;
	
	}
	
	
	
		// removes files and non-empty directories
	public function rrmdir($dir) {
		if (is_dir($dir)) {
			$files = scandir($dir);
			foreach ($files as $file)
			if ($file != "." && $file != "..") 
				$this->rrmdir("$dir/$file");
			rmdir($dir);
		}
		else if (file_exists($dir)) unlink($dir);
	} 
	
	
	// copies contents of a directory into another directory
	public function dircopy($src, $dst) {
		if ( !(file_exists($src) && file_exists($dst)) ) {
			return false;
		}
		
		(substr($src, -1) == "/") || $src = $src."/";
		(substr($dst, -1) == "/") || $dst = $dst."/";
		$files = scandir($src);
		foreach ($files as $file) {
			if ($file != "." && $file != "..") 
				$this->rcopy($src.$file, $dst.$file);
		}
	}
	
	// copies files and non-empty directories
	public function rcopy($src, $dst) {
		if (file_exists($dst))
			$this->rrmdir($dst);
		if (is_dir($src)) {
			mkdir($dst);
			$files = scandir($src);
			foreach ($files as $file)
			if ($file != "." && $file != "..") 
				$this->rcopy("$src/$file", "$dst/$file"); 
		}
		else if (file_exists($src)) copy($src, $dst);
	}

	
	public function tar_dir($src, $dst) {
		$src = str_replace(" ", "\ ", $src);
		$script = "tar -cf $dst $src";
		$results = exec($script, $res);
		return $res;
	}
	
}

?>