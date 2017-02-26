<?php
 /*
 Template Name: Single project
 */
 
get_header(); ?>
<div id="primary">
    <div id="content" role="main">
    <?php $mes = ''; ?>
    <?php while ( have_posts() ) : the_post();
        // Global values
        $freelancer_id = get_post_meta( get_the_ID(), 'freelancer_id', true );
        $author_id = get_the_author_meta('ID');

        // Freelancer accepts to work on this project
        if (isset($_POST['project_id'])) {
            add_user_meta( get_current_user_id(), 'project_id', get_the_ID());
            update_post_meta(get_the_ID(), 'project_status', 'working');
            wp_mail(get_user_meta( $author_id, 'user_email')[0], 'FREELANCER ACCEPTED', 'Freelancer ' . get_user_meta( $freelancer_id, 'last_name')[0] . ' has accepted your project!' . '\t\n\n' . 'Take a look at ' . get_post_permalink( get_the_ID() ));
            $mes = 'Great job! We will contact to the employee!';
        }

        // The Customer confirm this project is done by now
        if (isset($_POST['confirm_done'])) {
            update_post_meta(get_the_ID(), 'project_status', 'done');
            wp_mail(get_user_meta( $freelancer_id, 'user_email')[0], 'CONFIRM PROJECT DONE', 'Congratulation! Customer ' . get_user_meta( $author_id , 'last_name')[0] . ' has confirm your work is done!' . '\t\n\n' . 'Take a look at ' . get_post_permalink( get_the_ID() ));
            $mes = 'Congratulation!';
        }

        // Global values
        $status = get_post_meta( get_the_ID(), 'project_status', true );
    ?>
    
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="margin-top: 30px;">
            <div class="col-md-12">
                <?php if ($mes != '') { ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $mes; ?>
                    </div>
                <?php } ?>
                <div class="col-xs-3 col-sm-3 col-md-2">
                    <?php echo get_avatar( $author_id, 120); ?>
                    <h5><?php the_author();?></h5>
                </div>
                <div class="col-xs-9 col-sm-6 col-md-8">
                    
                    <header class="entry-header">
         
                        <!-- Display Title and Author Name -->
                        <h4><a href=""><?php the_title(); ?></a></h4>
                        <p style="margin-top: 30px;">
                            <strong><?php the_content(); ?></strong>
                            
                        </p>
                        <p style="margin-top: 30px;">
                            <?php $skills = unserialize(get_post_meta( get_the_ID(), 'skill_requirement', true )); 
                            foreach ($skills as $skill_id): ?>
                                <span class="skill">
                                    <?php echo get_skill_by_skill_id($skill_id)[0]['name']; ?>
                                </span>
                            <?php endforeach; ?>

                        </p>
                    </header>
                    <?php if ( $status == 'done' || $static == 'once_review' ): ?>
                        <div class="collapse col-xs-12" id="review_form">
                            <div class="row">
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label for="rate">Rating: </label>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <input type="hidden" id="rate" name="rate">
                                    </div>
                                    <div class="form-group">
                                        <label for="review">Review:</label>
                                        <textarea class="form-control" id="review" placeholder="It was greate"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-default">Send review</button>
                                </form>
                            </div>
                        </div>
                    <?php endif ;?>
                    
                </div>
                <div class="col-xs-12 col-sm-3 col-md-2">
                    
                    <?php 
                    // Freelancer login 
                    if ( $freelancer_id == get_current_user_id() && $freelancer_id != 0 && $status == 'pending'): ?>
                        <form method="POST" action="">
                            <button class="btn" type="submit" name="project_id" value="<?php the_ID(); ?>">
                                <?php _e('Accept', 'buddyfree'); ?>
                            </button>
                        </form>
                    <?php endif ;

                    // Customer login
                    if ( $author_id == get_current_user_id() && $status == 'working'): ?>
                        <form method="POST" action="">
                            <button class="btn" type="submit" name="confirm_done">
                                <?php _e('Done', 'buddyfree'); ?>
                            </button>
                        </form>
                    <?php endif; ?>


                    <?php 
                    // Freelancer and customer review
                    if ( $status == 'done' || $static == 'once_review' ): ?>
                        <button class="btn" type="button" data-toggle="collapse" data-target="#review_form" aria-expanded="false" aria-controls="review_form">
                            <?php _e('Review', 'buddyfree'); ?>
                        </button>
                    <?php endif ; ?>
                    <strong>
                        <span class="pull-right">
                            $<?php echo esc_html( get_post_meta( get_the_ID(), 'project_price', true ) ); ?>
                        </span>
                    </strong>

                </div>
            </div>
            
 
        </article>
 
    <?php endwhile; ?>
    </div>
</div>
<?php get_footer(); ?>