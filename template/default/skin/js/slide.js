$(function(){
	/*首页幻灯片效果 start*/
	$(".index-slide").slide({ titCell:".num li" , mainCell:".slidepic", autoPlay:true, delayTime:700 });
	/*首页幻灯片效果 end*/
   
	//首页Tab标签卡滑门切换
    var o, d;
    $(".j_fruitMenu li").hover(function() {
        var e = $(this);
        o = setTimeout(function() {
            e.addClass("sel").siblings("li").removeClass("sel"),
            n = e.index(),
            $(".j_fruitGoods").eq(n).css({
                display: "block"
            }).siblings(".j_fruitGoods").css({
                display: "none"
            })
        },
        200)
    });
			
})

