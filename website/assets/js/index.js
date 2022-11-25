
/*--------------------------------Parallax--------------------------------*/

var targetElements = document.getElementsByClassName('scenes');
// or, if you insist on using jQuery
var targetElements = $('.scenes').get();

var scenes = [];
for (var i = 0; i < targetElements.length; i++) {
  scenes.push(new Parallax(targetElements[i],{
  	limitY : false,
  	scalarY : 0,
  	scalarX : 8,
  	originX : 0,
  }));
}

/*--------------------------------大bn--------------------------------*/
$.fn.randomize = function (selector) {
    var $elems = selector ? $(this).find(selector) : $(this).children(),
        $parents = $elems.parent();

    $parents.each(function () {
        $(this).children(selector).sort(function (childA, childB) {
            // * Prevent last slide from being reordered
            if($(childB).index() !== $(this).children(selector).length - 1) {
                return Math.round(Math.random()) - 0.5;
            }
        }.bind(this)).detach().appendTo(this);
    });

    return this;
};

$('.back-banner').randomize().slick({
	lazyLoad: 'ondemand',
	slidesToShow: 1,
	autoplay: false,
	fade: true,
	draggable:true,
	arrows: true,
	infinite: true,
});

$('.news-banner-list').slick({
	slidesToShow: 3,
	slidesToScroll: 1,
	draggable:false,
	focusOnSelect: true,
	asNavFor: '.news-banner',
	vertical: true,
	centerMode: true,
  	focusOnSelect: true,
  	centerPadding: "64px",
  	infinite:true,
	prevArrow: $('.news-banner-prev'),
  	nextArrow: $('.news-banner-next')
});

$('.news-banner').slick({
	slidesToShow: 1,
	slidesToScroll: 1,
	draggable:false,
	arrows: false,
	fade: true,
	infinite:true,
	autoplay: true,
	asNavFor: '.news-banner-list'
});


/*--------------------------------電腦版遊戲區塊--------------------------------*/
$('.games-area-pc').slick({
	slidesToShow: 3,
	slidesToScroll: 1,
	centerPadding: '100px',
	arrows: true,
	dots:false,
	centerMode: true,
	infinite: true,
	responsive: [
	    {
	      breakpoint: 1025,
	      settings: {
	        centerPadding: '60px',
	      }
	    }
  	]
});

$('.games-area-mobile').slick({
	slidesToShow: 3,
	slidesToScroll: 1,
	centerPadding: '100px',
	arrows: true,
	dots:false,
	centerMode: true,
	infinite: true,
	responsive: [
	    {
	      breakpoint: 1025,
	      settings: {
	        centerPadding: '60px',
	      }
	    }
  	]
});


/*--------------------------------手機版遊戲區塊--------------------------------*/
$('.games-area-m').slick({
	infinite: true,
	slidesToShow: 3,
	slidesToScroll: 1,
	arrows: false,
	dots:false,
	centerMode: true,
	variableWidth: true
});


$(".dots-p").click(function(e){
	$(this).hide();
	$(this).closest('.game-box').find('.game-logo').hide();
	$(this).closest('.game-box').find('.gameinfo-box').show();
	$(this).closest('.game-box').find('.dots-close-p').show();
});

$(".dots-close-p").click(function(e){
	$(this).closest('.game-box').find('.game-logo').show();
	$(this).closest('.game-box').find('.gameinfo-box').hide();
	$(this).closest('.game-box').find('.dots-p').show();
});

$(".dots-m").click(function(e){
	$(this).hide();
	$(this).closest('.game-box-m').find('.game-cover-m').show();
	$(this).closest('.game-box-m').find('.dots-close-m').show();
});

$(".dots-close-m").click(function(e){
	$(this).hide();
	$(this).closest('.game-box-m').find('.game-cover-m').hide();
	$(this).closest('.game-box-m').find('.dots-m').show();
});


window.onresize = function(){
    document.body.height = window.innerHeight;
}
window.onresize(); // called to initially set the height.