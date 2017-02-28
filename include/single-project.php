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
        $status = get_post_meta( get_the_ID(), 'project_status', true );
        $author = get_userdata($author_id);
        $freelancer = get_userdata($freelancer_id);

        // Freelancer accepts to work on this project
        if (isset($_POST['accept'])) {
            add_user_meta( get_current_user_id(), 'project_id', get_the_ID());
            update_post_meta(get_the_ID(), 'project_status', 'working');
            wp_mail($author->user_email, 'FREELANCER ACCEPTED', 'Freelancer ' . get_user_meta( $freelancer_id, 'last_name')[0] . ' has accepted your job! Take a look at ' . get_post_permalink( get_the_ID() ));
            $mes = 'Great job! We will contact to the employee!';
        }


        // Freelancer accepts to work on this project
        if (isset($_POST['reject'])) {
            update_post_meta(get_the_ID(), 'project_status', 'rejected');
            wp_mail($author->user_email, 'FREELANCER REJECTED', 'Freelancer ' . get_user_meta( $freelancer_id, 'last_name')[0] . ' has rejected your job! Please choose other one! Take a look at ' . get_post_permalink( get_the_ID() ));
            $mes = 'So sad! We will contact to the employee!';
        }

        // The Customer confirm this project is done by now
        if (isset($_POST['confirm_done'])) {
            update_post_meta(get_the_ID(), 'project_status', 'done');
            wp_mail($freelancer->user_email, 'CONFIRM JOB DONE', 'Congratulation! Customer ' . get_user_meta( $author_id , 'last_name')[0] . ' has confirm your work is done! Take a look at ' . get_post_permalink( get_the_ID() ));
            $mes = 'Congratulation!';
        }

        // Review handle
        if (isset($_POST['rate'])) {
            $data = array(
                'project_id'    => get_the_ID(),
                'rate'          => $_POST['rate']
            );
            if (isset($_POST['freelancer_review'])) {
                $data['from_id']    = $freelancer_id;
                $data['to_id']      = $author_id;
                $data['review']     = $_POST['freelancer_review'];
                

                add_user_meta( get_current_user_id(), 'project_id', get_the_ID());
                wp_mail(
                    $author->user_email, 
                    'FREELANCER REVIEW', 
                    'Freelancer ' . get_user_meta( $freelancer_id, 'last_name')[0] . ' has said something about you! Take a look at ' . get_post_permalink( get_the_ID() )
                );
                $mes = 'Great job! We will contact to the employee!';
            } else{
                $data['from_id']    = $author_id;
                $data['to_id']      = $freelancer_id;
                $data['review']     = $_POST['customer_review'];
                wp_mail(
                    $freelancer->user_email, 
                    'CUSTOMER REVIEW', 
                    'Customer ' . get_user_meta( $author_id , 'last_name')[0] . ' has said somthing about you! Take a look at ' . get_post_permalink( get_the_ID() )
                );
                $mes = 'Congratulation!';
            }
            if ($status == 'once_review') {
                update_post_meta(get_the_ID(), 'project_status', 'closed');
            }
            else{
                update_post_meta(get_the_ID(), 'project_status', 'once_review');
            }
            add_review($data);
        }
        $reviews = get_all_review_by_project_id( get_the_ID() );
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
                <div class="col-xs-6 col-sm-6 col-md-2 pull-left">
                    <?php echo get_avatar( $author_id, 120); ?>
                    <h5 class="text-center"><?php the_author();?></h5>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-2 pull-right text-center">
                    
                    <?php 
                    // Freelancer login 
                    if ( $freelancer_id == get_current_user_id() && $freelancer_id != 0 && $status == 'pending'): ?>
                        <form method="POST" action="">
                            <button class="btn pull-left" style="width: auto; padding: 10px;" type="submit" name="accept">
                                <?php _e('Accept', 'buddyfree'); ?>
                            </button>
                            <button class="btn pull-right" style="width: auto; padding: 10px;" type="submit" name="reject">
                                <?php _e('Reject', 'buddyfree'); ?>
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
                    if ( can_review(get_current_user_id(), get_the_ID()) ): ?>
                        <button class="btn" type="button" data-toggle="collapse" data-target="#review_form" aria-expanded="false" aria-controls="review_form">
                            <?php _e('Review', 'buddyfree'); ?>
                        </button>
                    <?php endif ; ?>
                    <?php 
                    // Close project
                    if ($status == 'closed') :?>
                        <button class="btn">Closed</button>
                    <?php endif; ?>
                    <strong style="margin-top: 30px;">
                        <span class="pull-right">
                            $<?php echo esc_html( get_post_meta( get_the_ID(), 'project_price', true ) ); ?>
                        </span>
                    </strong>

                </div>
                <div class="col-xs-12 col-sm-12 col-md-8">
                    
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
                                    <?php echo get_skill_by_skill_id($skill_id)[0]['skill_name']; ?>
                                </span>
                            <?php endforeach; ?>

                        </p>
                        
                    </header>
                    <?php if ( can_review(get_current_user_id(), get_the_ID()) ):?>
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
                                        <input type="hidden" id="rate" name="rate" required>
                                    </div>
                                    <?php if (get_current_user_id() == $author_id ): ?>
                                        <div class="form-group">
                                            <label for="customer_review">Review:</label>
                                            <textarea class="form-control" id="customer_review" placeholder="It was greate" name="customer_review" rows="5" required></textarea>
                                        </div>
                                    <?php else: ?>
                                        <div class="form-group">
                                            <label for="freelancer_review">Review:</label>
                                            <textarea class="form-control" id="freelancer_review" placeholder="It was greate" name="freelancer_review" rows="5" required></textarea>
                                        </div>
                                    <?php endif; ?>
                                    <button type="submit" class="btn btn-default">Send review</button>
                                </form>
                            </div>
                        </div>
                    <?php endif ;?>
                    <?php if (count($reviews)): ?>
                        <!-- <div class="clearfix"></div> -->
                        <h4 style="padding-top: 30px;">Reviews:</h4>
                        <?php foreach ($reviews as $review): 
                            $user = get_userdata( $review['from_id'] );
                        ?>
                            <div class="row review" style="margin-top: 20px;">
                                <div class="col-xs-3 col-sm-3 col-md-2">
                                    <a href="<?php echo USER_PAGE . $user->data->user_login . '/profile';?>">
                                        <?php echo get_avatar( $review['from_id'], 60); ?>
                                    </a>
                                </div>
                                <div class="col-xs-9 col-sm-9 col-md-10">
                                    <a href="<?php echo USER_PAGE . $user->data->user_login . '/profile';?>"><?php echo $user->data->display_name; ?></a>
                                    <p>
                                        <?php echo $review['review']; ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif ?>
                    
                </div>
                
            </div>
            
 
        </article>
 
    <?php endwhile; ?>
    </div>
</div>
<?php get_footer(); ?>