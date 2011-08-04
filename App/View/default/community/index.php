<div class="gp" style="display: none;">
	<g:plusone callback="testCallback"></g:plusone>
</div>
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
                            <a href="<?= $item["url"];?>" target="_blank" class="readmore">Read More</a>
                            <div class="social gplus-here">

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


<!-- Place this tag after the last plusone tag -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>

<script type="text/javascript">

	var timeoutID = setTimeout("loadGoogle()", 1000);
	var i = 1;
	function loadGoogle() {
		if(++i === 5) {
			clearTimeout(timeoutID);
			return;
		}
		var html = $('.gp').html();
		if(html.indexOf('___plusone') != -1) {
			$('.gplus-here').html(html);
			clearTimeout(timeoutID);
			return;
		}
		timeoutID = setTimeout("loadGoogle()", 1000);
	}
</script>