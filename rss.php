<script src="jquery.js"></script>
<script src="lodash.js"></script>
<script src="moment.js"></script>
<script src="date.js"></script>
<title>My RSS Feed</title>
<link rel='icon' href="psi.ico" />
<style>
* {
	/*margin: 0px;*/
	/*padding: 0px;*/
}
table, td {
	border: 1px solid black;
	border-collapse: collapse;
}
a {
	text-decoration: none;
}
a:visited {
	color: #0000EE;
}
</style>
<div style='display: none;'>
<?
$links = [
"http://extremetech.com/feed",
"http://xkcd.com/rss.xml",
// "http://www.echojs.com/rss",
"http://feeds.feedburner.com/dailyjs.xml"
];
foreach($links as $link):
	echo file_get_contents($link);
endforeach;
?>
</div>
<script>
var log = console.log.bind(console);
$.fn.extract = function(tag) {
	return $(this).children(tag).text().trim() || $(this).find(tag).eq(0).text().trim();
}
function asrow() {
	var row = "<tr>", i, len = arguments.length;
	for(i = 0; i < len; i++) {
		row += "<td>";
		row += arguments[i];
		row += "</td>";
	}
	row += "</tr>";
	return row;
}
function asanchor(link) {
	return $("<a></a>", {href: link.url || link.id, title: link.desc || ""}).text(link.title)[0].outerHTML;
}
$(function() {
	$("rss").find("link").each(function() {
		var url = $(this)[0].nextSibling.nodeValue;
		$(this)[0].parentElement.removeChild($(this)[0].nextSibling);
		$(this).replaceWith($("<link></link>").text(url.trim()));
	});
	$("rss channel").each(function() {
		var channel = {};
		channel.title = $(this).extract("title");
		channel.url = $(this).extract("link");
		channel.desc = $(this).extract("desc");
		channel.a = asanchor(channel);
		$(this).find("item").each(function() {
			var link = {};
			link.title = $(this).extract("title");
			link.url = $(this).extract("link");
			link.desc = $(this).extract("desc");
			link.a = asanchor(link);
			link.date = new Date($(this).extract("pubdate"));
			$("table").append(asrow(channel.a, link.a, moment(link.date).format("YYYY-MM-DD hh:mm:ss A")));
		});
	})
	$("feed").each(function() {
		var feed = {};
		feed.title = $(this).extract("title");
		feed.id = $(this).extract("id");
		feed.a = asanchor(feed);
		$(this).find("entry").each(function() {
			var link = {};
			link.title = $(this).extract("title");
			link.id = $(this).extract("id");
			link.date = new Date($(this).extract("updated"));
			link.a = asanchor(link);
			$("table").append(asrow(feed.a, link.a, moment(link.date).format("YYYY-MM-DD hh:mm:ss A")));
		});
	});
	$("table tr").sort(function(a, b) {
		a = $(a).find("td").eq(2).text();
		b = $(b).find("td").eq(2).text();
		return moment(b).diff(moment(a));
	}).appendTo($("table"));

})
</script>
<table>
</table>
