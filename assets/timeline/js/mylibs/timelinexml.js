(function($) {
	// contentLength -> how many characters it will show in the box. Line: ~103
	// "model.events[i].link.text()" -> link text. Replace with a string of your choise. Line: ~158
	var model, view, controller;
	
	var methods = {
		lim : function(val, min, max) {
			if (val < min) {
				val = min;
			} else if (val > max) {
				val = max;
			}
			return val;
		}
	};
	
	function Model(options) {
		this.rawData = new Array();
		this.src = options.src;
		this.eventTagName = 		options.eventTagName;
		this.dateTagName = 			options.dateTagName;
		this.titleTagName = 		options.titleTagName;
		this.thumbTagName = 		options.thumbTagName;
		this.contentTagName = 		options.contentTagName;
		this.linkTagName = 			options.linkTagName;
		this.htmlEventClassName = 	options.htmlEventClassName;
		this.htmlDateClassName = 	options.htmlDateClassName;
		this.htmlTitleClassName = 	options.htmlTitleClassName;
		this.htmlContentClassName = options.htmlContentClassName;
		this.htmlLinkClassName = 	options.htmlLinkClassName;
		this.htmlThumbClassName = 	options.htmlThumbClassName;
		this.events = new Array();
		this.xmlDoc = 1;
		this.allMonths = [
			"Jan",
			"Feb",
			"Mar",
			"Apr",
			"May",
			"Jun",
			"Jul",
			"Aug",
			"Sep",
			"Oct",
			"Nov",
			"Dec"
		]
		
		this.nYears = 0;
		this.nMonths = 0;
		this.nDays = 0;
		
		this.startYear = 30000;
		this.endYear = 0;
	}
	function View(options) {
		this.eventPositions = new Array();
		this.eventNodeWidth = 0;
		this.width = 0;
		this.yearWidth = 0;
		this.monthWidth = 0;
		this.dayWidth = 0;
		this.hourWidth = 0;
		this.minuteWidth = 0;
		this.secondWidth = 0;
		
		this.showYears = false;
		this.showMonths = false;
		this.showEveryNthMonth = 1;
		this.showEveryNthYear = 1;
		this.showDays = false;
		this.showHours = false;
		this.showMinutes = false;
		this.showSeconds = false;
		
		this.nLargeScale = 0;
		this.nSmallScale = 0;
		this.step = 0;
		
		this.monthId = 0;
		this.monthPosition = 0;
		this.yearPosition = 0;
		this.yearId = 0;
		
		this.selectedEvent = 0;
		this.selectedEventContentsWidth = 0;
		this.eventMargin = 0;
	}
	function Controller() {
		this.selectedEventContentsWidth = 0;
	}
	
	Model.prototype.loadXML = function(options) {
		var root = this;
		$.get(root.src, function(data) {
			root.xmlDoc = data;
			options.callback();
		});
	};
	Model.prototype.getXMLContent = function() {
		var events = this.xmlDoc.getElementsByTagName(this.eventTagName);
		for (var i=0; i<events.length; i++) {
			var link = '<a href="'+$(events[i]).find(this.linkTagName).find('a').attr('href')+'">'+$(events[i]).find(this.linkTagName).find('a').text()+'</a>';
			this.rawData[i] = {
				date : 		$(events[i]).find(this.dateTagName).text(),
				title : 	$(events[i]).find(this.titleTagName).text(),
				thumb : 	$(events[i]).find(this.thumbTagName).text(),
				content : 	$(events[i]).find(this.contentTagName).text(),
				link : 		link
			};
		}
	}
	Model.prototype.getHTMLContent = function() {
		var events = $('.'+this.htmlEventClassName);
		for (var i=0; i<events.length; i++) {
			this.rawData[i] = {
				date : 		$(events[i]).find('.'+this.htmlDateClassName).html(),
				title : 	$(events[i]).find('.'+this.htmlTitleClassName).html(),
				thumb : 	'',
				content : 	$(events[i]).find('.'+this.htmlContentClassName).html(),
				link : 		$(events[i]).find('.'+this.htmlLinkClassName).html()
			};
			
			if ($(events[i]).find('.'+this.htmlThumbClassName).length != 0) {
				this.rawData[i].thumb = $(events[i]).find('.'+this.htmlThumbClassName).html();
			}
		}
	};
	Model.prototype.parseRawData = function() {
		var root = this;
		var date,year,month;
		for (var i=0; i<root.rawData.length; i++) {
			
			if (root.rawData[i].date.search('BC') == -1) {
				var newDate = root.rawData[i].date;
				var dateParts = newDate.split('.');

				date = dateParts[0];
				month = dateParts[1];
				year = parseInt(dateParts[2]);

				// parse date and month
				var intdate = 0;
				if (parseInt(date[0]) == 0) {
					intdate = parseInt(date[1]);
				} else {
					intdate = parseInt(date);
				}
				var intmonth = 0;
				if (parseInt(month[0]) == 0) {
					intmonth = parseInt(month[1]);
				} else {
					intmonth = parseInt(month);
				}
				var datestring = date+'.'+month+'.'+year;
			} else {
				var datestring = root.rawData[i].date;
				year = parseInt('-'+root.rawData[i].date.replace(' ', '').replace('BC', ''));
			}
			
			// titleLength -> how many characters it will show in the title.
			var titleLength = 30;			
			var title = root.rawData[i].title;

			if (title.length > titleLength) {
				title = title.slice(0, titleLength);
				title = title + "...";
			}
			
			// thumb
			var hasThumb = false;

			if (root.rawData[i].thumb != '') {
				hasThumb = true;
				var thumb = '<img src="'+root.rawData[i].thumb+'">';
			}
			
			// contentLength -> how many characters it will show in the box.
			
			var contentLength = 400;			
			var content = root.rawData[i].content;

			if (content.length > contentLength) {
				content = content.slice(0, contentLength);
				content = content + "...";
			}
			
			root.events[i] = {
				id : 		i,
				datestring : datestring,
				date : 		intdate,
				year : 		year,
				month : 	intmonth,
				title : 	title,
				content : 	content,
				link : 		root.rawData[i].link,
				hasThumb : 	hasThumb,
				thumb : 	thumb
			};
		}	

		for (var i=0; i<root.events.length; i++) {
			if (parseInt(root.events[i].year) > root.endYear) {
				root.endYear = parseInt(root.events[i].year);
			}
		}
		for (var i=0; i<root.events.length; i++) {
			if (parseInt(root.events[i].year) < root.startYear) {
				root.startYear = parseInt(root.events[i].year);
			}
		}
		root.nYears = root.endYear - root.startYear + 1;
		root.nMonths = Math.ceil((root.endDate - root.startDate)/root.months);
		root.nDays = Math.ceil((root.endDate - root.startDate)/root.days);
	}
	Model.prototype.parseXML = function() {
		var root = this;
		// Build the model
		var events = root.xmlDoc.getElementsByTagName(root.eventTagName);
		
		var date,year,month;
		for (var i=0; i<events.length; i++) {
			var newDate = $(events[i]).find(root.dateTagName).text();
			var dateParts = newDate.split('.');
			
			date = dateParts[0];
			month = dateParts[1];
			year = parseInt(dateParts[2]);
			
			// parse date and month
			var intdate = 0;
			if (parseInt(date[0]) == 0) {
				intdate = parseInt(date[1]);
			} else {
				intdate = parseInt(date);
			}
			var intmonth = 0;
			if (parseInt(month[0]) == 0) {
				intmonth = parseInt(month[1]);
			} else {
				intmonth = parseInt(month);
			}
			
			// titleLength -> how many characters it will show in the box.
			var titleLength = 30;			
			var title = $(events[i]).find(root.titleTagName).text();

			if (title.length > titleLength) {
				title = title.slice(0, titleLength);
				title = title + "...";
			}
			
			// thumb
			var hasThumb = false;
			if ($(events[i]).find(root.thumbTagName).length != 0) {
				hasThumb = true;
				var thumb = '<img src="'+$(events[i]).find(root.thumbTagName).text()+'">';
			}
			
			// contentLength -> how many characters it will show in the box.
			
			var contentLength = 400;			
			var content = $(events[i]).find(root.contentTagName).text();

			if (content.length > contentLength) {
				content = content.slice(0, contentLength);
				content = content + "...";
			}
			
			
			root.events[i] = {
				id : 		i,
				date : 		intdate,
				year : 		year,
				month : 	intmonth,
				title : 	title,
				content : 	content,
				link : 		$(events[i]).find(root.linkTagName).find('a'),
				hasThumb : 	hasThumb,
				thumb : 	thumb
			};
		}	

		for (var i=0; i<root.events.length; i++) {
			if (parseInt(root.events[i].year) > root.endYear) {
				root.endYear = parseInt(root.events[i].year);
			}
		}
		for (var i=0; i<root.events.length; i++) {
			if (parseInt(root.events[i].year) < root.startYear) {
				root.startYear = parseInt(root.events[i].year);
			}
		}
		root.nYears = root.endYear - root.startYear + 1;
		root.nMonths = Math.ceil((root.endDate - root.startDate)/root.months);
		root.nDays = Math.ceil((root.endDate - root.startDate)/root.days);
	}
	Model.prototype.parseHTML = function() {
		var root = this;
		// Build the model
		var events = $('.timeline-event');
		
		var date,year,month;
		for (var i=0; i<events.length; i++) {
			var newDate = $(events[i]).find('.'+root.htmlDateClassName).html();
			var dateParts = newDate.split('.');
			
			date = dateParts[0];
			month = dateParts[1];
			year = parseInt(dateParts[2]);
			
			// parse date and month
			var intdate = 0;
			if (parseInt(date[0]) == 0) {
				intdate = parseInt(date[1]);
			} else {
				intdate = parseInt(date);
			}
			var intmonth = 0;
			if (parseInt(month[0]) == 0) {
				intmonth = parseInt(month[1]);
			} else {
				intmonth = parseInt(month);
			}
			
			// titleLength -> how many characters it will show in the box.
			var titleLength = 30;			
			var title = $(events[i]).find('.'+root.htmlTitleClassName).html();

			if (title.length > titleLength) {
				title = title.slice(0, titleLength);
				title = title + "...";
			}
			
			// thumb
			var hasThumb = false;
			if ($(events[i]).find('.'+root.htmlThumbClassName).length != 0) {
				hasThumb = true;
				var thumb = '<img src="'+$(events[i]).find('.'+root.htmlThumbClassName).html()+'">';
			}
			
			// contentLength -> how many characters it will show in the box.			
			var contentLength = 400;			
			var content = $(events[i]).find('.'+root.htmlContentClassName).html();

			if (content.length > contentLength) {
				content = content.slice(0, contentLength);
				content = content + "...";
			}
			
			
			root.events[i] = {
				id : 		i,
				date : 		intdate,
				year : 		year,
				month : 	intmonth,
				title : 	title,
				content : 	content,
				link : 		$(events[i]).find('.'+root.htmlLinkClassName).find('a'),
				hasThumb : 	hasThumb,
				thumb : 	thumb
			};
		}	

		for (var i=0; i<root.events.length; i++) {
			if (parseInt(root.events[i].year) > root.endYear) {
				root.endYear = parseInt(root.events[i].year);
			}
		}
		for (var i=0; i<root.events.length; i++) {
			if (parseInt(root.events[i].year) < root.startYear) {
				root.startYear = parseInt(root.events[i].year);
			}
		}
		root.nYears = root.endYear - root.startYear + 1;
		root.nMonths = Math.ceil((root.endDate - root.startDate)/root.months);
		root.nDays = Math.ceil((root.endDate - root.startDate)/root.days);
	};
	
	View.prototype.init = function(options) {
		var root = this;
		root.target = options.target;		
		root.width = root.target.outerWidth();
		root.yearWidth = 	root.width/(model.nYears);
		root.monthWidth = 	root.yearWidth/12;
		root.dayWidth = 	root.monthWidth/30;
		
		if (options.mode === 'html') {
			$('.timeline-html-wrap').hide();
		}
		
		root.target.after().append('<div class="timeline-event-node"></div>');
		root.eventNodeWidth = $('.timeline-event-node').first().outerWidth();
		$('.timeline-event-node').remove();
		
		if (root.monthWidth <= 8) {
			root.showMonths = false;
		} else {
			root.showMonths = true;
		}
		root.showEveryNthMonth = Math.round((root.width/root.monthWidth) / 12);
		root.showEveryNthMonth = root.showEveryNthMonth - root.showEveryNthMonth%2;
		if (model.nYears == 1) { root.showEveryNthMonth = 1; }
		
		
		if (root.yearWidth > root.width) {
			root.showYears = false;
		} else root.showYears = true;
		
		root.showEveryNthYear = Math.round((root.width/root.yearWidth) / 12);
		var str = root.showEveryNthYear+'';
		var rounding = 1;
		for (var k=0; k<str.length-1; k++) {
			rounding = rounding+'0';
		}
		
		root.showEveryNthYear = Math.round(root.showEveryNthYear/parseInt(rounding))*parseInt(rounding);
		root.showEveryNthYear = (root.showEveryNthYear < 1) ? 1 : root.showEveryNthYear;
		
		root.nLargeScale = model.nYears;
		root.nSmallScale = model.nMonths;
		root.step = root.monthWidth/30;
	},
	View.prototype.drawEvents = function() {
		var root = this;
		var html = '<div class="timeline-wrap">';
		html 	+= '	<div class="timeline-events">';
		html 	+= '	<div class="timeline-years timeline-large-scale"></div>';
		html 	+= '	<div class="timeline-months timeline-small-scale"></div>';
		for (var i=0; i<model.events.length; i++) {
			html +=	'		<div class="timeline-event timeline-bottom" id="timeline-event-'+model.events[i].id+'">';
			html += '			<div class="timeline-event-node" id="timeline-event-node-'+model.events[i].id+'"></div>'
			html += '			<div class="timeline-event-arrow"></div>';			
			html += '			<div class="timeline-event-contents">';

			if (model.events[i].title != 0) {
				html += '			<div class="timeline-event-title"><span>'+model.events[i].datestring+'</span>'+model.events[i].title+'</div>';
			}
			if (model.events[i].content != 0) {
				html += '			<div class="timeline-event-content">';
				if (model.events[i].hasThumb) {
					html += model.events[i].thumb;
				}
				html += model.events[i].content+'</div>';
			}

			if (model.events[i].link != 0) {
				html += '			<div class="timeline-event-link">'+model.events[i].link+'</div>';
			}
			html += '			</div>';
			html += '		</div>';
		}
		html	+= '	</div>';
		html	+= '</div>';
		root.target.html(html);
	},
	View.prototype.setData = function() {
		var root = this;
		var target = 0;
		var targetContents = 0;
		for (var i=0; i<model.events.length; i++) {
			target = $('#timeline-event-'+model.events[i].id);
			targetContents = target.find('.timeline-event-contents');
			targetContents.show();
			target.data({ "offset" : targetContents.offset().left, "smallWidth" : targetContents.width(), "margin" : parseInt(target.find('.timeline-event-contents').css('margin-left').replace('px', '')) });
			targetContents.hide();
		}
	},
	View.prototype.addDates = function() {
		var root = this;
		if (root.showYears) {
			for (var i=0; i < model.nYears+1; i++) {
				if (i<model.nYears) {
					for (var j=0; j < 12; j++) {
						root.addMonth();
					}
				}
				root.addYear();
			}
		}
	},
	View.prototype.addMonth = function() {
		var root = this;
		if (root.monthId%12 != 0 && root.showMonths) {
			if (root.monthId%root.showEveryNthMonth == 0) {
				$('.timeline-months').append('<div class="timeline-month timeline-dateblock" id="timeline-month-'+root.monthId+'">'+model.allMonths[root.monthId%12]+'</div>');
			} else {
				$('.timeline-months').append('<div class="timeline-month timeline-dateblock" id="timeline-month-'+root.monthId+'"></div>');
			}
			$('#timeline-month-'+root.monthId).css({ "left" : methods.lim(root.monthPosition, 0, root.width) });
		}
		root.monthPosition += root.monthWidth;
		root.monthId++;
	},
	View.prototype.addYear = function() {
		var root = this;
		if (root.showYears && root.yearId%root.showEveryNthYear == 0) {
			var year = new Array();
			
			year = model.startYear+root.yearId+'';

			if (year < 0) {
				year = year.replace('-', '') + " BC";
			}
			
			$('.timeline-years').append('<div class="timeline-year timeline-dateblock" id="timeline-year-'+root.yearId+'">'+year+'</div>')
			$('#timeline-year-'+root.yearId).css({ "left" : methods.lim(root.yearPosition, 0, root.width-1) });
		}
		root.yearPosition += root.yearWidth;
		root.yearId++;
	},
	View.prototype.positionEvents = function() {
		
		// CALCULATE EACH ITEM'S POSITION IN MODEL.EVENTS
		for (var i=0; i<model.events.length; i++) {
			model.events[i].position = view.getPosition(i);
		}
		
		// CLONE ARRAY
		var temp = new Array();
		for (i=0; i<model.events.length; i++) {
			temp[i] = model.events[i];
		}
		
		model.events.sort(sortFunc);
		
		function sortFunc(a, b) {
			return a.position - b.position;
		}
				
		// CORRECT POSITIONS
		var lastresult = 0, delta=0, newresult;

		for (i=1; i<model.events.length; i++) {
			if (model.events[i].position <= model.events[i-1].position) {
				model.events[i].position = model.events[i-1].position;
			}
			if (Math.abs(model.events[i].position - model.events[i-1].position) < view.eventNodeWidth) {
				delta = Math.abs(model.events[i].position - model.events[i-1].position);
				model.events[i].position = model.events[i-1].position + view.eventNodeWidth/1.5;
			}
		}
		
		if (model.events[model.events.length-1].position > root.width) {
			model.events[model.events.length-1].position = root.width;
			
			for (var i=model.events.length-1; i>0; i--) {
				if (model.events[i-1].position >= model.events[i].position) {
					model.events[i-1].position = model.events[i].position;
				}
				if (Math.abs(model.events[i].position - model.events[i-1].position) < view.eventNodeWidth) {
					delta = Math.abs(model.events[i].position - model.events[i-1].position)
					model.events[i-1].position = model.events[i-1].position - view.eventNodeWidth/1.5;
				}
				
				model.events[i].position = model.events[i].position - view.eventNodeWidth/2;
			}
		}
		
		root.drawEvents();
		root.setData();
		
		// APPLY POSITIONS & CORRECT Z-INDEX
		for (i=0; i<model.events.length; i++) {
			$('#timeline-event-'+model.events[i].id).css({ "left" : model.events[i].position, "z-index" : i });
		}
	},
	View.prototype.getPosition = function(i) {
		// get event node width
		root = this;
		var left = 0;
		left = (root.monthWidth * (model.events[i].month-1)) + 
		(root.yearWidth * (model.events[i].year - model.startYear)) + 
		root.dayWidth * (model.events[i].date-1) - view.eventNodeWidth/2-1;
		root.eventPositions[i] = left;
		return left;
	}
	View.prototype.showEvent = function(id) {
		var root = this;
		var timeline = false;
		var target = $('#'+id);
		var targetContents = target.find('.timeline-event-contents');
		
		if (root.aboveTimeline(target)) {
			target.addClass('timeline-above');
		}
		
		target.addClass('timeline-hover').find('.timeline-event-contents').fadeIn(200);
		target.find('.timeline-event-arrow').fadeIn(200);
		
		target.data({ "offset" : targetContents.offset().left, "showed" : true });
		
		if (this.needMargin(target)) {
			this.correctMargin(target);
		}
	},
	View.prototype.hideEvent = function(id) {
		var root = this;
		var target = $('#'+id);
		
		target.removeClass('timeline-hover').find('.timeline-event-contents').fadeOut(200, function() {
			$(this).closest('.timeline-above').removeClass('timeline-above');
			target.find('.timeline-event-contents').css({
				"margin-left" : target.data('margin')
			});
		});
		target.removeClass('timeline-hover').find('.timeline-event-arrow').fadeOut(200);
		
		target.data({ "showed" : false });
	},
	View.prototype.selectEvent = function(target) {
		var root = this;
		var targetContents = target.find('.timeline-event-contents');
		target.removeClass('timeline-above').addClass('timeline-selected').removeClass('timeline-hover');
		root.selectedEvent = target;
		
		if (!target.data('showed')) {
			root.showEvent(target.attr('id'));
		}
		
		if (this.needMargin(target, 400)) {
			var delta = target.data('offset') + 400 - ($('.timeline-wrap').offset().left + $('.timeline-wrap').outerWidth());
			var margin = -delta+target.data('margin');
		} else {
			var margin = target.data('margin');
		}
		
		target.find('.timeline-event-contents').fadeIn(200).animate({
			"width" : 376,
			"margin-left" : margin
		}, 100, function() {
			target.find('.timeline-event-content').slideDown(200);
			target.find('.timeline-event-link').fadeIn(200);							
		});
	},
	View.prototype.deselectEvent = function() {
		var root = this;
		var target = $('.timeline-wrap').find('.timeline-selected');
		root.hideEvent(target.attr('id'));
		target.removeClass('timeline-selected');
		target.find('.timeline-event-content').slideUp(200, function() {
			target.find('.timeline-event-contents').css({
				"width" : target.data('smallWidth'),
				"margin-left" : target.data('margin')
			});
		});
		target.find('.timeline-event-link').fadeOut(200);
		root.selectedEvent = 0;
	},
	View.prototype.aboveTimeline = function(target) {
		var root = this;

		if (root.selectedEvent != 0 && !target.hasClass('timeline-selected')) {
			return true;
		}
		return false;
	},
	View.prototype.needMargin = function(target, width) {
		if (width === undefined) { width = target.find('.timeline-event-contents').outerWidth(); } else { width = 400; }

		if (target.data('offset') + width > $('.timeline-wrap').offset().left + $('.timeline-wrap').outerWidth()) {
			
			return true;
		}
		return false;
	}
	View.prototype.correctMargin = function(target, width, animated) {
		if (width === undefined) { width = target.find('.timeline-event-contents').outerWidth(); } else { width = 400; }
		var delta = target.data('offset') + width - ($('.timeline-wrap').offset().left + $('.timeline-wrap').outerWidth());
		
		if (animated) {
			target.find('.timeline-event-contents').animate({
				"margin-left" : -delta+target.data('margin')
			}, 100);
		} else {
			target.find('.timeline-event-contents').css({
				"margin-left" : -delta+target.data('margin')
			});
		}		
	}
	
	Controller.prototype.setDOMEvents = function() {
		var root = this;
		$('.timeline-event-node').on('click', function() {
			if (view.selectedEvent == 0) {
				view.selectEvent($(this).parent());
			} else {
				if ($(this).parent().hasClass('timeline-selected')) {
					view.deselectEvent($(this).parent());
				} else {
					view.deselectEvent($('.timeline-selected'));
					view.selectEvent($(this).parent());
				}								
			}
		});
		$('.timeline-event-node').on('mouseover', function() {
			if ($('.timeline-selected').length == 0) {
				view.hideEvent($('.timeline-hover').attr('id'));
			}
			if (!$(this).closest('.timeline-event').hasClass('timeline-hover')) {
				view.showEvent($(this).closest('.timeline-event').attr('id'));
			}						
		});
		$('.timeline-event-node').on('mouseout', function() {
			if (!$(this).closest('.timeline-event').hasClass('timeline-selected')) {
				view.hideEvent($(this).closest('.timeline-event').attr('id'));
			}
		});
		$(document).on('click', function(e) {
			if (root.selectedEvent != 0 && $(e.target).closest('.timeline-wrap').length == 0) {
				view.deselectEvent();
			}
		});
	}
	
 	$.fn.extend({ 
		timelinexml : function(options) {
			var settings = $.extend({
				src : '',
				showLatest : false,
				selectLatest : false,
				eventTagName : "event",
				dateTagName : "date",
				titleTagName : "title",
				thumbTagName : "thumb",
				contentTagName : "content",
				linkTagName : "link",
				mode : 'xml',
				htmlEventClassName : "timeline-event",
				htmlDateClassName : "timeline-date",
				htmlTitleClassName : "timeline-title",
				htmlContentClassName : "timeline-content",
				htmlLinkClassName : "timeline-link",
				htmlThumbClassName : "timeline-thumb"
			}, options);
			
			model = new Model(settings);
			view = new View();
			controller = new Controller();
			
			return this.each(function() {
				var target = $(this);
				
				if (settings.src.length != 0) {
					settings.mode = 'html';
					model.getHTMLContent();
					model.parseRawData();
					view.init({ target : target, mode : 'html' });
					view.positionEvents();
					view.addDates();
					controller.setDOMEvents();
					if (settings.showLatest || settings.selectLatest) {
						view.showEvent("timeline-event-0");
						if (settings.selectLatest) {
							setTimeout(function() {
								view.selectEvent($('#timeline-event-0'));
							}, 500);							
						}
					}
				} else {
					model.loadXML({ callback : function() {
						model.getXMLContent();
						model.parseRawData();
						view.init({ target : target });
						view.positionEvents();
						view.addDates();
						controller.setDOMEvents();
						if (settings.showLatest || settings.selectLatest) {
							view.showEvent("timeline-event-0");
							if (settings.selectLatest) {
								setTimeout(function() {
									view.selectEvent($('#timeline-event-0'));
								}, 500);							
							}
						}				
					} });
				}
    		});
    	}
	});
})(jQuery);