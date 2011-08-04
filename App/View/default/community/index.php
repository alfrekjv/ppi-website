<div class="community-page">
	<div class="left-side">
		<img src="<?= $baseUrl; ?>images/community/community.jpg" alt="Community">
	</div>
    <div class="content-box">

        <div class="topcontent">
                <div class="filter">Filter: <a href="<?= $baseUrl; ?>community/">All</a> - <a href="<?= $baseUrl; ?>community/index/filter/twitter">Twitter</a> - <a href="<?= $baseUrl; ?>community/index/filter/github">Github</a> </div>
                <div class="back-to-homepage"><a href="<?= $baseUrl; ?>">Back to Homepage</a></div>
        </div>

        <div class="activity-stream">
            <div class="feeds">
            <?php
            foreach($activity as $item):
                $feedImage  = $baseUrl . 'images/community/' . $item['source'] . '.png';
            ?>
                <div class="feed">
                    <div class="image">
                        <img class="fl <?= $item['source']; ?>" src="<?= $feedImage; ?>" alt="<?= $item['source']; ?>" />
                    </div>
                    <div class="content">
                        <div class="description"><?= $item['title']; ?></div>
                        <div class="actions">
                            <a href="<?= $item["url"];?>" class="readmore">Read More</a>
                            <div class="social">
                                <g:plusone></g:plusone>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
            ?>
            </div>
        </div>
    </div>
</div>