<div class="community-page">
	<div class="left-side">
		<img src="<?= $baseUrl; ?>images/community/community.jpg" alt="Community">
	</div>
	<div class="activity-stream content-box">
		<h1>Activity Stream</h1>
		<div class="feeds">
		<?php
		foreach($activity as $item):
			$feedImage  = $baseUrl . 'images/community/' . $item['source'] . '.png';
		?>
			<div class="feed">
				<img class="fl <?= $item['source']; ?>" src="<?= $feedImage; ?>" alt="<?= $item['source']; ?>" />
				<p class="fl content"><?= $item['title']; ?></p>
				<div class="clear"></div>
			</div>
		<?php
		endforeach;
		?>
		</div>
	</div>
</div>