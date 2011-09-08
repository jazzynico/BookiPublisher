<?
$randname="";
 for ($i=0; $i<6; $i++) {
                $d=rand(1,30)%2;
                $randname.= $d ? chr(rand(65,90)) : chr(rand(48,57));
        }   
echo $randname;
?>
