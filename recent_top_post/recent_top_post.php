<?php
/*
Plugin Name: Recent post & Top Post
Description: Recent Post And Top Post accordian Widget. 
Author: GrouponWorks
Version: 1.0
Author URI: http://www.grouponworks.com/
*/
class recent_top_post extends WP_Widget
{
  function recent_top_post()
  {
    $widget_ops = array('classname' => 'recent_top_post', 'description' => 'Displays Recent & Top Post Accordian' );
    $this->WP_Widget('recent_top_post', 'Recent post & Top Post', $widget_ops);
  }
 
  function form($instance)
  {
    	
	$title = isset( $instance['title'] ) ? $instance['title'] : '';
	$number_post = isset( $instance['number_post'] ) ? $instance['number_post'] : '';
   
?>
  	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>">Title: 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</label>
	</p>
  
	<p>
		<label for="<?php echo $this->get_field_id('number_post'); ?>">How Many Story you want set in carousel?: 
			<input class="widefat" id="<?php echo $this->get_field_id('number_post'); ?>" name="<?php echo $this->get_field_name('number_post'); ?>" type="text" value="<?php echo esc_attr($number_post); ?>" />
		</label>
	</p>
	
<?php
  }
 
  function update($new_instance, $old_instance)
  {
	 $instance = array();
	 
		if ( ! empty( $new_instance['title'] ) ) 
		{
			$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		}
		
		if ( ! empty( $new_instance['number_post'] ) ) 
		{
			$instance['number_post'] = strip_tags( stripslashes($new_instance['number_post']) );
		}
	 
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    	$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$number_post=$instance['number_post'];
	
		if($number_post != '')
		{
			$recent_post =  new WP_Query(array('post_type'=>'post','taxonomy'=>'category','posts_per_page'=>$number_post,'orderby' => 'post_date', 'order' => 'desc'));
			$top_post =  new WP_Query(array('post_type'=>'post','taxonomy'=>'category','posts_per_page'=>$number_post,'meta_key'=>'groupon_works_top_post','orderby' => 'post_date', 'order' => 'desc'));
		}
		else
		{
			$recent_post =  new WP_Query(array('post_type'=>'post','taxonomy'=>'category','posts_per_page'=>'4','orderby' => 'post_date', 'order' => 'desc'));
			$top_post =  new WP_Query(array('post_type'=>'post','taxonomy'=>'category','posts_per_page'=>'4','meta_key'=>'groupon_works_top_post','orderby' => 'post_date', 'order' => 'desc'));
		}
	
	
 
    // WIDGET CODE GOES HERE
		echo "<div class='info_area_inner'>";
			echo "<div class='container recent_top_post'>";
				if (!empty($title))
		  
		  		echo '<div class="recent_top_post_inner">';
			  		echo '<div class="accordion" id="section1">Recent Post<span class="fa"></span></div>';
			  		echo '<div class="accordian_details">';
						for($r=0; $r<count($recent_post->posts); $r++)
							{ 
								$permalink=get_permalink($recent_post->posts[$r]->ID);
									echo '<div class="post_title">';
										echo '<a href="'.$permalink.'">'.$recent_post->posts[$r]->post_title.'</a>';
									echo '</div>';
							}
			  		echo '</div>';
			  
			  		echo '<div class="accordion without_border" id="section2">Top Post<span class="fa"></span></div>';
			  		echo '<div class="accordian_details">';
						for($t=0; $t<count($top_post->posts); $t++)
							{ 
								$groupon_works_top_post=get_post_meta( $top_post->posts[$t]->ID,'groupon_works_top_post');
								if($groupon_works_top_post[0] == 'yes')
									{
										$permalink=get_permalink($top_post->posts[$t]->ID);
										echo '<div class="post_title">';
											echo '<a href="'.$permalink.'">'.$top_post->posts[$t]->post_title.'</a>';
										echo '</div>';
									}
									else
									{
								
									}
							}
			  		echo '</div>';
				echo '</div>';	  
		
			echo "</div>";
		echo "</div>";		

    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("recent_top_post");') );?>