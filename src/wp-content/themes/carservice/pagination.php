<?php
function kriesi_pagination($ajax = false, $pages = '', $range = 2, $query_string = false, $outputEcho = true, $action = '', $top_margin = 'none', $query = null)
{
	$showitems = ($range * 2)+1;  

	global $paged;
	if(empty($paged)) $paged = 1;

	if($pages == '')
	{
		$pages = $query->max_num_pages;
		if(!$pages)
		{
			$pages = 1;
		}
	}

	if(1 != $pages)
	{
		if($query_string)
			parse_str($_SERVER["QUERY_STRING"], $query_string_array);
		
		$output = "<ul class='pagination clearfix" . ($ajax ? " ajax" : "") . ($action ? " " . esc_attr($action) : "") . ($top_margin!="none" ? " " . esc_attr($top_margin) : "") . "'>";
		if($paged > 2 && $paged > $range+1 && $showitems < $pages) $output .= "<li><a href='".($query_string ? "?" . esc_url(http_build_query(array_merge($query_string_array,  array("paged"=>1)))) : esc_url(get_pagenum_link(1)))."'>&laquo;</a></li>";
		if($paged > 1 && $showitems < $pages) $output .= "<li><a href='".($query_string ? "?" . esc_url(http_build_query(array_merge($query_string_array,  array("paged"=>$paged-1)))) :  esc_url(get_pagenum_link($paged - 1)))."'>&lsaquo;</a></li>";

		for ($i=1; $i <= $pages; $i++)
		{
			if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
			{
				$output .= "<li" . ($paged == $i ? " class='selected'" : "") . ">" . ($paged == $i && !$ajax ? "<span>".$i."</span>":"<a href='".($query_string ? "?" . esc_url(http_build_query(array_merge($query_string_array,  array("paged"=>$i)))) :  esc_url(get_pagenum_link($i)))."'>".$i."</a>") . "</li>";
			}
		}

		if ($paged < $pages && $showitems < $pages) $output .= "<li><a href='".($query_string ? "?" . esc_url(http_build_query(array_merge($query_string_array,  array("paged"=>$paged + 1)))) : esc_url(get_pagenum_link($paged + 1)))."'>&rsaquo;</a></li>";  
		if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) $output .= "<li><a href='".($query_string ? "?" . esc_url(http_build_query(array_merge($query_string_array,  array("paged"=>$pages)))) : esc_url(get_pagenum_link($pages)))."'>&raquo;</a></li>";
		$output .= "</ul>";
		if($outputEcho)
			echo $output;
		else
			return $output;
	}
}
?>