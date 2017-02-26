<div id="search-form">
	<input type="text" placeholder="Search by skills(PHP, CSS, HTML, etc.)">
	<!-- <input type="hidden" id="skill_values" value=""> -->
	<button class="btn">Search</button>
</div>
<div id="search-results">
	<div class="row">
		<div class="title-left col-xs-6 text-left pull-left">
			<h4> Results (24) </h4>
		</div>

		<!-- <div class="title-right col-xs-6 text-right">
			<select id="sort-type" class="form-control pull-right">
				<option value="">Sort type</option>
				<option value="top_rate">Top rate</option>
			</select>
		</div> -->
	</div>
	<?php for ($j=0; $j < 10; $j++): ?>
		<div class="result">
			<div class="result-header">
				<div class="user-avatar col-xs-3 col-sm-2">
					<?php echo get_avatar(1); ?>
				</div>
				<div class="user-title col-xs-9 col-sm-10">
					<div class="name">
						<a href="<?= USER_PAGE; ?>">John Doe</a>
						<span class="full-time">full time</span>
						<span class="price pull-right hidden-xs">$44/h</span>
					</div>
					<div class="rate">
						<span>Reply rate: <strong>82%</strong></span>
					</div>
					<div class="visible-xs">
						<span>Fee: <strong>$44/h</strong></span>
					</div>
					<div class="short-intro">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et ...
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="result-body">
				<div class="skills col-xs-12">
					<?php for ($i=0; $i < 4; $i++): ?>
						<span class="skill"> HTML </span>
						<span class="skill"> PHP </span>
						<span class="skill"> CSS </span>
						<span class="skill"> Javascript </span>
					<?php endfor; ?>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="result-footer">
				<div class="available-days col-xs-12">
					<a href="#" class="available-day">Monday</a>
					<a href="#" class="available-day">Tuesday</a>
					<a href="#" class="available-day">Wendsday</a>
					<a href="#" class="available-day">Thurdday</a>
					<a href="#" class="available-day">Friday</a>
					<a href="#" class="available-day">Saturday</a>
					<a href="#" class="available-day">Sunday</a>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	<?php endfor; ?>
</div>
