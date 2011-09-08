<?php

function admin_admin() {

    // get users books
    $tableUsers = new Axon(DB_TABLE_USERS);
    $books = array();
    if ($username = array_value($_SESSION, 'username')) {
        $tableUsers->load("username='$username'");
        if (!$tableUsers->dry()) {
            $books = unserialize($tableUsers->books);
        }
    }

    $tableBooks = new Axon(DB_TABLE_BOOKS);
    $html ='<h1>'._('bookInfo Plugin').'</h1>';
    if (array_value($_GET, 'action') =='save' ){
        foreach($_POST['description'] as $key=>$value)
        	$description[]=$value;
        foreach($_POST['title'] as $key=>$value)
        	$title[]=$value;
        foreach($_POST['dir'] as $key=>$value)
        	$dir[]=$value;
        foreach($_POST['date'] as $key=>$value)
        	$date[]=$value;
        foreach($_POST['category'] as $key=>$value)
        	$category[]=$value;
        foreach($_POST['status'] as $key=>$value)
        	$status[]=$value;
        foreach($_POST['modified'] as $key=>$value)
        	$modified[]=$value;
        foreach($_POST['epub'] as $key=>$value)
        	$epub[]=$value;
        foreach($_POST['pdf'] as $key=>$value)
        	$pdf[]=$value;
        foreach($_POST['analyzer'] as $key=>$value)
        	$analyzer[]=$value;
        foreach($_POST['translations'] as $key=>$value)
        	$translations[]=$value;
        foreach($_POST['textdirection'] as $key=>$value)
        	$textdirection[]=$value;

        $i=0;
        foreach ($_POST['dir'] as $value) {
            if (array_value($books, $value)) {
                $tableBooks->load("dir='$value'");
                $tableBooks->description=$description[$i];
            	$tableBooks->dir=$dir[$i];
            	$tableBooks->title=$title[$i];
            	$tableBooks->date=$date[$i];
            	$tableBooks->visible=array_value($_POST, 'visible_'.$i);
            	$tableBooks->category=$category[$i];
            	$tableBooks->status=$status[$i];
            	$tableBooks->modified=$modified[$i];
            	$tableBooks->epub=$epub[$i];
            	$tableBooks->pdf=$pdf[$i];
            	$tableBooks->analyzer=$analyzer[$i];
            	$tableBooks->translations=$translations[$i];
            	$tableBooks->textdirection=$textdirection[$i];
            	$tableBooks->save();
            	$tableBooks->reset();
            }
            $i++;
        }
    }
    $ii=0;
    $allBooks = $tableBooks->find();
    $allowedBooks = array();
    foreach ($allBooks as $book) {
        if (array_value($books, $book['dir'])) {
            $allowedBooks[] = $book;
        }
    }
    $html .= '<br><br><a name="info"></a><form action="admin.php?plugin=admin&action=save" method="POST">';
	$html .= '<table><tr><td width=350>';
	$counter=0;
    foreach((array)$allowedBooks as $info) {
        $info = (object) $info;
        $info_html = str_replace("\n", "<br>\n", (empty($info->info) ? null : $info->info));
        $html .= '<table><tr><td><b>&lt;'._('book-dir').'/&gt; :</b></td><td> '
       	.'<input type="text" name="dir[]" value="'.$info->dir.'" readonly/>';
	    if (!is_dir(BOOKI_DIR.'/'.$info->dir)) $html.='<font color=red> * </font>';
	    $html.= '</td></tr><tr><td>'
           	.'<b>'._('created').' : </b></td><td>'
           	.'<input type="text" name="date[]" value="'.$info->date.'"/></td></tr><tr><td>'
           	.'<b>&lt;'._('modified').'/&gt; : </b></td><td>'
           	.'<input type="text" name="modified[]" value="'.$info->modified.'"/></td></tr><tr><td>'
           	.'<b>&lt;'._('translation-links').'/&gt; : </b></td><td>'
           	.'<textarea name="translations[]"/>'.$info->translations.'</textarea></td></tr><tr><td>'
           	.'<b>&lt;'._('textdirection').'/&gt; : </b></td><td>'
           	.'<input type="text" name="textdirection[]" value="'.$info->textdirection.'"/></td></tr><tr><td>'
           	.'<b>&lt;'._('pdf-location').'/&gt; : </b></td><td>'
           	.'<input type="text" name="pdf[]" value="'.$info->pdf.'"/></td></tr><tr><td>'
           	.'<b>&lt;'._('epub-location').'/&gt; : </b></td><td>'
           	.'<input type="text" name="epub[]" value="'.$info->epub.'"/></td></tr><tr>'
           	.'</tr><td><b>&lt;'._('book-title').'/&gt : </b></td><td><input type="text" name="title[]" value="'.$info->title.'"></td></tr><tr><td>'
           	.'<b>'._('category').': </b></td><td><input type="text" name="category[]" value="'.$info->category.'"></td></tr><tr><td>'
           	.'<b>'._('status').' : </b></td><td><select name="status[]"><option value="'.$info->status.'">'.$info->status.'</option><option value="New">'._('New').'</option>'
		.'<option value="Major Update">'._('Major Update').'</option><option value="Updated">'._('Updated').'</option><option value="no status">'._('no status').'</option></select></td></tr><tr><td>'
           	.'<b>'._('Description').' : </b></td><td><textarea name="description[]">'.$info->description.'</textarea></td></tr><tr><td>'
           	.'<b>&lt;'._('analyzer').'/&gt; : </b></td><td><textarea name="analyzer[]">'.$info->analyzer.'</textarea></td></tr><tr><td>';
		if ($info->visible == 'on') {
           		$html .= '<b>'._('Visible').' : </b></td><td><input type="checkbox" name="visible_'.$ii.'" checked></td></tr><tr>';
		} else {
           		$html .= '<b>'._('Visible').' : </b></td><td><input type="checkbox" name="visible_'.$ii.'"></td></tr>';
		}
		$html.="<tr><td>";
		$html.='</table><br><br>';
		$html.='</td><td>';
		if ($counter==0) {
			$html.='</td></td><td>';
			$counter=1;
		} else {
			$html.='</td></tr><tr><td>';
			$counter=0;
		}
    	$ii++;
    }
    $html .= '</td></tr></table><input type="submit" value="'._('Save changes').'">'
           .'</form><br><font color=red>* = '._('dir does not exist').'</font>' . "\n";
    return $html;
}


function admin_dispatcher($name) {
  if($name == "admin") return admin_admin();
}


function admin_afterdisplay() {
}

function admin_adminnav() {
	return	createAdminNav("admin","bookInfo");
}

function admin_initialize() {
  add_hook("admin_nav", "admin_adminnav");
}

function admin_install() {

}

function admin_uninstall() {

}


function admin_plugin() {
  return Array("info" => Array("author" => "Adam Hyde",
			       "license" => "AGPL",
			       "description" => "Edit books table.",
			       "version" => "1.0")
	       );
}

?>
