<?php

function blog_admin () {
  	$html ='<h1>Blog Plugin</h1>';
  	$html.='Posts must have a h1 (and only one) at the top.';
	if (array_value($_POST, 'save') == "save") {
	    $tableBlogs = new Axon(DB_TABLE_BLOGS);
		$tableBlogs->date = date("D, d M Y H:i:s O");
		$tableBlogs->postedBy = $_POST['postedBy'];
    	$doc = new DOMDocument();
      	@$doc->loadHTML($_POST['myTextArea']);
      	$xml=simplexml_import_dom($doc); // just to make xpath more simple
      	$title=$xml->xpath('//h1');
    	//$title = preg_replace('/\s/', '', $title[0]);
    	$title=preg_replace('/[^a-z0-9]/', '', array_value($title, 0));
    	if ($title=="") $title == _("General News");
      	$tableBlogs->title = $title;

		if (USE_TIDY=="true"){
		  $cleanfile=tempnam("tmp/", "clean_");
		  file_put_contents($cleanfile,$_POST['myTextArea']);
		  shell_exec("tidy -m -config data/tidy.config $cleanfile");
		  $tableBlogs->post = file_get_contents($cleanfile);
	   	} else {
	   	  $tableBlogs->post = $_POST['myTextArea'];
	   	}
	   	$tableBlogs->save();

	}

        $html.='<script type="text/javascript" src="data/xinha/XinhaLoader.js"></script>';
		$html.='<script type="text/javascript" src="data/xinha/XinhaConfig.js"></script>';
		$html.='<br /><br />';
		$html.='<form action="admin.php?plugin=blog&action=edit" method="post">';
		$html.='<textarea id="myTextArea" name="myTextArea" rows="25" cols="20" style="width: 650px;"></textarea><br />';
		//$html.='Blog title : <input type="text" name="title"> <br />';
		$html.=_('Posted by').' : <input type="text" name="postedBy" value="'.$_SESSION["username"].'"> <br />';
		$html.='<br /><input type="submit" name="save" value="'._('save').'"><input type="submit" name="cancel" value="'._('cancel').'"></form>';

		return $html;
}

function blog_index() {
    $html = null;
    $rssitems = null;
    $rssseq = null;
	$maxPosts = defined(BLOG_MAX_POSTS) ? BLOG_MAX_POSTS : 5;
    $action = array_value($_GET, 'action');
    $tableBlogs = new Axon(DB_TABLE_BLOGS);

	if ($action == "rss"){
	    $tableRss = new Axon(DB_TABLE_RSS);
	    $tableRss->load();
	    $rss = "<?xml version=\"1.0\"?>
<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">
<channel>
<title>$tableRss->blogtitle</title>
<link>$tableRss->bloglink</link>
<description>$tableRss->blogdescription</description>
<language>$tableRss->language</language>
<webMaster>$tableRss->webmaster</webMaster>
<generator>$tableRss->generator</generator>
<atom:link href=\"$tableRss->rsslink\" rel=\"self\" type=\"application/rss+xml\" />
";
	}

  	$blogs = $tableBlogs->find();
  	$blogs = array_reverse((array)$blogs);
  	foreach ($blogs as $info) {
  	    $info = (object) $info;
		$post=preg_replace('[\\\"]','',$info->post);
        if ($action=="rss"){
        	$posting="";
        	//thse two need to be changed
        	//$bloglink="http://www.flossmanuals.net/".basename($fullfilename);
        	//$descriptionlink="http://www.flossmanuals.net";

        	$doc = new DOMDocument();
          	@$doc->loadHTML($info->post);
          	$xml=simplexml_import_dom($doc); // just to make xpath more simple
          	$title=$xml->xpath('//h1');
          	$content=$xml->xpath('//p');
        	foreach ($content as $description) {
        		$posting.=$description."  ";
        		//echo $description."<br /><br />";;
        	}
        	$content=substr($posting,0,200);
        	$guidtitle=preg_replace('/[^a-z0-9]/', '', $title[0]);
        	$guid=$tableRss->bloglink."#".$guidtitle;

        		$rssitems.="<item><title>$title[0]</title>
          <link>$guid</link>
          <description>$content (by $info->postedBy)</description>
          <pubDate>".$info->date."</pubDate>
              <guid>$guid</guid>
        </item>
        ";
        }
		$post=preg_replace("[\/\&quot\;]","",$post);
		//$post=preg_match("[\&quot;]","",$post);
        	$html .= '<br />'.stripslashes($post);
		$html.='<br />'.$info->date;
        	if (trim($info->postedBy) != "") $html .= ' by '.$info->postedBy;
		$html.='<br /><br /><br />';
        if ($maxPosts-- == 0) break;
    }


	if ($action == 'rss'){
		$rss .= $rssseq.$rssitems."</channel></rss>";
		return $rss;
	} else {
		$template='blog';
		$html = addTemplate($template,$html);
		return $html;
	}
}

