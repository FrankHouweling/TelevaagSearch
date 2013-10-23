$(document).ready(function() {
	$('#my-timeline').timelinexml({ 
		src : 'timeline.xml',
		showLatest : false, 
		selectLatest : false,
		eventTagName : "event",
		dateTagName : "date",
		titleTagName : "title",
		thumbTagName : "thumb",
		contentTagName : "content",
		linkTagName : "link",
		htmlEventClassName : "timeline-event",
		htmlDateClassName : "timeline-date",
		htmlTitleClassName : "timeline-title",
		htmlContentClassName : "timeline-content",
		htmlLinkClassName : "timeline-link",
		htmlThumbClassName : "timeline-thumb"
	});
});