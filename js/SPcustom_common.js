/*================================================================================

   ▄███▄  ▄██ ██▄   ▄██▄    ▄████▄  ▀▀██▀▀     ▄█▀▀█▄  ▄████▄    ▄████▄      ▄████▄
   ███▄▄  ███████  ▐█ ▐█   ▐██  ██    ██       ██  ██  ██  ▀▀       ▄██      ██  ██
     ▀██▌ ██▐█▌██  ██████  ▐█████▄    ██       ███▀▀   ██  ▄▄    ██▀▀▀▀      ██  ██
   ▀███▀  ██ █ ██  ██  ██  ▐██ ▐██▌   ██       ██      ▀████▀    ▀████▀  ██  ▀████▀

=================================================================================*/


/* =============================== ↓ 기본 호출 ↓ =============================== */
if (window.console != undefined) {
    setTimeout(console.log.bind(console, "%c SMART PC / COPYRIGHT ⓒUNEEDCOMMS ALL RIGHTS RESERVED.", "font:11px Arial;color:#549bf7;font-weight:bold"), 0);
}
/* =============================== ↓ 스크립트 호출 ↓ =============================== */

var prdListMainLength = SP$('.SP_prdCustomList').length; // 카테고리진열
var timesaleListLength = SP$('.timeSaleChk').length; // 타임세일
var instagramLength = SP$('.instagram_section').length; // 인스타그램
var listAlignSortLength = SP$('.SP_listAlignSort_wrap').length; // 리스트 정렬방식
var detailPagePrdOpt = SP$('.SP_detailPrdOpt_wrap').length; // 디테일 페이지 상품리스트

/* 상단배너 */
document.write("<script src='/smartpc/include/topbanner/topbanner_v2/_js/topbanner_v2.js'></script>");

/* 디폴트 상품 리스트 */
document.write("<script src='/smartpc/include/prdList/_js/defaultPrdList.js'></script>");
document.write("<script src='/smartpc/include/prdList/_js/defaultPrdListStart.js'></script>");

;(function(){
	switch(SMARTPC_GLOBAL_OBJECT.page){
		case 'main':
			document.write("<script src='/smartpc/_js/smartpc_main.js'></script>");
		
			/* TIME SALE LIST */
			if(timesaleListLength) {
				document.write("<script src='/smartpc/include/prdList/prdMainList/timesale_progressbar/_js/timesale_progressbar.js'></script>");
			}

			/* CUSTROM PRODUCT LIST */
			if(prdListMainLength > 0 ){
				document.write("<script src='/smartpc/include/prdList/_js/CustromPrdList.js'></script>");
				document.write("<script src='/smartpc/include/prdList/_js/CustromPrdListStart.js'></script>");
			}

			/* INSTAGRAM LIST */
			if(instagramLength > 0){
				document.write("<script src='/smartpc/include/instagram/_js/instagram.js'></script>");
				document.write("<script src='/smartpc/include/instagram/_js/instaStart.js'></script>");
			}
		break;

		case 'category':
			document.write("<script src='/smartpc/_js/smartpc_category.js'></script>");
			/* 카테고리페이지 상품정렬 */
			if(listAlignSortLength > 0 ){
				document.write("<script src='/smartpc/include/listAlignSort/_js/listAlignSort.js'></script>");
			}
		break;

		case 'detail':
			/* 디테일 관심상품 슬라이드 */
			document.write("<script src='/smartpc/_js/smartpc_detail.js'></script>");
		break;

		case 'mypage':
			document.write("<script src='/smartpc/_js/smartpc_mypage.js'></script>");
		break;
		
		case 'order':
			/* 디테일 관심상품 슬라이드 */
			document.write("<script src='/smartpc/_js/smartpc_order_1.js'></script>");
		break;
	}
})();


/* =============================== //스크립트 호출 =============================== */

SP$(document).ready(function(){
    SP$('.SP_js_gnb_toggle').on('click',function(){
        SP$('body, html ').addClass('SP_overflow_hd');
        SP$('.SP_shopping_info_wrap').show();
    });
    SP$('.SP_js_lypop_close_btn').on('click', function(){
        SP$('body, html ').removeClass('SP_overflow_hd');
        SP$('.SP_shopping_info_wrap').hide();
    });

	// 공통 영역 eng_font 적용 + 하단 after wid 값 조절
	if(SP$('.SP_subContHeader .SP_subTitle').length){
		SP$('.SP_subContHeader .SP_subTitle > span').addClass('eng_font');
		SP$('.SP_subContHeader .SP_subTitle').addClass('eng_font');
		// 카테고리 중분류 명 변경
		var SPcateName = SP$('.SP_subContHeader .SP_subTitle').text();
		( SPcateName.indexOf('shop') != -1 ) ? SP$('.SP_subContHeader .SP_subTitle .name_2').fadeIn() : SP$('.SP_menuCategory li a').css({'padding':'12px 100px'}).end().find('.SP_subContHeader .SP_subTitle .name_1').fadeIn();
        /* 2021-04-07 한주연 오호라 x 네일샵 카테고리 한줄로 나오도록 수정 Start */
        if(SPcateName.indexOf('오호라 x 네일샵') != -1){
            SP$('.SP_menuCategory li a').css('padding','12px 35px');   
        }
        /* 2021-04-07 한주연 오호라 x 네일샵 카테고리 한줄로 나오도록 수정 End */
        /* 2021-02-24 한주연 기획전 카테고리 영역 수정 Start */
        if(SPcateName.indexOf('기획전') != -1){
            SP$('.SP_menuCategory li a').css('padding','12px 35px');
        }
        /* 2021-02-24 한주연 기획전 카테고리 영역 수정 End */
	}

	// 공통 영역 eng_font 적용 + 하단 after wid 값 조절
	if(SP$('.SP_subLoginHeader .SP_subTitle').length){SP$('.SP_subLoginHeader .SP_subTitle span').addClass('eng_font');}

	if(SP$('.SP_subSection').length){
		var SecTit = SP$('.SP_subSection .SP_subTitle').text();
		SP$('.SP_subSection').removeClass('Nunito_Sans_font');
		SP$('.SP_subSection .SP_subTitle').html('<span class="eng_font">'+ SecTit +'</span>')
		// if(SecTit == '') {SP$('.SP_subSection').hide();}
	} 
	if(SP$("#category_no").length) {
			SP$("#category_no option[value=120]").attr("selected", "selected");
			SP$("#category_no option[value=121]").attr("selected", "selected");
			SP$("#category_no option[value=180]").attr("selected", "selected");
			SP$("#category_no option[value=44]").attr("selected", "selected");		
	}
})



/* =============================== 상품 HOVER 장바구니 / 카트아이콘 =============================== */
var thumbHover = SP$('.SP_thumbHover_wrap').length;
var bThumbHoverChk = Boolean(thumbHover);
if(bThumbHoverChk){
    SP$('.JS_SP_hoverDetailPage').on('click',function(){
        var frameUrl = SP$(this).data('detail');
        var $target = SP$('.SP_hoverPop_iframe')
        $target.children().remove();
        var _html = '<iframe src="'+frameUrl+'"></iframe>';
        $target.append(_html)
        SP$('.SP_hoverPopup').css('display','block');
    })
    SP$('.SP_JS_detailPageClose_btn').on('click',function(){
        SP$(this).next().children().remove();
        SP$(this).parents('.SP_hoverPopup').css('display','none');
    })
}