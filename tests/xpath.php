<?php


function getImages($page,$bookdir){
  $doc = new DOMDocument();

  $doc->loadHTMLFile($page);
  $xml=simplexml_import_dom($doc); // just to make xpath more simple
  $images=$xml->xpath('//img');
  foreach ($images as $img) {
     echo $img['src']."<br>";
  }
}

$bookfile="booki/Audacity/index.txt";
$bookname="Audacity";

getImages($bookfile,$bookname);

?>
