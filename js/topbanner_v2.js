var loadTop = document.getElementById('JS_topBnChk');
if(loadTop !== null){
    SP$('.SP_topBanner').css('display','block');
    var target = SP$('#JS_topBnChk li');

    SP$.each(target, function(i, e){
		// console.log(i);
        var li_target = SP$(e);
        var targetId = li_target.attr('rel');

        /* 이미지 경로 */
        var targetImgSrc = li_target.find('img').attr('src');
        /* 이미지 높이값 */
        var targetImgHeight = li_target.find('img').attr('height');
        /* 이미지 링크값 */
        var targetLink = li_target.find('a').attr('href');

        var html = '';
        html += '<div class="SP_top_contents SP_topBn0'+i+' JS_topbnChk" id="topbn0'+i+'" style="height:'+ targetImgHeight +'px; background-image:url('+ targetImgSrc +');">';
        html += '<div class="SP_top_contents_inr">';
        html += '<a href="'+ targetLink +'">';
        html += '</a>';
        html += '<div class="SP_close_btn_wrap">';
        html += '<a href="javascript:void(0);" class="SP_topbn_close_btn SP_js_close_btn SP_js_today_btn"><span class="SP_cm_icon SP_topbn_close"></span></a>';
        html += '</div>';
		/*
        html += '<div class="SP_today_wrap">';
		html += '<input type="checkbox" name="" value="" /><label class="SP_today_btn SP_js_today_btn">오늘 하루 보지않기</label>';
		html += '</div>';
		*/
        html += '</div>';
        html += '</div>';


        SP$('.SP_topBn_inr').append(html);
    })
}

function setCookieMobile(name, value, expiredays){
    var todayDate = new Date();
    todayDate.setDate( todayDate.getDate() + expiredays );
    document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}

function getCookieMobile(){
    var cookiedata = document.cookie;
    if(cookiedata.indexOf("todayCookie=done") < 0){
		SP$(".SP_topBanner").show();
    }else{
        SP$(".SP_topBanner").hide();
    }
}

getCookieMobile();

SP$(window).on('load', function(){
	SP$('.SP_js_close_btn').on('click', function(){
		SP$(this).children().css({
            'height':'0',
        }).parents('.SP_top_contents').css({
            'height':'1px',
			'transition':'1s',
			'transform':'translateY(-2px)'
		})
		SP$('.SP_topBanner').slideUp(500);
		
		// 체크박스에 표시 후 닫을 때 쿠키 심기 _Vicky 추가
		// if(SP$('.SP_today_wrap input[type="checkbox"]').prop('checked')){setCookieMobile( "todayCookie", "done" , 1);}
	});
})

/* 오늘 하루 열지 않기 버튼 활성화 체크 */
SP$(document).ready(function(){
    SP$(".SP_js_today_btn").on('click', function () {
		setCookieMobile( "todayCookie", "done" , 1);
		SP$(".SP_topBanner").css({
			'transform':'translateY(-2px)'
		}).find('.SP_top_contents').css({
			'height':'1px',
			'transition':'1s',
			'transform':'translateY(-2px)'
		});;
		SP$('.SP_js_close_btn').css('display','none');
		/*
        if(inputChk.prop('checked')){
            setCookieMobile( "todayCookie", "done" , 1);
            SP$(".SP_topBanner").css({
    			'transform':'translateY(-2px)'
    		}).find('.SP_top_contents').css({
    			'height':'1px',
    			'transition':'1s',
    			'transform':'translateY(-2px)'
    		});;
            SP$('.SP_js_close_btn').css('display','none');
        }else{
            alert('체크박스를 체크해주세요.')
        }*/
    });
});
