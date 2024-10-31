<?php
/*
Plugin Name: Schedule Planner
Plugin URI: http://blog.artesea.co.uk/2009/12/a-post-from-the-future.html
Description: Displays a list of the last two posts, plus the scheduled posts on the Edit/New Post page to allow you to plan your posts schedules.
Author: Ryan Cullen
Version: 0.2
Author URI: http://blog.artesea.co.uk/
*/

function draw_schedule_planner() {
	global $wpdb, $wp_locale;

	echo '<div class="postbox">' . "\n";
	echo ' <h3>Scheduler</h3>' . "\n";
	echo ' <div class="inside">' . "\n";
	echo '  <p>' . "\n";

	//start point
	//get all future posts + the last two
	
	$recent_posts_query  = "SELECT * FROM $wpdb->posts WHERE `post_status` LIKE 'publish' ORDER BY `post_date` DESC LIMIT 0, 2";
	$recent_posts_result = $wpdb->get_results($recent_posts_query);

	$future_posts_query  = "SELECT * FROM $wpdb->posts WHERE `post_status` LIKE 'future' ORDER BY `post_date` ASC";
	$future_posts_result = $wpdb->get_results($future_posts_query);
	
	$old_date = 0;
	
	$recent_posts_result = array_reverse($recent_posts_result);
	
	echo '   <strong>Published:</strong><br />' . "\n";
	
	foreach($recent_posts_result as $post) {
		$new_date = strtotime($post->post_date);
		if($old_date < date("Ymd", $new_date)) {
			$old_date = date("Ymd", $new_date);
			echo '   <em>' . date("D jS M y", $new_date) . '</em><br />' . "\n";
		}
		echo '   ' . date("H:i", $new_date) . ' <a href="post.php?action=edit&amp;post=' . $post->ID . '">' . $post->post_title . '</a><br />' . "\n";
	}
	
	echo '   <strong>Scheduled:</strong><br />' . "\n";
	
	$i = 1;
	$p = 5; //scheduled posts to show before before a more link hidey thing
	$x = sizeof($future_posts_result);

	foreach($future_posts_result as $post) {
		$new_date = strtotime($post->post_date);
		if($old_date < date("Ymd", $new_date)) {
			$old_date = date("Ymd", $new_date);
			echo '   <em>' . date("D jS M y", $new_date) . '</em><br />' . "\n";
		}
		echo '   ' . date("H:i", $new_date) . ' <a href="post.php?action=edit&amp;post=' . $post->ID . '">' . $post->post_title . '</a><br />' . "\n";
		if($i==$p && $x>$p) {
			echo '   <span id="aspMore">&raquo; <a href="#" onclick="document.getElementById(\'aspLess\').style.display=\'inline\';document.getElementById(\'aspMore\').style.display=\'none\';return false;">more</a><br /></span>' . "\n";
			echo '   <span id="aspLess" style="display:none;">&laquo; <a href="#" onclick="document.getElementById(\'aspLess\').style.display=\'none\';document.getElementById(\'aspMore\').style.display=\'inline\';return false;">less</a><br />' . "\n";
		}
		$i++;
	}
	if($x>$p) {
		echo '   </span>';
	}

	echo '  </p>' . "\n";
	echo ' </div>' . "\n";
	echo '</div>' . "\n";
}

add_action('submitpost_box', 'draw_schedule_planner');
?>