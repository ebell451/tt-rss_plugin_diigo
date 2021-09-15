function shareArticleTodiigo(id) {


	var d = new Date();
	var ts = d.getTime();
        Notify.progress("Opening Diigo window");

	try {
		xhrPost("backend.php",
			{
				'op': 'pluginhandler',
				'plugin': 'diigo',
				'method': 'getInfo',
				'id': encodeURIComponent(id)
			},
			(reply) => {
				if (reply) {
					if (reply.status == "200") {
						Notify.info("Diigo windows openned");

						var ti = JSON.parse(reply.responseText);

						var share_url = "http://www.diigo.com/post?url=" + encodeURIComponent(ti.link) + "&title=" + encodeURIComponent(ti.title);

						window.open(share_url, 'ttrss_diigo',
							"status=0,toolbar=0,location=0,width=1000,height=950,scrollbars=1,menubar=0");
					} else {
						Notify.error("<strong>Error: " + reply.status + " encountered while sharing to DIIGO!</strong>", true);
					}
				} else {
					Notify.error("The Diigo plugin needs to be configured. See the README for help", true);
				}
			},);

	} catch (e) {
		App.Error.report(e);	
	}
}
