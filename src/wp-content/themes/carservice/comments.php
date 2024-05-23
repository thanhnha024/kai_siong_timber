<?php
if(comments_open())
{
if(have_comments()):
	$comments_count = get_comments_number();
	?>
	<h3 class="box-header"><?php echo $comments_count . " " . ($comments_count!=1 ? __("COMMENTS", 'carservice') : __("COMMENT", 'carservice')); ?></h3>
	<ul id="comments-list">
		<?php
		paginate_comments_links();
		wp_list_comments(array(
			'avatar_size' => 90,
			'page' => (isset($_GET["paged"]) ? (int)$_GET["paged"] : 1),
			'per_page' => '5',
			'callback' => 'cs_theme_comments_list'
		));
		?>
	<?php
	global $post;
	$query = $wpdb->prepare("SELECT COUNT(*) AS count FROM $wpdb->comments WHERE comment_approved = 1 AND comment_post_ID = %d AND comment_parent = 0", get_the_ID());
	$parents = $wpdb->get_row($query);
	if($parents->count>5)
		cs_comments_pagination(2, ceil($parents->count/5));
	?>
	</ul>
	<?php
endif;
}
function cs_theme_comments_list($comment, $args, $depth)
{
	global $post;
	$GLOBALS['comment'] = $comment;
?>
	<li <?php comment_class('comment clearfix'); ?> id="comment-<?php echo esc_attr(get_comment_ID()); ?>">
		<div class="comment-author-avatar">
			<?php echo get_avatar( $comment->comment_author_email, $args['avatar_size'] ); ?>
		</div>
		<div class="comment-details">
			<div class="posted-by clearfix">
				<h6>
				<?php 
				comment_author_link();
				if((int)$comment->comment_parent>0)
				{	
					$parent_author = get_comment_author((int)$comment->comment_parent);
					echo '<a href="#comment-' . esc_attr((int)$comment->comment_parent) . '" class="in-reply">@' . $parent_author . '</a>';
				}
				?>
				</h6>
				<abbr title="<?php printf(esc_html__(' %1$s, %2$s', 'carservice'), get_comment_date(),  get_comment_time()); ?>" class="timeago"><?php printf(__(' %1$s, %2$s', 'carservice'), get_comment_date(),  get_comment_time()); ?></abbr>
				<a class="more simple small reply-button" href="#<?php echo esc_attr(get_comment_ID()); ?>" title="<?php esc_attr_e('Reply', 'carservice'); ?>"><?php _e('REPLY', 'carservice'); ?></a>
			</div>
			<?php 
			comment_text(); 
			edit_comment_link(__('(Edit)', 'carservice'),'<br>','<br><br>'); 
			?>
		</div>
<?php
}
function cs_comments_pagination($range, $pages)
{
	$paged = (!isset($_GET["paged"]) || (int)$_GET["paged"]==0 ? 1 : (int)$_GET["paged"]);
	$showitems = ($range * 2)+1;
	
	echo "<ul class='pagination page-margin-top'>";
	//if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li class='left'><a href='#page-1'></a></li>";
	if($paged > 1 && $showitems < $pages) echo "<li><a href='#page-" . esc_attr(($paged-1)) . "'>&lsaquo;</a></li>";

	for ($i=1; $i <= $pages; $i++)
	{
		if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
		{
			echo "<li" . ($paged == $i ? " class='selected'" : "") . ">" . ($paged == $i ? "<span>".$i."</span>":"<a href='#page-" . absint($i) . "'>".$i."</a>") . "</li>";
		}
	}

	if ($paged < $pages && $showitems < $pages) echo "<li><a href='#page-" . esc_attr(($paged+1)) . "'>&rsaquo;</a></li>";  
	//if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li><a href='#page-" . esc_url($pages) . "' class='pagination_arrow'>&raquo;</a></li>";
	echo "</ul>";
}
?>