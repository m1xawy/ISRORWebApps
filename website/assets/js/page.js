
$(document).ready(function(){
	tablepagination();
});

$(".gototop").click(function() {
	$("html, body").animate({ scrollTop: 0 }, "slow");
});

$("#re-profile").click(function(e){
	$(this).hide();
	$(this).closest('#user-info').find('span').hide();
	$(this).closest('#user-info').find('input[type=text]').show();
	$('.gender-select').show();
	$('#save-userinfo').show();
	$('alert-danger').html('');
});

$(".charge-box").mouseover(function(){
	$(this).addClass('chargebox-hover');
});
$(".charge-box").mouseout(function(){
	$(this).removeClass('chargebox-hover');
});

$(".changept-box").mouseover(function(){
	$(this).addClass('changeptbox-hover');
});
$(".changept-box").mouseout(function(){
	$(this).removeClass('changeptbox-hover');
});

$("#next-step").click(function(e){
	$(this).hide();
	$('#twopass-part02').show();
});

$('.faq-toggle').click(function () {
	var FAQtoggleID = $(this).data('toggle');
	$('.faq-content[data-toggle=' + FAQtoggleID + ']').slideToggle();
});

$('.td-click').each(function(){
	var $tr = $(this);
		$tr.on('click', function(){
			window.open($tr.attr('data-href'), $tr.attr('data-target'));
		});
});

$('.charge-area').each(function(){
	var $defaultLi = $('.charge-way li');
	$($defaultLi.find('a').attr('href')).siblings().hide();
	$(".charge-way li").click(function(e){

		var $this = $(this),
				_clickTab = $this.find('a').attr('href');

		$this.addClass('active').siblings('.active').removeClass('active');

  		$(".charge-func li").removeClass('active');
  		$(_clickTab).stop(false, true).slideDown().siblings().hide();

  		/*return false;*/
  	}).find('a').focus(function(){
			this.blur();
	});
});

$('.charge-area').each(function(){
	var $defaultLi = $('.charge-func li');
	$($defaultLi.find('a').attr('href')).siblings().hide();
	$(".charge-func li").click(function(e){
		var $this = $(this),
				_clickTab = $this.find('a').attr('href');

		$this.addClass('active').siblings('.active').removeClass('active');

  		$('#charge-p3').slideDown();
  		/*$('#charge-p3').hide();*/
  		$(".charge-deno li").removeClass('active');
  		$(_clickTab).stop(false, true).slideDown().siblings().hide();

  		/*return false;*/
  	}).find('a').focus(function(){
			this.blur();
	});
	/*$(".charge-deno li").click(function(e){
		var $this = $(this),
				_clickTab = $this.find('a').attr('href');

		$this.addClass('active').siblings('.active').removeClass('active');

  		$('#charge-p4').slideDown();
  		$(_clickTab).stop(false, true).slideDown().siblings().hide();

  	}).find('a').focus(function(){
			this.blur();
	});*/
});

$('.changept-area').each(function(){
	var $defaultLi = $('.changept-game li');
	$('#Digeamptchange-func').siblings().hide();
	$(".changept-game li").click(function(e){

		var $this = $(this),
				_clickTab = '#Digeamptchange-func';

		$this.addClass('active').siblings('.active').removeClass('active');

  		$('#changept-p2').slideDown();
  		$(_clickTab).stop(false, true).slideDown().siblings().hide();

  		//return false;
  	}).find('a').focus(function(){
			this.blur();
	});
});

/*$(".charge-deno li").click(function(e){
	$(this).addClass('active').siblings('.active').removeClass('active');
});*/

function tablepagination(){
	$('.page-table-03').each(function () {
		var width = $(window).width();
		var $table = $(this);
		var itemsPerPage = 5;
		var currentPage = 0;
		
		if(window.location.pathname == '/member/coupon') {
			itemsPerPage = 10;
		}

		if( width < 768) {
          itemsPerPage = 3;
        }

		var pages = Math.ceil($table.find("tr:not(:has(th))").length / itemsPerPage);

		$table.bind('repaginate', function () {
		if (pages > 1) {
			var pager;
			if ($table.next().hasClass("pager"))
			  pager = $table.next().empty();  
			else  
			  pager = $('<div class="pager" style="direction:ltr; " align="center"></div>');

			$('<span class="pg-goto"></span>').text(' « ').bind('click', function () {
			  currentPage = 0;
			  $table.trigger('repaginate');
			}).appendTo(pager);

			$('<span class="pg-goto"> < </span>').bind('click', function () {
			  if (currentPage > 0)
			    currentPage--;
			  $table.trigger('repaginate');
			}).appendTo(pager);

			var startPager = 0;
			var endPager = 0;
			if ( width > 768){
				startPager = currentPage > 2 ? currentPage - 2 : 0;
				endPager = startPager > 0 ? currentPage + 3 : 5;
				if (endPager > pages) {
				  endPager = pages;
				  startPager = pages - 5;    if (startPager < 0)
				    startPager = 0;
				}
			}else if (width < 768){
				startPager = currentPage > 2 ? currentPage - 2 : 0;
				endPager = startPager > 0 ? currentPage + 3 : 2;
				if (endPager > pages) {
				  endPager = pages;
				  startPager = pages - 5;    if (startPager < 0)
				    startPager = 0;
				}
			}
			for (var page = startPager; page < endPager; page++) {
			  $('<span id="pg' + page + '" class="' + (page == currentPage ? 'pg-selected' : 'pg-normal') + '"></span>').text(page + 1).bind('click', {
			      newPage: page
			    }, function (event) {
			      currentPage = event.data['newPage'];
			      $table.trigger('repaginate');
			  }).appendTo(pager);
			}

			$('<span class="pg-goto"> > </span>').bind('click', function () {
			  if (currentPage < pages - 1)
			  currentPage++;
			  $table.trigger('repaginate');
			}).appendTo(pager);
			$('<span class="pg-goto"> » </span>').bind('click', function () {
			  currentPage = pages - 1;
			  $table.trigger('repaginate');
			}).appendTo(pager);

			if (!$table.next().hasClass("pager"))
			  pager.insertAfter($table);
				
		}

		$table.find(
			'tbody tr:not(:has(th))').hide().slice(currentPage * itemsPerPage, (currentPage + 1) * itemsPerPage).show();
		});

		$table.trigger('repaginate');
	});
}


function record_del(index){
	location.href = '/cs/delete/'+index;
}
