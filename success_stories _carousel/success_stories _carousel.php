<?php
/*
Plugin Name: Success Stories Carousel
Description: Perticuler post type category post in Carousel style.you can select any category of custom post type and selected category post will show in Carousel. 
Author: GrouponWorks
Version: 1.0
Author URI: http://www.grouponworks.com/
*/


class success_stories_carousel extends WP_Widget
{
  function success_stories_carousel()
  {
    $widget_ops = array('classname' => 'success_stories_carousel', 'description' => 'Displays a post with thumbnail in Carousel' );
    $this->WP_Widget('success_stories_carousel', 'Success Stories Carousel', $widget_ops);
  }
 
  function form($instance)
  {
    	
	$title = isset( $instance['title'] ) ? $instance['title'] : '';
	$success_stories_category = isset( $instance['success_stories_category'] ) ? $instance['success_stories_category'] : '';
	$number_post = isset( $instance['number_post'] ) ? $instance['number_post'] : '';
   
	$port_categories = get_categories(array('type' => 'success_stories','orderby' => 'ID', 'order' => 'ASC', 'taxonomy' => 'success_stories_category' ));
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
  <p>
  		<label for="success_stories_category">Success Stories Category</label><br />
		<select id="<?php echo $this->get_field_id('success_stories_category'); ?>" name="<?php echo $this->get_field_name('success_stories_category'); ?>">
		<option value="no_catrgory">Please Select</option>
			<?php foreach($port_categories as $port_category){ 
			
			echo '<option value="' . $port_category->slug . '"'
					. selected( $success_stories_category, $port_category->slug, false )
					. '>'. esc_html( $port_category->name ) . '</option>';
			?>
			<?php }?>
		</select>
</p>
<p><label for="<?php echo $this->get_field_id('number_post'); ?>">How Many Story you want set in carousel?: <input class="widefat" id="<?php echo $this->get_field_id('number_post'); ?>" name="<?php echo $this->get_field_name('number_post'); ?>" type="text" value="<?php echo esc_attr($number_post); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
	 $instance = array();
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		}
		if ( ! empty( $new_instance['success_stories_category'] ) ) {
			$instance['success_stories_category'] = strip_tags( stripslashes($new_instance['success_stories_category']) );
		}
		if ( ! empty( $new_instance['number_post'] ) ) {
			$instance['number_post'] = strip_tags( stripslashes($new_instance['number_post']) );
		}
		
	 
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
	
	
	if( ! empty($instance['number_post']))
	{
		
		$number_post=$instance['number_post'];
	}
	else
	{
		$number_post='100000000';
	}
	
	if($instance['success_stories_category'] == 'no_catrgory')
	{
		$success_stories_category = '';
			  
	$success_stories_categories_c =  new WP_Query(array('post_type'=>'success_stories', 'taxonomy'=>'success_stories_category',  'meta_key'=>'_thumbnail_id','posts_per_page' => $number_post ,'orderby' => 'menu_order', 'order' => 'asc'));
	}
	else
	{
		$success_stories_category = $instance['success_stories_category'];
			  
	$success_stories_categories_c =  new WP_Query(array('post_type'=>'success_stories', 'taxonomy'=>'success_stories_category', 'term' => ''.$success_stories_category.'', 'meta_key'=>'_thumbnail_id','posts_per_page' => $number_post ,'orderby' => 'menu_order', 'order' => 'asc'));
	}
	
 	
	

echo "<div class='info_area_inner'>";
	echo "<div class='container'>";
		if (!empty($title))
		  echo $before_title . $title . $after_title;
	
	
		echo "<div class='success_stories_flexslider carousel animate' data-animate='fadeIn'>";
			echo "<ul class='slides mobile_sli'>";
				for($b=0; $b<count($success_stories_categories_c->posts); $b++)
					{ 
					$permalink = get_permalink( $success_stories_categories_c->posts[$b]->ID );
					if(get_the_post_thumbnail($success_stories_categories_c->posts[$b]->ID, '') != '')
						{
							echo "<li>";
					echo "<a href='".$permalink."'>";
						echo "<div class='success_stories_carousel_image'>";			
							echo get_the_post_thumbnail($success_stories_categories_c->posts[$b]->ID, 'carousel_image_new');
						echo "</div>";
					echo "</a>";	
						echo "<div class='success_stories_carousel_details'>";
						echo "<a href='".$permalink."'>";
							echo "<div class='success_stories_carousel_title'>";
								echo $success_stories_categories_c->posts[$b]->post_title;
							echo "</div>";
						echo "</a>";
						echo "<a href='".$permalink."'>";	
							echo "<div class='success_stories_carousel_content'>";
								$string=strip_tags ($success_stories_categories_c->posts[$b]->post_excerpt);
								$substring=substr ($string,0,73);
								$string1=substr ($string,0,74);
								echo $substring;
									if ($substring < $string1)
									{
										echo '...';	
									}
							echo "</div>";
						echo "</a>";	
							echo "<div class='success_stories_carousel_read_more'>";
							$permalink1="javascript:";
								echo "<a href='".$permalink."'>Read the story »</a>";
							echo "</div>";
							
						echo "</div>";
					echo "</li>";
						}
					
				}
			echo "</ul>";	
		echo "</div>";
	echo "</div>";
echo "</div>";		
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("success_stories_carousel");') );?>