function rss_admin() {
    $html ="<h1>"._("Blog Plugin - RSS")."</h1>";
    $tableRss = new Axon(DB_TABLE_RSS);
    $tableRss->load();

  if (array_value($_GET, "action2") == "save"){
  	$tableRss->webmaster = $_POST["webmaster"];
  	$tableRss->bloglink =$_POST["bloglink"];
  	$tableRss->rsslink =$_POST["rsslink"];
  	$tableRss->blogtitle =$_POST["blogtitle"];
  	$tableRss->blogdescription =$_POST["blogdescription"];
  	$tableRss->generator =$_POST["generator"];
	$tableRss->language =$_POST["language"];
	$tableRss->save();
  }

    $html .= '<br /><br /><form action="admin.php?plugin=blog&action=rss&action2=save" method="post">';
	$html .= '<table>';
    $html .=  '<tr><td>'._('webmaster').'</td><td><input type="text" name="webmaster" value="'.$tableRss->webmaster.'" size="35"/> '._('eg.  pete@email.com (Pete Mate)').'</td></tr>';
    $html .=  '<tr><td>'._('blog url').' </td><td><input type="text" name="bloglink" value="'.$tableRss->bloglink.'" size="35"/></td></tr>';
    $html .=  '<tr><td>'._('rss link').' </td><td><input type="text" name="rsslink" value="'.$tableRss->rsslink.'" size="35"/></td></tr>';
    $html .=  '<tr><td>'._('title of blog').'</td><td><input type="text" name="blogtitle" value="'.$tableRss->blogtitle.'" size="35"/></td></tr>';
    $html .=  '<tr><td>'._('blog description').' </td><td><textarea name="blogdescription" cols="29"/>'.$tableRss->blogdescription.'</textarea></td></tr>';
    $html .=  '<tr><td>'._('generator').' </td><td><input type="text" name="generator" value="'.$tableRss->generator.'" size="35"/></td></tr>';
    $html .=  '<tr><td>'._('language').' </td><td><input type="text" name="language" value="'.$tableRss->language.'" size="35"/></td></tr>';
	$html .= '</table>';
	$html .= '<br /><input type="submit" value="'._('save').'"></form>';
	$html .= _("See")." <a href=\"http://cyber.law.harvard.edu/rss/rss\" target=\"new\">http://cyber.law.harvard.edu/rss/rss</a> "._("for a good explanation of rss fields.");
	return $html;
}

function blog_dispatcher($name) {
  if($name == "index") return blog_index();
  if(($name == "admin") && (array_value($_GET, "action")=="rss")) {
		return rss_admin();
  } else {
		return blog_admin();
  }
}

function blog_afterdisplay() {
}

function blog_initialize() {
  add_hook("admin_nav", "blog_adminnav");
}

function blog_install() {

}

function blog_uninstall() {

}

function blog_adminnav() {
	$nav=	createAdminNav("blog","blog");
	$nav.=	createAdminNav("blog","blog","rss");
	return $nav;
}

function blog_plugin() {
  return Array("info" => Array("author" => "Adam Hyde",
			       "license" => "AGPL",
			       "description" => "Make Blog posts. It also has rss 2.0 output (index.php?plugin=blog&action=rss).",
			       "requirements" => "May need htmltidy installed if you want nice results.",
			       "version" => "1.0")
	       );
}
