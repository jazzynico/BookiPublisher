<?php

function comments_initialize() {
    return;
}

function comments_render($book, $chapter) {
    $comments_file = "data/comments/$book/$chapter.json";
    $html =  <<<EOF
<script language="javascript">
<!--
function new_freecap() {
	if(document.getElementById) {
		// extract image name from image source (i.e. cut off ?randomness)
		thesrc = document.getElementById("freecap").src;
		thesrc = thesrc.substring(0,thesrc.lastIndexOf(".")+4);
		// add ?(random) to prevent browser/isp caching
		document.getElementById("freecap").src = thesrc+"?"+Math.round(Math.random()*100000);
	} else {
		alert("Sorry, cannot autoreload freeCap image\\nSubmit the form and a new freeCap will be loaded");
	}
}
//-->
</script>
EOF;
    $html .= '<a name="comments"></a><form action="/widgets/comments/add.php" method="POST">'
           .'<input type="hidden" name="book" value="'.$book.'">'
           .'<input type="hidden" name="chapter" value="'.$chapter.'">'
           .'<table><tr><td>name : </td><td><input type="text" name="name"></td><tr>'
           .'<td style="vertical-align:text-top";>comment : </td><td><textarea name="comment" cols="40" rows="10"></textarea></td><td></table>'
           .'<img src="/widgets/comments/freecap/freecap.php" id="freecap"><br>'
           .'<div class="captcha_help">If you can\'t read the word, <a href="" onClick="this.blur();new_freecap();return false;">click here</a></div>'
           .'word : <input type="text" name="word"><br>'

           .'<input type="submit" value="Send">'
           .'</form>' . "\n";

    if (file_exists($comments_file)) {
        $comments = file_get_contents($comments_file);
        $comments = json_decode($comments);
        foreach($comments as $comment) {
            $comment_html = str_replace("\n", "<br>\n", $comment->comment);
            $html .= '<div class="comment">On ' . $comment->date
                  . ' <span class="name">' . $comment->name 
                  . '</span> wrote:<br>' 
                  . $comment_html . "</div>\n";
        }
    }
    return $html;
}

function comments_widget() {
  return Array(
    "info" => Array(
        "author" => "j",
		"version" => "1.0"
    )
   );
}

?>
