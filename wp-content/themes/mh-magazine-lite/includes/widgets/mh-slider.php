<?php

/***** MH Slider [lite] Widget *****/

class mh_slider_hp_widget extends WP_Widget {
    function __construct() {
		parent::__construct(
			'mh_slider_hp', esc_html_x('MH Slider Widget [lite]', 'widget name', 'mh-magazine-lite'),
			array('classname' => 'mh_slider_hp', 'description' => esc_html__('Slider widget for use on homepage template.', 'mh-magazine-lite'))
		);
	}
    function widget($args, $instance) {
        extract($args);
        $category = isset($instance['category']) ? $instance['category'] : '';
        $tags = empty($instance['tags']) ? '' : $instance['tags'];
        $postcount = empty($instance['postcount']) ? '5' : $instance['postcount'];
        $offset = empty($instance['offset']) ? '' : $instance['offset'];
        $sticky = isset($instance['sticky']) ? $instance['sticky'] : 0;
        echo $before_widget; ?>
        	<div id="mh-slider-<?php echo rand(1, 9999); ?>" class="flexslider mh-slider-widget">
				<ul class="slides"><?php
					$args = array('posts_per_page' => $postcount, 'cat' => $category, 'tag' => $tags, 'offset' => $offset, 'ignore_sticky_posts' => $sticky);
					$slider = new WP_query($args);
					while ($slider->have_posts()) : $slider->the_post(); ?>
						<li>
							<article class="slide-wrap">
								<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php
									if (has_post_thumbnail()) {
										the_post_thumbnail('content');
									} else {
										echo '<img class="mh-image-placeholder" src="' . esc_url(get_template_directory_uri() . '/images/noimage_620x264.png') . '" alt="No Picture" />';
									} ?>
								</a>
								<div class="slide-caption">
									<div class="slide-data">
										<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
											<h2 class="slide-title">
												<?php the_title(); ?>
											</h2>
										</a>
									</div>
								</div>
							</article>
						</li><?php
					endwhile;
					wp_reset_postdata(); ?>
				</ul>
			</div><?php
        echo $after_widget;
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['category'] = absint($new_instance['category']);
        $instance['tags'] = sanitize_text_field($new_instance['tags']);
        $instance['postcount'] = absint($new_instance['postcount']);
        $instance['offset'] = absint($new_instance['offset']);
        $instance['sticky'] = isset($new_instance['sticky']) ? strip_tags($new_instance['sticky']) : '';
        return $instance;
    }
    function form($instance) {
        $defaults = array('category' => '', 'tags' => '', 'postcount' => '5', 'offset' => '0', 'sticky' => 0);
        $instance = wp_parse_args((array) $instance, $defaults); ?>
	    <p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Select a Category:', 'mh-magazine-lite'); ?></label>
			<select id="<?php echo $this->get_field_id('category'); ?>" class="widefat" name="<?php echo $this->get_field_name('category'); ?>">
				<option value="0" <?php if (!$instance['category']) echo 'selected="selected"'; ?>><?php _e('All', 'mh-magazine-lite'); ?></option>
				<?php
				$categories = get_categories(array('type' => 'post'));
				foreach($categories as $cat) {
					echo '<option value="' . $cat->cat_ID . '"';
					if ($cat->cat_ID == $instance['category']) { echo ' selected="selected"'; }
					echo '>' . $cat->cat_name . ' (' . $cat->category_count . ')';
					echo '</option>';
				}
				?>
			</select>
		</p>
		<p>
        	<label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Filter Posts by Tags (e.g. lifestyle):', 'mh-magazine-lite'); ?></label>
			<input class="widefat" type="text" value="<?php echo esc_attr($instance['tags']); ?>" name="<?php echo $this->get_field_name('tags'); ?>" id="<?php echo $this->get_field_id('tags'); ?>" />
	    </p>
		<p>
        	<label for="<?php echo $this->get_field_id('postcount'); ?>"><?php _e('Show:', 'mh-magazine-lite'); ?></label>
			<input type="text" size="2" value="<?php echo esc_attr($instance['postcount']); ?>" name="<?php echo $this->get_field_name('postcount'); ?>" id="<?php echo $this->get_field_id('postcount'); ?>" /> <?php _e('Posts', 'mh-magazine-lite'); ?>
	    </p>
	    <p>
        	<label for="<?php echo $this->get_field_id('offset'); ?>"><?php _e('Skip:', 'mh-magazine-lite'); ?></label>
			<input type="text" size="2" value="<?php echo esc_attr($instance['offset']); ?>" name="<?php echo $this->get_field_name('offset'); ?>" id="<?php echo $this->get_field_id('offset'); ?>" /> <?php _e('Posts', 'mh-magazine-lite'); ?>
	    </p>
        <p>
      		<input id="<?php echo $this->get_field_id('sticky'); ?>" name="<?php echo $this->get_field_name('sticky'); ?>" type="checkbox" value="1" <?php checked('1', $instance['sticky']); ?>/>
	  		<label for="<?php echo $this->get_field_id('sticky'); ?>"><?php _e('Ignore Sticky Posts', 'mh-magazine-lite'); ?></label>
    	</p>
    	<p>
    		<strong><?php esc_html_e('Info:', 'mh-magazine-lite'); ?></strong> <?php _e('This is the Lite Version of this widget with only basic features. If you need more features and options, you can upgrade to the premium version of this theme.', 'mh-magazine-lite'); ?>
    	</p><?php
    }
}

?>