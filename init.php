<?php
class diigo extends Plugin {
	private $host;

	function init($host) {
		$this->host = $host;

		$host->add_hook($host::HOOK_ARTICLE_BUTTON, $this);
	}

	function about() {
		return array(1.6,
			"Share article on diigo.",
			"ebell451, Niek",
			false);
	}

	function get_js() {
		return file_get_contents(dirname(__FILE__) . "/diigo.js");
	}

	function hook_article_button($line) {
		$article_id = $line["id"];

		$rv = "<img src=\"plugins.local/diigo/diigo.png\"
			class='tagsPic' style=\"cursor : pointer\"
			onclick=\"shareArticleTodiigo($article_id)\"
			title='".__('Share on diigo.')."'>";

		return $rv;
	}

	function getInfo() {
		$id = $_REQUEST['id'];

		$result = $this->pdo->prepare("SELECT title, link 
		FROM ttrss_entries, ttrss_user_entries 
		WHERE id = ? AND ref_id = id  AND owner_uid = ?");
		$result->execute([$id, $_SESSION['uid']]);

		if ($row = $result->fetch()) {
			$title = truncate_string(strip_tags($row['title']), 100, '...');
			$article_link = $row['link'];
			print json_encode(array("title" => $title, "link" => $article_link,	"id" => $id));
		} else {
			print json_encode(array( "status" => "Database fail" ));
		}

	}

	function api_version() {
		return 2;
	}

}
?>