<?php

function array_value($array, $value)
{
    if (is_array($value)) {
        $subArray = $array;
        foreach ($value as $i => $v) {
            if (is_array($subArray) && isset($subArray[$v])) {
                $subArray = $subArray[$v];
            } else {
                return null;
            }
        }
        return $subArray;
    } else {
        return (is_array($array) && isset($array[$value]) ? $array[$value] : null);
    }
}

function myscandir($dir, $exp, $how='name', $desc=0)
{
   $r = array();
   $dh = @opendir($dir);
   if ($dh) {
       while (($fname = readdir($dh)) !== false) {
           if (preg_match($exp, $fname)) {
               $stat = stat("$dir/$fname");
               $r[$fname] = ($how == 'name')? $fname: $stat[$how];
           }
       }
       closedir($dh);
       if ($desc) {
           arsort($r);
       }
       else {
           asort($r);
       }
   }
   return(array_keys($r));
}

// Replaces only the first occurance...
function str_replace_once($needle, $replace, $haystack) {
   $pos = strpos($haystack, $needle);
   if ($pos === false) {
       return $haystack;
   }
   return substr_replace($haystack, $replace, $pos, strlen($needle));
}

function DateCompare($a, $b)
{
  return ($a[2] < $b[2]) ? 1 : -1;
}

function delete_directory($dirname)  {
    if (is_dir($dirname))
       $dir_handle = opendir($dirname);
    if (!$dir_handle)
       return false;
    while($file = readdir($dir_handle)) {
       if ($file != "." && $file != "..") {
          if (!is_dir($dirname."/".$file))
             unlink($dirname."/".$file);
          else
             delete_directory($dirname.'/'.$file);
       }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

function objectToArray( $object )
    {
        if( !is_object( $object ) && !is_array( $object ) )
        {
            return $object;
        }
        if( is_object( $object ) )
        {
            $object = get_object_vars( $object );
        }
        return array_map( 'objectToArray', $object );
}

function replaceArrayValue($array,$identifyingKey,$IdentifyingValue,$keyToReplace,$replacementValue){
	foreach ((array)$array as $key=>$value){
		if ($array[$key][$identifyingKey]==$IdentifyingValue) $array[$key][$keyToReplace]=$replacementValue;
	}
	return $array;
}

function searchArray($array, $key, $value)
{
    $results = array();

    if (is_array($array))
    {
        if (array_value($array, $key) == $value)
            $results[] = $array;

        foreach ((array)$array as $subarray)
            $results = array_merge($results, searchArray($subarray, $key, $value));
    }

    return $results;
}
