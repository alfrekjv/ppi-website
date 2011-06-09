<?php
class APP_Controller_Home extends APP_Controller_Application {

	function index() {
		$this->load('home/index');
	}
	
	public static function getNews()
	{
		$news = array();
		$feed = 'https://github.com/dragoonis/ppi-website/commits/master.atom';
		$xml = simplexml_load_file($feed);
		foreach($xml->entry as $commit){
		$timestamp = strtotime($commit->updated);
			$news[$timestamp] = array(
				'url'	=> (string) $commit->link['href'],
				'title'	=> (string) $commit->title,
			);
		}
		return $news;
	}
}
