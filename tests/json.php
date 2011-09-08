<?
include("lib/json.inc.php");
        $info_file = "data/bookInfo.json";
        if(file_exists($info_file)) {
            $info = file_get_contents($info_file);
            $info = json_decode($info);
        } 

$info2=ObjectToArray($info);


print_r($info2);

function doit($info2){	
	foreach ($info2 as $key=>$value){
		if ($info2[$key]["title"]=="Audacity") unset($info2[$key]);
	}

}

$info2=doit($info2);



#unset($info2[1]);
echo "<br><br";
echo "<br><br";
print_r($info2);


?>
