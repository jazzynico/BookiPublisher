<?
$here="tmp/test.pdf";

$thisBookHTML="tmp/makebook_DcJeeq.html";
$thisBookPDF=$here;

$yip=$thisBookHTML." ".$thisBookPDF;
 exec("lib/wkhtmltopdf-i386 tmp/makebook_DcJeeq.html tmp/boo.pdf");
    //shell_exec("wkhtmltopdf $yip");

?>
