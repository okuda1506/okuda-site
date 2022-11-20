$('.burger-btn').on('click',function(){
  $('.burger-btn').toggleClass('close');
  $('.nav-wrapper').toggleClass('slide-in');
  $('body').toggleClass('noscroll');
});

// メニュー押下⇒.nav-wrapperなくす
$('.nav-item').on('click',function(){
  $('.burger-btn').toggleClass('close');
  $('.nav-wrapper').toggleClass('slide-in');
  $('body').toggleClass('noscroll');
});

//フェードインアニメーション
$(function(){
	$(window).scroll(function (){
		$('.fade-in').each(function(){
			var elemPos = $(this).offset().top;
			var scroll = $(window).scrollTop();
			var windowHeight = $(window).height();
			if (scroll > elemPos - windowHeight + 200){
				$(this).addClass('scroll-in');
			}
		});
	});
});