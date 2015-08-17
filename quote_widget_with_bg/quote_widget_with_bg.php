<?php
/*
Plugin Name: Quote Widget
Description: Quote post type with category and Parallax Background. 
Author: GrouponWorks
Version: 1.0
Author URI: http://www.grouponworks.com/
*/


class quote_widget_with_bg extends WP_Widget
{
  function quote_widget_with_bg()
  {
    $widget_ops = array('classname' => 'quote_widget_with_bg', 'description' => 'Displays a post with thumbnail in Carousel' );
    $this->WP_Widget('quote_widget_with_bg', 'Quote Widget', $widget_ops);
  }
 
  function form($instance)
  {
    	
	$title = isset( $instance['title'] ) ? $instance['title'] : '';
	$quote_category = isset( $instance['quote_category'] ) ? $instance['quote_category'] : '';
	$background_image = isset( $instance['background_image'] ) ? $instance['background_image'] : '';
	$number_post = isset( $instance['number_post'] ) ? $instance['number_post'] : '';
   
	$port_categories = get_categories(array('type' => 'quote','orderby' => 'ID', 'order' => 'ASC', 'taxonomy' => 'quote_category' ));
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
  <p>
  		<label for="blog_category">Quote Category</label><br />
		<select id="<?php echo $this->get_field_id('quote_category'); ?>" name="<?php echo $this->get_field_name('quote_category'); ?>">
			<?php
						echo '<option value="no_category">Please Select</option>';

			 foreach($port_categories as $port_category){ 
			echo '<option value="' . $port_category->slug . '"'
					. selected( $quote_category, $port_category->slug, false )
					. '>'. esc_html( $port_category->name ) . '</option>';
			?>
			<?php }?>
		</select>
</p>

<p>
			  <label for="<?php echo $this->get_field_id('background_image'); ?>">Background Image</label><br />
				<img class="custom_media_image_background_image" src="<?php if(!empty($instance['background_image'])){echo $instance['background_image'];} ?>" style="margin:0;padding:0;max-width:100px;float:left;display:inline-block" />
				<input type="text" class="widefat custom_media_url_background_image" name="<?php echo $this->get_field_name('background_image'); ?>" id="<?php echo @$this->get_field_id('background_image'); ?>" value="<?php echo @$instance['background_image']; ?>">
				<input type="button" value="<?php _e( 'Upload Image', 'theme name' ); ?>" class="button custom_media_upload_background_image" id="custom_image_uploader_background_image"/>
			</p>

<p><label for="<?php echo $this->get_field_id('number_post'); ?>">How Many Quote you want set ?: <input class="widefat" id="<?php echo $this->get_field_id('number_post'); ?>" name="<?php echo $this->get_field_name('number_post'); ?>" type="text" value="<?php echo esc_attr($number_post); ?>" /></label></p>			
<?php
  }
 
  function update($new_instance, $old_instance)
  {
	 $instance = array();
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		}
		if ( ! empty( $new_instance['quote_category'] ) ) {
			$instance['quote_category'] = strip_tags( stripslashes($new_instance['quote_category']) );
		}
		if ( ! empty( $new_instance['background_image'] ) ) {
			$instance['background_image'] = strip_tags( $new_instance['background_image'] );
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
	//echo $instance['blog_category'];
	if($instance['quote_category'] != 'no_category')
	{
		$quote_category = $instance['quote_category'];
				$quote_categories_c =  new WP_Query(array('post_type'=>'quote', 'taxonomy'=>'quote_category', 'term' => ''.$quote_category.'', 'posts_per_page' => ''.$number_post.'' ,'orderby' => 'rand'));

	}
	else
	{
		$quote_category = '';
				$quote_categories_c =  new WP_Query(array('post_type'=>'quote', 'taxonomy'=>'quote_category',  'posts_per_page' => ''.$number_post.'' ,'orderby' => 'rand'));

	}
	$background_image=$instance['background_image'];
	$str_q = substr(strrchr($background_image, 'wp-content'), 0);
				$file_ti_url_q=filemtime($str_q);
 
    // WIDGET CODE GOES HERE
   echo "<div class='info_area_inner quote_widget_main fullscreen background parallax' style='background-image:url($background_image?$file_ti_url_q);' data-img-width='1600' data-img-height='1064' data-diff='100'>";
	echo "<div class='slider_overlay'>";
		echo "<div class='container'>";
			if (!empty($title))
			  echo $before_title . $title . $after_title;
		echo '<div class="quote_inner">';
			echo '<ul class="slides">';
				for($i=0; $i<count($quote_categories_c->posts); $i++)
				{
					$quote_image=get_post_meta($quote_categories_c->posts[$i]->ID,'groupon_works_quote_image',true);
					echo "<li>";
						echo "<div class='quote_widget'>";
							echo "<div class='quote_content'>";
								echo ''.$quote_categories_c->posts[$i]->post_content.'';
							echo "</div>";
							
							if($quote_image !=='')
							{
								echo "<div class='quote_author_image_main'>";
									echo "<div class='quote_author_image'>";
										echo "<img src='".$quote_image."' />";
									echo "</div>";
								echo "</div>";	
							}
							else
							{
								echo "<div class='quote_divider'>";
							echo "</div>";
							}
							echo "<div class='quote_autor_name'>";
								echo "<span>".$quote_categories_c->posts[$i]->post_title.'</span>, '.get_post_meta($quote_categories_c->posts[$i]->ID,'groupon_works_quote_quoter_position',true);
							echo "</div>";
						echo "</div>";
					echo "</li>";
				}
			echo '</ul>';
		echo "</div>";
		echo "</div>";
	echo "</div>";
echo "</div>";		
    echo $after_widget;
  }
 
}
function ctUp_wdScript(){
	  wp_enqueue_media();
	}
	
add_action('admin_enqueue_scripts', 'ctUp_wdScript');
add_action( 'widgets_init', create_function('', 'return register_widget("quote_widget_with_bg");') );?>