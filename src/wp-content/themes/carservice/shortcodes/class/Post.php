<?php
class Cs_Post
{
	public $blog_type;
	public $featured_image_size;
	public $show_post_date;
	public $show_post_views;
	public $show_post_comments;
	public $show_post_categories;
	public $show_post_tags;
	public $show_post_excerpt;
	public $show_post_author;
	public $i;
	
	public function __construct($blog_type, $featured_image_size, $show_post_date, $show_post_views, $show_post_comments, $show_post_categories, $show_post_tags, $show_post_excerpt, $show_post_author, $i)
	{
		$this->blog_type = $blog_type;
		$this->featured_image_size = $featured_image_size;
		$this->show_post_date = $show_post_date;
		$this->show_post_views = $show_post_views;
		$this->show_post_comments = $show_post_comments;
		$this->show_post_categories = $show_post_categories;
		$this->show_post_tags = $show_post_tags;
		$this->show_post_excerpt = $show_post_excerpt;
		$this->show_post_author = $show_post_author;
		$this->i = $i;
	}
	
	public function getPostDetails()
	{
		$output = '';
		if($this->show_post_date || $this->show_post_author || $this->show_post_categories || $this->show_post_tags || $this->show_post_views || $this->show_post_comments)
		{
			if($this->blog_type!="small")
				$output .= '<div class="post-content-details-container clearfix">';
			if($this->show_post_date || $this->show_post_author || $this->show_post_categories || $this->show_post_tags)
			{
				$output .= '<ul class="post-content-details' . ($this->show_post_views || $this->show_post_comments ? ' margin-right-20' : '') . '">';
				if($this->show_post_date)
				{
					$output .= '<li>' . date_i18n(get_option('date_format'), get_post_time()) . '</li>';
				}
				if($this->show_post_author)
				{
					$output .= '<li>' . __("By ", 'carservice') . '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" title="' . esc_attr(get_the_author()) . '">' . get_the_author() . '</a></li>';
				}
				if($this->show_post_categories)
				{
					$categories = get_the_category();
					$output .= '<li>' . __("In ", 'carservice');
					foreach($categories as $key=>$category)
					{
						$output .= '<a class="category-' . esc_attr($category->term_id) . '" href="' . esc_url(get_category_link($category->term_id)) . '" ';
						if(empty($category->description))
							$output .= 'title="' . sprintf(__('View all posts filed under %s', 'carservice'), esc_attr($category->name)) . '"';
						else
							$output .= 'title="' . esc_attr(strip_tags(apply_filters('category_description', $category->description, $category))) . '"';
						$output .= '>' . $category->name . '</a>' . ($category != end($categories) ? ', ' : '');
					}
					$output .= '</li>';
				}
				if($this->show_post_tags)
				{
					$tags = get_the_tags();
					if($tags)
					{
						$output .= '<li>' . __("Tags ", 'carservice');
						foreach($tags as $key=>$tag)
						{
							$output .= '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" ';
							if(empty($tag->description))
								$output .= 'title="' . sprintf(__('View all posts filed under %s', 'carservice'), esc_attr($tag->name)) . '"';
							else
								$output .= 'title="' . esc_attr(strip_tags(apply_filters('tag_description', $tag->description, $tag))) . '"';
							$output .= '>' . $tag->name . '</a>' . ($tag != end($tags) ? ', ' : '');
						}
						$output .= '</li>';
					}
				}
				$output .= '</ul>';
			}
			if($this->show_post_views || $this->show_post_comments)
			{
				$output .= '<ul class="post-content-details ' . ($this->blog_type=="small" ? 'float' : 'right') . '">';
				if($this->show_post_views)
				{
					$views_count = cs_getPostViews(get_the_ID());
					$output .= '<li' . ($this->blog_type=="blog" ? ' class="template-eye"' : '') . '>' . $views_count . ($this->blog_type=="small" ? ' ' . ($views_count==1 ? __('view', 'carservice') : __('views', 'carservice')) : '') . '</li>';
				}
				if($this->show_post_comments)
				{
					$comments_count = get_comments_number();
					$output .= '<li class="template-bubble"><a href="' . esc_url(get_comments_link()) . '" title="' . esc_attr($comments_count) . ' ' . ($comments_count==1 ? esc_html__('comment', 'carservice') : esc_html__('comments', 'carservice')) . '">' . $comments_count . '</a></li>';
				}
				$output .= '</ul>';
			}
			if($this->blog_type!="small")
				$output .= '</div>';
		}
		return $output;
	}
	
	public function getLiCssClass($extra_class=null)
	{
		$post_classes = get_post_class("post");
		$output = '<li class="' . ($this->blog_type=="small" ? 'small' : '') . (!empty($extra_class) ? esc_attr($extra_class) : '');
		foreach($post_classes as $key=>$post_class)
			$output .= ' ' . esc_attr($post_class);
		$output .= '">';
	
		return $output;
	}
	
	public function getThumbnail($default_image_size = null)
	{
		$output = '';
		if(has_post_thumbnail())
		{
			$output .= '<a class="post-image" href="' . esc_url(get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">' . get_the_post_thumbnail(get_the_ID(), ($this->featured_image_size!="default" ? $this->featured_image_size : $default_image_size), array("alt" => get_the_title(), "title" => "")) . '</a>';	
		}
		return $output;
	}
}