$(document).ready(function(){
  
});

$("#nav-toggle").click(function(){
  $("#header-menu").addClass("menu-slide-l");
  $(".left-nav").css("z-index","13");
  $(".navbar-overlay").css("z-index","12");
  $(".navbar-overlay").show();
});

$(".menu-close").click(function(){
  $("#header-menu").removeClass("menu-slide-l");
  $(".navbar-overlay").hide();
});

$("#nav-toggle-r").click(function(){
  $("#member-menu").addClass("menu-slide-r");
  $(".right-nav").css("z-index","13");
  $(".navbar-overlay").css("z-index","12");
  $(".navbar-overlay").show();
});

$(".menu-close").click(function(){
  $("#member-menu").removeClass("menu-slide-r");
  $(".left-nav").css("z-index","11");
  $(".right-nav").css("z-index","11");
  $(".navbar-overlay").hide();
});

$(".navbar-overlay").click(function(){
  $("#header-menu").removeClass("menu-slide-l");
  $(".navbar-overlay").hide();
  $("#member-menu").removeClass("menu-slide-r");
  $(".navbar-overlay").hide();
  $(".left-nav").css("z-index","11");
  $(".right-nav").css("z-index","11");
});