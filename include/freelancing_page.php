<style type="text/css">
	#subnav{
		display: none;
	}
</style>

<div id="about-me">
	<h3>About me</h3>
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse imperdiet diam nec lorem lacinia ullamcorper. Praesent ac pharetra tortor. Morbi a magna ut ligula sollicitudin auctor. Cras fringilla, augue ut ultricies varius, dui dui sollicitudin elit, sed dictum ex ex quis nisl. Vivamus sit amet pharetra enim, sit amet volutpat mi. Fusce feugiat ut nibh quis dictum. Cras in orci varius, tristique odio commodo, convallis leo.</p>
</div>

<div class="col-md-6 col-md-12 col-xs-12">
	<div class="row">
		<h3 class="my-work-title">
			Skills 
			<?php if ($is_author): ?>
				<button class="btn btn-small add-skill-btn" id="add-skill-btn" type="button" data-toggle="collapse" data-target="#add-skill-form" aria-expanded="false" aria-controls="add-skill-form">
				    Add new
				</button>
			<?php endif; ?>
		</h3>
		<?php if ($is_author): ?>
			<div class="collapse" id="add-skill-form">
		        <form class="form-inline" action="" method="POST">
				    <div class="form-group">
				        <input type="text" class="form-control" id="new_skill" name="new_skill" placeholder="Find a skill">
					    <!-- <button type="submit" class="add-skill-btn btn btn-default" id="add-new-skill">
					    	Add
					    </button> -->
					    <div id="sugget_results"></div>
				    </div>
				</form>
			</div>
		<?php endif; ?>
		<div id="user_skills">
			<?php foreach ($skills as $skill) {?>
				<span class="skill">
					<?php echo $skill['skill_name']; ?>
					<?php if ($is_author): ?>
						<i class="fa fa-times" id="<?php echo $skill['id']; ?>" aria-hidden="true"></i>
					<?php endif; ?>
				</span>
			<?php }?>
		</div>
	</div>
</div>
<div class="col-md-6 col-md-12 col-xs-12">
	<div class="row">
		<h3 class="my-work-title">
			Schedule
		</h3>
		<div id="mini-clndr"></div>
	</div>
	<script id="mini-clndr-template" type="text/template">

        <div class="controls">
          <div class="clndr-previous-button">&lsaquo;</div><div class="month"><%= month %></div><div class="clndr-next-button">&rsaquo;</div>
        </div>

        <div class="days-container">
          <div class="overlayer">
            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
          </div>
          <div class="days">
            <div class="headers">
              <% _.each(daysOfTheWeek, function(day) { %><div class="day-header"><%= day %></div><% }); %>
            </div>
            <% _.each(days, function(day) { %><div class="<%= day.classes %>" id="<%= day.date %>"><%= day.day %></div><% }); %>
          </div>
          <div class="events">
            <div class="headers">
              <div class="x-button">x</div>
              <div class="event-header">Schedule</div>
            </div>
            <div class="events-list">
              <% _.each(days, function(day) { %>
                <div id="detail_<%= day.date %>" class="detail text-center">
                  <?php for($i = 0, $j = 12; $i < 12; $i += 2 ): 
			        if ($i < 10) {
			          $time = $i . ' AM - ' . ($i+2) . ' AM';
		            }else{
			          $time = $i . ' AM - ' . ($i+2) . ' PM';
		            }?>
                  <div class="event">
                    <input type="checkbox" name="time[]" value="AM<?= $i ?>" class="styled" id="<%= day.date %>AM<?= $i ?>">
                    <label for="<%= day.date %>AM<?= $i ?>"> <?php echo $time; ?></label>
                  </div>
                  <?php  $time = $j . ' PM - ';
			      $j += 2;
			        if ($j > 12) {
					  $j = 2;
					  $time .= $j . ' PM';
					}else{
					  if ($j < 10) {
						$time .= $j . ' PM';
					  }else {
						$time .= '0 AM';
				      }
					} 
				  ?>
                  <div class="event">
                    <input type="checkbox" name="time[]" value="PM<?= $i ?>" class="styled" id="<%= day.date %>PM<?= $i ?>">
                    <label for="<%= day.date %>PM<?= $i ?>"> <?php echo $time; ?> </label>
                  </div>
                  <?php endfor; ?>
                  
                  <?php if ($is_author) {?>
                    <button class="add-schedule-btn">Save schedule</button>
                  <?php } ?>
                </div>
              <% }); %>
            </div>
          </div>
        </div>

    </script>
</div>
