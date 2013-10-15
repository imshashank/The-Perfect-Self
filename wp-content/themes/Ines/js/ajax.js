jQuery(document).ready(function($) {
    var $mainContent = $("section"),
        siteUrl = "http://" + top.location.host.toString(),
        url = ''; 
		
    $(document).delegate("a[href^='"+siteUrl+"']:not([href*=/wp-admin/]):not([href*=/wp-comments-post.php]):not([href*=replytocom]):not([href*=/wp-login.php]):not([href$=/feed/])", "click", function() {
        location.hash = this.pathname;
        return false;
    }); 
    $("#searchform").submit(function(e) {
        location.hash = '?s=' + $("#s").val();
        e.preventDefault();
    }); 
    $(window).bind('hashchange', function(){
        url = window.location.hash.substring(1); 
        if (!url) {
            return;
        } 
		if (url.indexOf("comment")!= -1) { return; }
		else if (url.indexOf("respond")!= -1) { return; }
		
        url = url + " article"; 
        $mainContent.animate({opacity: "0.1"}).html('<img src="/wp-content/themes/aj/images/ajax-loader.gif" width="16" height="16" alt="Loading">').load(url, function() {
            $mainContent.animate({opacity: "1"});
        });
    });
    $(window).trigger('hashchange');
});