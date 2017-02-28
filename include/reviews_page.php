<style type="text/css">
	#subnav{
		display: none;
	}
</style>

<div id="about-me">
	<h3>About me</h3>
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse imperdiet diam nec lorem lacinia ullamcorper. Praesent ac pharetra tortor. Morbi a magna ut ligula sollicitudin auctor. Cras fringilla, augue ut ultricies varius, dui dui sollicitudin elit, sed dictum ex ex quis nisl. Vivamus sit amet pharetra enim, sit amet volutpat mi. Fusce feugiat ut nibh quis dictum. Cras in orci varius, tristique odio commodo, convallis leo.</p>
</div>
<?php if (count($reviews)):
	foreach ($reviews as $review):
	    $user = get_userdata( $review['from_id'] ); ?>
	    <div class="row review" style="margin-top: 20px;">
	        <div class="col-xs-2 col-sm-2 col-md-2">
	            <a href="<?php echo USER_PAGE . $user->data->user_login . '/profile';?>">
	                <?php echo get_avatar( $review['from_id'], 90); ?>
	            </a>
	        </div>
	        <div class="col-xs-10 col-sm-10 col-md-10">
	            <a href="<?php echo USER_PAGE . $user->data->user_login . '/profile';?>">
	            	<?php echo $user->data->display_name; ?></a>
	            <p>
	                <?php echo $review['review']; ?>
	            </p>
	            <p>
	            	<strong>Project: </strong>
	            	<a href="<?php echo get_post_permalink($review['project_id']);?>">
	            		<?php echo get_the_title( $review['project_id'] ); ?>
            		</a>
	            </p>
	            <div class="rate">
		            <?php $rate = $review['rate']; ?>
	            	<span><?php echo $rate;?></span>
	            	<?php for ($i=1; $i <= 5; $i++) :?>
	            		<i class="fa fa-star <?php if($i <= $rate) echo 'rated';?>" aria-hidden="true"></i>
	            	<?php endfor; ?>
	            </div>
	            	 
	            </p>
	        </div>
	    </div>
	<?php endforeach;
endif; ?>
