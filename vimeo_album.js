var $va = jQuery.noConflict();

var vidHeight = 280;
var vidWidth = 504;
var embedTarget ;	
var oEmbedCallback = 'embedVideo' ;
var oEmbedUrl = 'http://vimeo.com/api/oembed.json';
	
// This function puts the video on the page
function embedVideo(video) {
		var videoEmbedCode = video.html;
		$va(embedTarget).html(unescape(videoEmbedCode));
}

// This function loads the data from Vimeo
function loadScript(url, embedTarget) {
		var js = document.createElement('script');
		js.setAttribute('src', url);
		if ( document.getElementsByTagName('head').item(0).lastChild.getAttribute('src') !== js.getAttribute('src'))
		{	document.getElementsByTagName('head').item(0).appendChild(js);	}				
}

function va_init() {
	var links = $va('#content').find('.vimeo_album_wrapper').find('a.vimeoAlbumA');
	$va(links).click(function(e){
		embedTarget =  '#embed'+ $va(this).closest('div').attr('id').substring(5) ;
		loadScript(oEmbedUrl + '?url=' + $va(this).attr('href') + '&width=' + vidWidth + '&height=' + vidHeight + '&callback=' + oEmbedCallback);
		e.preventDefault();
		return false;		
	});	
}			
			
$va(document).ready(function() {
	va_init();
});
	

		