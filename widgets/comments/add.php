<?php

$book = basename($_POST["book"]);
$chapter = basename($_POST["chapter"]);

$post = Array();
$post['name'] = $_POST["name"];
$post['comment'] = $_POST["comment"];
$post['date'] = strftime("%Y-%m-%d %H:%M");

session_start();

if(!empty($_SESSION['freecap_word_hash']) && !empty($_POST['word'])) {
    // all freeCap words are lowercase.
    // font #4 looks uppercase, but trust me, it's not...
    if($_SESSION['hash_func'](strtolower($_POST['word']))==$_SESSION['freecap_word_hash'])
    {
	    // reset freeCap session vars
	    // cannot stress enough how important it is to do this
	    // defeats re-use of known image with spoofed session id
	    $_SESSION['freecap_attempts'] = 0;
	    $_SESSION['freecap_word_hash'] = false;


	    // now process form


	    // now go somewhere else
	    // header("Location: somewhere.php");
	    $captcha_ok = "yes";
    } else {
	    $captcha_ok = "no";
    }
} else {
    $captcha_ok = "no";
}

if($captcha_ok!="yes") {
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
    $html .= '<a name="comments"></a><form action="" method="POST">'
           .'<input type="hidden" name="book" value="'.$book.'">'
           .'<input type="hidden" name="chapter" value="'.$chapter.'">'
           .'<input type="text" name="name" value="' .htmlspecialchars($post['name']). '"><br>'
           .'<textarea name="comment">' .htmlspecialchars($post['comment']) . '</textarea><br>'
           .'<img src="freecap/freecap.php" id="freecap"><br>'
           .'<div class="captcha_help">If you can\'t read the word, <a href="" onClick="this.blur();new_freecap();return false;">click here</a></div>'
           .'<input type="text" name="word"><br>'

           .'<input type="submit" value="Send">'
           .'</form>' . "\n";

	echo "sorry, that's not the right word, try again.<br />";
    echo $html;
    exit();
}

//ok, captcha fine, lets save comment
if(isset($book) && isset($chapter)) {
    $page_file = "../../booki/$book/$chapter.txt";
    if(file_exists($page_file)) {
        $comments_file = "../../data/comments/$book/$chapter.json";
        if(file_exists($comments_file)) {
            $comments = file_get_contents($comments_file);
            $comments = json_decode($comments);
        } else {
            $comments = Array();
            if(!file_exists(dirname($comments_file))) {
                if(!mkdir(dirname($comments_file))) {
                    die("failed to create folder: " . dirname($comments_file)); 
                }
            }
        }
        $comments[] = $post;
        $comments = json_encode($comments);
        if(!file_put_contents ($comments_file, $comments)) {
            print "failed to write " . $comments_file; 
        } else {
            header("Location: ../../index.php?book=$book&chapter=$chapter#comments");
        }
    }
}

?>
