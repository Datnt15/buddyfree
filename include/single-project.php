<?php
 /*
 Template Name: Single project
 */
 
get_header(); ?>
<div id="primary">
    <div id="content" role="main">
    <?php $mes = ''; ?>
    <?php while ( have_posts() ) : the_post();
        if (isset($_POST['project_id'])) {
            add_user_meta( get_current_user_id(), 'project_id', $_POST['project_id']);
            update_post_meta(get_the_ID(), 'project_status', 'working');
            $mes = 'Great job! We will contact to the employee!';
        }
        if (isset($_POST['confirm_done'])) {
            update_post_meta(get_the_ID(), 'project_status', 'done');
            $mes = 'Congratulation!';
        }
    ?>
    <?php $freelancer_id = get_post_meta( get_the_ID(), 'freelancer_id', true );
    $status = get_post_meta( get_the_ID(), 'project_status', true ); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="margin-top: 30px;">
            <div class="col-md-12">
                <?php if ($mes != '') { ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $mes; ?>
                    </div>
                <?php } ?>
                <div class="col-xs-3 col-sm-3 col-md-2">
                    <?php echo get_avatar( get_the_author_meta('ID'), 120); ?>
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
                </div>
                <div class="col-xs-12 col-sm-3 col-md-2">
                    
                    <?php if ( $freelancer_id == get_current_user_id() && $freelancer_id != 0 && $status == 'pending'): ?>
                        <form method="POST" action="">
                            <button class="btn" type="submit" name="project_id" value="<?php the_ID(); ?>">
                                <?php _e('Accept', 'buddyfree'); ?>
                            </button>
                        </form>
                    <?php endif ?>
                    <?php if ( get_the_author_meta('ID') == get_current_user_id() && $status == 'working'): ?>
                        <form method="POST" action="">
                            <button class="btn" type="submit" name="confirm_done">
                                <?php _e('Done', 'buddyfree'); ?>
                            </button>
                        </form>
                    <?php endif ?>
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