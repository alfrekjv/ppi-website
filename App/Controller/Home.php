<?php
class APP_Controller_Home extends APP_Controller_Application {

	function index() {
		$allNews = $this->getNews();
		$this->load('home/index', compact('allNews'));
	}

	public function getNews() {
		$news = array();
		$news[] = array(
			'source' => 'ppi',
			'title'	 => 'Testing the news feature'
		);
		$feeds = array(
			'https://github.com/dragoonis/ppi-framework/commits/master.atom',
			'https://github.com/dragoonis/ppi-skeleton-app/commits/master.atom',
			'https://github.com/dragoonis/ppi-website/commits/master.atom',
			'https://github.com/dragoonis/ppi-issue-tracker/commits/master.atom',
		);
		foreach($feeds as $feed) {
			$xml = simplexml_load_file($feed);
			if(isset($xml->entry)) {
				foreach($xml->entry as $commit){
					$timestamp = strtotime( (string) $commit->updated);
					list($day, $month, $year) = explode('/', date('d/m/Y', $timestamp));
					if($year == 2011) {
						$title = (string) $commit->title;
						if(strlen($title) > 70) {
							$title = trim(substr($title, 0, 70)) . '..';
						}
						$news[$timestamp] = array(
							'source' => 'github',
							'url'	 => (string) $commit->link['href'],
							'title'	 => $title . ' on ' . date('jS M Y \a\t g:i a', $timestamp)
						);
					}
				}
			}
		}
		krsort($news);
		return $news;
	}
}
