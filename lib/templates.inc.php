<?
function addTemplate ($templateType='read',$content) {
	if ($templateType=='editor') {
		$template=file_get_contents("_templates/editor.tmpl");
	} else {
		$template=file_get_contents("_templates/".DEFAULT_TEMPLATE."_$templateType.tmpl");
	} 
  	$page=preg_replace('[<content-goes-here/>]',$content,$template);
//following works but i first have to work out how to find json rows
		$hook_args['output']=$page;
 		$page = fire_hook("tag_replace", $hook_args);
	return $page;
}
?>
