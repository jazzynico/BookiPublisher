<?php
  $doc = new DOMDocument();
  $doc->loadHTMLFile( 'booki/Audacity/index.txt' );
  
  $books = $doc->getElementsByTagName( "ul" );
  foreach( $books as $book )
  {
  $chapters = $book->getElementsByTagName( "li" );
  $chapter = $chapters->item(0)->nodeValue;
  
  $sections = $book->getAttribute( "booki-section" );
  $section = $sections->item(0)->nodeValue;
  
  echo "chapter : $chapter - section : $section\n";
  }
?>

