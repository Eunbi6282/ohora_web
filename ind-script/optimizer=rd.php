$(document).ready(function(){
    var scrollChk_main = false;

    var pageSt = $(window).scrollTop();
    if(pageSt > 30){
        scrollChk_main = true;
        mainPrdCallFunc();
    }

    var mainPageScroll = function(){
        var wT = $(window).scrollTop();
        if(wT > 30 && scrollChk_main == false){
            scrollChk_main = true;
            mainPrdCallFunc();
        }
    }
    document.addEventListener('scroll',  function() {
        if(scrollChk_main){
            document.removeEventListener("scroll", mainPageScroll);
        }else{
            mainPageScroll();
        }
    });

    //메인 상품 영역 호출(동적호출)
    function mainPrdCallFunc(){
        $('.muse_prd_wrap > ul').addClass('swiper-wrapper');
        $('.muse_banner_wrap .SP_prdList_ul[data-list-sort="3"] .SP_prdList_item .rv_icon').removeClass('displaynone');

        //뮤즈마다 상품 호출 및 스와이프
        $('.muse_banner').each(function(){
            var el = $(this);
            var cateNum = el.find('.common_list_box ').data('cate');
            jQuery1_11_2.ajax({
                url: '/exec/front/Product/ApiProductNormal',
                type: 'GET',
                dataType: 'json',
                data: {
                    cate_no: cateNum,
                    sort_method: 0,
                    bInitMore: "F",
                    page: 1,
                    count: '10'
                },
                success: function (data) {
                    var prdData = data["rtn_data"]["data"];
                    if(prdData != null){
                        if (prdData.length) {
                            for (let i = 0; i < prdData.length; i++) {
                                var elemNo = prdDataCallFunc(prdData[i]).prdNo;
                                if(el.find('[data-prd-no="'+ elemNo +'"]').length == 0){
                                    var elem = prdDataCallFunc(prdData[i]).item;
                                    var test2 = document.createElement('li');
                                    test2.classList.add('xans-record-');
                                    test2.classList.add('append_item');
                                    test2.classList.add('swiper-slide');
                                    test2.innerHTML = elem;
                                    el.find('.common_items').append(test2);
                                }
                            }
                        }
                    }
                }
            }).done(function (data, textStatus, xhr) {

                setTimeout(function(){
                    var banner_index = el.index();
                    el.find('.thumb img').addClass('swiper-lazy');
                    // banner_index = '.muse_prd_wrap' + banner_index;

                    window['MusePrdSwiper' + banner_index] = new Swiper('.muse_prd_wrap' + banner_index , {
                        slidesPerView: 3,
                        slidesPerGroup: 3,
                        observer: true,
                        observeParents: true,
                        spaceBetween:40,
                        navigation: {
                            nextEl: '.muse_wrap .swiper-button-next',
                            prevEl: '.muse_wrap .swiper-button-prev',
                        },
                        scrollbar: {
                            el: '.muse_wrap .swiper-scrollbar',
                        },
                        preloadImages: false,
                        // Enable lazy loading
                        lazy: {
                            loadPrevNext: true,
                            loadPrevNextAmount: 1,
                            loadOnTransitionStart: true
                        },
                    });

                },300);

            });
        });

        //주간 베스트 ----------------------------------------------------------------------------------------------------------------
        $('.cate_tab > span').each(function(){
            var cateTabFind = $(this).data('tab');
            var cateFind = $(this).data('cate');

            jQuery1_11_2.ajax({
                url: '/exec/front/Product/ApiProductNormal',
                type: 'GET',
                dataType: 'json',
                //async:false,
                data: {
                    cate_no: cateFind,
                    sort_method: 0, //6에서 0으로 변경
                    bInitMore: "F",
                    page: 1,
                    count: '10'
                },
                success: function (data) {
                    var prdData = data["rtn_data"]["data"];
                    if(prdData != null){
                        if (prdData.length) {
                            for (let i = 0; i < prdData.length; i++) {
                                var elemNo = prdDataCallFunc(prdData[i]).prdNo;
                                var el = $('.best_section [data-cate="'+ cateFind +'"] .common_items');
                                var elem = prdDataCallFunc(prdData[i]).item;
                                var test2 = document.createElement('li');
                                test2.classList.add('xans-record-');
                                test2.classList.add('append_item');
                                test2.classList.add('swiper-slide');
                                test2.innerHTML = elem;
                                el.append(test2);
                            }
                        }
                    }
                }
            }).done(function (data, textStatus, xhr) {

                setTimeout(function(){
                    
                    $('.best_section [data-cate="'+ cateFind +'"] .common_items > li').each(function(){
                        var el = $(this);
                        prdItemInfoFunc(el);
                        el.addClass('swiper-slide');
                        var index = $(this).index() + 1;
                        el.find('.SMS_base_img').prepend('<span class="main_best_icon" style="position: absolute; top: 0; left: 0; z-index: 2; color: #fff; padding: 3px 12px; background: #000; font-size: 13px;">'+ index +'위</span>');
                    });

                    //베스트 영역 스와이프
                    $('.best_section .common_list_box').each(function(){
                        
                        if($(this).find('.swiper-button-next').length == 0){
                        	$(this).append('<div class="swiper-button-next swiper-button"></div><div class="swiper-button-prev swiper-button"></div>');
                        }
                        $(this).find('.common_items  > li .thumb img').addClass('swiper-lazy');
                        var best_section_index = $(this).data('tab');
                        var bsel = '.best_section .common_list_box[data-tab ="' + best_section_index +'"] > .swiper-container';

                        window['bestPrdSwiper' + best_section_index] = new Swiper(bsel, {
                            slidesPerView: 3,
                            slidesPerGroup: 3,
                            observer: true,
                            observeParents: true,
                            spaceBetween:40,
                            navigation: {
                                nextEl: '.best_section .common_list_box[data-tab ="' + best_section_index +'"] .swiper-button-next',
                                prevEl: '.best_section .common_list_box[data-tab ="' + best_section_index +'"] .swiper-button-prev',
                            },
                            scrollbar: {
                                el: '.best_section .common_list_box[data-tab ="' + best_section_index +'"] .swiper-scrollbar',
                            },
                            lazy: {
                                loadPrevNext: true,
                                loadPrevNextAmount: 1,
                                loadOnTransitionStart: true
                            },
                        });
                        
                        
                    });
                },300);
            });
        });
        //주간 베스트 ----------------------------------------------------------------------------------------------------------------


        //이 달의 신상품 -------------------------------------------------------------------------------------------------------------
        var newCollection = $('.newCollections'); //이달의 신상품
        var newcateNum = newCollection.find('.new_container').data('cate');
        jQuery1_11_2.ajax({
            url: '/exec/front/Product/ApiProductNormal',
            type: 'GET',
            dataType: 'json',
            data: {
                cate_no: newcateNum,
                sort_method: 0, //5에서 0으로 변경
                bInitMore: "F",
                page: 1,
                count: '10'
            },
            success: function (data) {
                var prdData = data["rtn_data"]["data"];
                if(prdData != null){
                    if (prdData.length) {
                        for (let i = 0; i < prdData.length; i++) {
                            var elemNo = prdDataCallFunc(prdData[i]).prdNo;
                            if(newCollection.find('[data-prd-no="'+ elemNo +'"]').length == 0){
                                var elem = prdDataCallFunc(prdData[i]).item;
                                var test2 = document.createElement('li');
                                test2.classList.add('xans-record-');
                                test2.classList.add('append_item');
                                test2.classList.add('swiper-slide');
                                test2.innerHTML = elem;
                                newCollection.find('.common_items').append(test2);
                            }
                        }
                    }
                }
            }
        }).done(function (data, textStatus, xhr) {
            newCollection.find('.thumb img').addClass('swiper-lazy');
            var newCollectionSwiper = new Swiper('.new_container.swiper-container ', {
                slidesPerView: 3,
                slidesPerGroup: 3,
                loop:false,
                observeParents: true,
                observer: true,
                speed:300,
                spaceBetween:40,
                navigation: {
                    nextEl: ".newCollections .swiper-button-next",
                    prevEl: ".newCollections .swiper-button-prev",
                },
                scrollbar: {
                    el: ".newCollections .swiper-scrollbar",
                },
                preloadImages: false,
                // Enable lazy loading
                lazy: {
                    loadPrevNext: true,
                    loadPrevNextAmount: 1,
                    loadOnTransitionStart: true
                },
            });
        });
        //이 달의 신상품 -------------------------------------------------------------------------------------------------------------





        //매거진 스와이프


        $('.swiper-container.magazine-container .swiper-slide').each(function(){
            var el = $(this);
            var img = el.find('.boardThumb > img');
            var src = img.data('src');
            img.attr('src',src);
            img.removeAttr('data-src');

        });

        var magazineSwiper = new Swiper('.swiper-container.magazine-container', {
            loop : true,
            loopAdditionalSlides : 1, // 슬라이드 반복 시 마지막 슬라이드에서 다음 슬라이드가 보여지지 않는 현상 수정
            freeMode : false,
            autoHeight : true,
            observeParents: true,
            loopPreventsSlide: false,
            slidesPerView: 5,
            spaceBetween: 17,
            centeredSlides: true,
            navigation: {
                nextEl: '.magazine-container .swiper-button-next',
                prevEl: '.magazine-container .swiper-button-prev',
            },
        });
    }


    //메인배너 -------------------------------------------------------------------------------------------------------------------
    var mainBanner = $('.mainBanner'); 
    var mainBannerChk = mainBanner.length;
    if (mainBannerChk > 0) {
        mainBanner.find('.mainBanner_slide').each(function(){
            var mbSlide = $(this);
            var index = mbSlide.index();
            var src = mbSlide.find('img').attr('src');
            if(index > 0){
                mbSlide.find('img').addClass('swiper-lazy');
                mbSlide.find('img').attr('src','data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
                mbSlide.find('img').attr('data-src',src);
            }
        });
        var Mainswiper = new Swiper('.mainBanner.swiper-container', {
            slidesPerView: 'auto',
            loop: true,
            //loopFillGroupWithBlank: true,
            autoplay: {
                delay: 2500,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            lazy: {
                loadPrevNext: true,
                loadPrevNextAmount: 1,
                loadOnTransitionStart: true
            },
        });
    }
    //메인배너 -------------------------------------------------------------------------------------------------------------------














    //뮤즈 영역 클릭 시 동작
    $('.muse_wrap > span').click(function(){
        var txt_index = $(this).index();
        $('.muse_banner').hide();
        $('.muse_banner').eq(txt_index).fadeIn();
        $('.muse_wrap > span').removeClass('on');
        $(this).addClass('on');


        window['MusePrdSwiper' + txt_index].init();


    });










    //메인 이벤트 슬라이드 
    var Eventbanner = $('.SP_eventBanner_wrap').length;
    if (Eventbanner > 0) {
        /*
        $('.SP_eventBanner_wrap').addClass('swiper-container');
        $('.SP_eventBanner_wrap .SP_eventBnList_wrap').addClass('swiper-wrapper');
        $('.SP_eventBanner_wrap .SP_eventBn_li').addClass('swiper-slide');
        */
        $('.SP_eventBanner_wrap .SP_eventBn_li').each(function(){
            var meSlide = $(this);
            var index = meSlide.index();
            var src = meSlide.find('img').attr('src');
            if(index > 0){
                meSlide.find('img').addClass('swiper-lazy');
                meSlide.find('img').attr('data-src',src);
                meSlide.find('img').attr('src','data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
            }else{
                //preloading([src])
            }
        });
        var Eventswiper = new Swiper('.SP_eventBanner_wrap.swiper-container', {
            slidesPerView: 'auto',
            loop: true,
            loopFillGroupWithBlank: true,
            //autoplay: {
            //    delay: 2500,
            //},
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            lazy: {
                loadPrevNext: true,
                loadPrevNextAmount: 1,
                loadOnTransitionStart: true
            },
        });
    }

    //헤더영역 가림처리
    $('.prd_cate_container').hide();







    //주간 베스트 tab 클릭 시, 해당 상품 노출 
    $('.best_section .cate_tab > span').click(function(){
        $('.best_section .cate_tab > span').removeClass('on');
        $(this).addClass('on');
        var cate_tab = $(this).data('tab');
        $('.best_section .common_list_box').removeClass('on');
        $('.best_section .common_list_box[data-tab="' +cate_tab + '"]').addClass('on');
        window['bestPrdSwiper' + cate_tab].init();
        window['bestPrdSwiper' + cate_tab].slideTo( 0 , 0 , true );
    });

    //주간베스트 더보기 눌렀을 때 현재 보고있는 카테고리 url로 넘어가기
    $('.best_section .more_btn').click(function(){
        $('.best_section .common_list_box').each(function(){
            if($(this).hasClass('on')){
                var cate_now = $(this).children('div').data('cate');
                $('.best_section .more_btn').attr('href','/product/list.html?cate_no=' + cate_now);
            }
        });	
    });

    //내가 원하는 디자인 찾기
    $('.find_color_con .find_color > span').click(function(){
        if($(this).hasClass('on')){
            $(this).removeClass('on');
        }else{
            $(this).addClass('on');
        }
    });



    //큐레이션 선택하여 보러가기 눌렀을 때
    $('.find_color_con .find_color_btn ').click(function(){
        //배열 생성★★★★★ 위치 매우 중요 ==> 보러가기 누를 때 마다 배열 초기화되서 선택한 값 담기는거임
        var color_arr = [];

        $('.find_color_con .find_color > span').each(function(){
            if($(this).hasClass('on')){
                //value name
                var name = $(this).attr('name');
                //네일 스타일
                var value = $(this).attr('value');
                var color = name +'='+ value;

                //선택해서 on 붙은 큐레이션 값 배열에 담기
                color_arr.push(color);

            }    
        });

        //각각 배열에 담긴 객체 연결
        color_arr = color_arr.join('&');
        //console.log(color_arr + '인코딩전');

        var encode_color_arr = encodeURI(color_arr);
        //console.log(encode_color_arr + '인코딩후');
        window.location.href="/product/list.html?keyword=&rel_keyword=&" +encode_color_arr + "&cate_no=44";
    });



    //각 상품마다 실행
    $('.SMS_Product_display .items.common_items > li').each(function(){
        var el = $(this);
        prdItemInfoFunc(el);
    });


}); //document



//품절 아이콘 제어
function prdIcon(){
    setTimeout(function(){
        $('.SMS_Product_display .items.common_items li').each(function(){
            if($(this).find('.SMS_base_mask .icons .soldout > img').length > 0){
                $(this).addClass('soldout_prd prdIconFunc');
                $(this).find('.soldout_img').show();
            }else{
                $(this).find('.soldout_img').hide();
            }
        });

    },1000);
}





//window popup script
function winPop(url) {
    window.open(url, "popup", "width=300,height=300,left=10,top=10,resizable=no,scrollbars=no");
}
/**
 * document.location.href split
 * return array Param
 */
function getQueryString(sKey)
{
    var sQueryString = document.location.search.substring(1);
    var aParam       = {};

    if (sQueryString) {
        var aFields = sQueryString.split("&");
        var aField  = [];
        for (var i=0; i<aFields.length; i++) {
            aField = aFields[i].split('=');
            aParam[aField[0]] = aField[1];
        }
    }

    aParam.page = aParam.page ? aParam.page : 1;
    return sKey ? aParam[sKey] : aParam;
};
$(document).ready(function(){
    
    
    //장바구니 등 프로모션 썸네일 이미지 노출
    var chk_need_thumb = $('.chk_custom_studio ').length;

    if (chk_need_thumb > 0){


        //ajax로 프로모션 데이터 리스트 값 가져옴 
        $.ajax({
            type:"POST",
            url: "/new_promotion_data.html" ,
            dataType: 'html',
            success: function(html) {
                var ajax_option_list = $(html).find('.check_option_list').html();
                var ajax_option_list_leng = ajax_option_list.length;
                var ajax_cate_list = $(html).find('.check_option_list li');
                var ajax_cate_list_length = $(html).find('.check_option_list li').length;

                var product_name = '나만의 커스텀 오호라';

                //var chk_page = 0;	//장바구니: 0	주문서작성: 1
                //console.log(ajax_option_list);

                //cart 페이지, order 페이지 동일
                var cur_opt = [];

                var chked_opt = $('.chk_custom_studio .xans-order tr');
                var opt_length = chked_opt.length;

                //console.log(opt_length);

                for ( var i = 0; i < opt_length; i++){
                    var checked_name = $(chked_opt).eq(i).find('.name').text().indexOf(product_name);
                    //console.log(checked_name);
                    //해당 값이 있다면
                    if (checked_name > -1){
                        var opt_title = $(chked_opt).eq(i).find('[class*="option"]').text();	//장바구니: .option_txt 주문서작성: .option
                        var split = opt_title.split('옵션: ');
                        opt_title = split[1];
                        split = opt_title.split(']');


                        var product_url = $(chked_opt).eq(i).find('a').attr('href');
                        var split_url = product_url.split('?');
                        var page_url = '/new_promotion.html?' + split_url[1];
                        //console.log(page_url);

                        //최종 값
                        opt_title = split[0];
                        //console.log(opt_title);

                        //data 값 찾기                        
                        for( var j = 0; j < ajax_cate_list_length; j++) {
                            var ajax_option = ajax_cate_list.eq(j).text();

                            var chk_name = opt_title.indexOf(ajax_option);
                            if(chk_name > 0) {
                                //썸네일 사진 불러오기
                                var data_cate = ajax_cate_list.eq(j).data('cate');
                                var data_no = ajax_cate_list.eq(j).data('no');

                                if(data_cate < 10) { data_cate = '0' + data_cate; }
                                if(data_no < 100) {
                                    if( data_no < 10) { data_no = '00' + data_no; }
                                    else { data_no = '0' + data_no; }
                                }

                                var new_src = '/custom_studio/nail_img/mo/cate_' + data_cate + '/temp_prd_thumb/' + data_no + '.png';

                                // 페이지 썸네일 삽입
                                $(chked_opt).eq(i).find('.thumb img').attr('src', new_src);
                                $(chked_opt).eq(i).find('.thumb img').css({'object-fit': 'contain', 'padding': '15px'});
                                // a 링크 바꿔주기
                                $(chked_opt).eq(i).find('.thumb a').attr('href', page_url);
                                $(chked_opt).eq(i).find('.name a').attr('href', page_url);
                            }
                        }



                    }
                }

            }

        });

    } //if 문 끝
    
    
    
    
    
    // tab
    $.eTab = function(ul){
        $(ul).find('a').click(function(){
            var _li = $(this).parent('li').addClass('selected').siblings().removeClass('selected'),
                _target = $(this).attr('href'),
                _siblings = '.' + $(_target).attr('class');
            $(_target).show().siblings(_siblings).hide();
            return false
        });
    }
    if ( window.call_eTab ) {
        call_eTab();
    };
    
    if ($('.dna').length>0)
	{
		
		$('.dna').find('.animateTop').each(function(){
			
			if ($(this).attr('data-delay'))
			{
				var a = $(this).attr('data-delay');
				$(this).delay(a).queue(function(){
					$(this).addClass('active');
				});
			} else {
				$(this).addClass('active');
			}
		});

		$(window).scroll(function(){
			$('.animateBox').each(function(){
				var dt = $(this).offset().top - 400;
				if($(window).scrollTop() > dt) {
					$(this).find('.object').each(function(){
						if ($(this).attr('data-delay'))
						{
							var a = $(this).attr('data-delay');
							$(this).delay(a).queue(function(){
								$(this).addClass('active');
							});
						} 
					})
				}
				
			});
		}).scroll();
	}

	if ($('.store').length>0) {
		var obj = $('.store');
		obj.find('.store-list a').click(function(){
			var a = $(this).parent().index();
			obj.find('.store-list li').removeClass('active');
			$(this).parent().addClass('active');
			obj.find('.store-map > div').removeClass('active');
			obj.find('.store-map > div').eq(a).addClass('active');
			return false;
		});
	}

	if ($('.issue').length>0) {
		var obj = $('.issue');
		
		obj.attr('data-index',Math.round(obj.find('li').length/9));
		var k = obj.attr('data-index');
		var j = obj.find('.view').attr('data-index');

		if (k == j)
		{
			obj.find('.view').hide();
		}

		for (var i=0;i<9 ;i++ )
		{
			obj.find('li').eq(i).show().delay(i*100).queue(function(){$(this).addClass('active');});
		}
		obj.find('.view').click(function(){
			
			var b = $(this).attr('data-index');
			
			for (var i=b*9;i<(b*9)+9 ;i++ )
			{
				obj.find('li').eq(i).show().queue(function(){$(this).addClass('active');});
			}
			b++;
			$(this).attr('data-index',b);

			var a = obj.attr('data-index');
			var c = $(this).attr('data-index');
			if ( a == c)
			{
				$(this).hide();
			}
			return false;
		});
	}

	if ($('.issue-detail').length>0)
	{
		$('.title-block h2').addClass('active');
		$('.title-block p').addClass('active');
		$(window).scroll(function(){
			$('.issue-detail .object').each(function(){
				var dt = $(this).offset().top - ($(window).height()/2);
				if($(window).scrollTop() > dt) {
					$(this).addClass('active');
				}
			});
		}).scroll();
		
	}

	if ($('.collection').length>0)
	{
		$('.title-block h2').addClass('active');
		$('.title-block p').addClass('active');
		$(window).scroll(function(){
			$('.collection .object').each(function(){
				var dt = $(this).offset().top - ($(window).height()/2);
				if($(window).scrollTop() > dt) {
					if ($(this).hasClass('imgBox'))
					{
						if ($(this).hasClass('imgLeft'))
						{
							$(this).queue(function(){
								$(this).find('div').addClass('active');
							});
						} else if ($(this).hasClass('imgRight'))
						{
							$(this).queue(function(){
								$(this).find('div').addClass('active');
							});
						}
					} else {
						$(this).addClass('active');
					}
					
					
				}
			});
		}).scroll();
		$('.collection .imgBox').each(function(){
			$('.collection .imgBox, .collection .imgBox > div').height($(this).find('img').height());
		})
	}

	if ($('.eventPage').length>0)
	{
		var obj = $('.eventPage');
		
		obj.attr('data-index',Math.round(obj.find('.contents-box').length/4));
		var k = obj.attr('data-index');
		var j = obj.find('.view').attr('data-index');

		if (k == j)
		{
			obj.find('.view').hide();
		}

		for (var i=0;i<4 ;i++ )
		{
			obj.find('.contents-box').eq(i).show().delay(i*100).queue(function(){$(this).addClass('active');});
		}
		obj.find('.view').click(function(){
			
			var b = $(this).attr('data-index');
			
			for (var i=b*4;i<(b*4)+4 ;i++ )
			{
				obj.find('.contents-box').eq(i).show().queue(function(){$(this).addClass('active');});
			}
			b++;
			$(this).attr('data-index',b);

			var a = obj.attr('data-index');
			var c = $(this).attr('data-index');
			if ( a == c)
			{
				$(this).hide();
			}
			return false;
		});
		
	}
});

$(window).load(function(){
    
    // 상품상세 페이지 추천상품 위젯
    var chk_prdDetail = $('.SP_prdDtail_wrap').length;
    if( chk_prdDetail > 0 ) {
        setTimeout(function(){
            //var widgetH = $('.recmdPrdWiget iframe').height();
            //console.log(widgetH);
            $('.recmdPrdWiget iframe').attr('height', 371);
        }, 600);
    }
    
   function sns_text(){
        $("#ifrm_sns").contents().find(".add_info").remove();
        var cont_info_txt = '<p class="marketing add_info" style="color:red">모두 동의하면 젤램프 증정 (첫 구매 시)</p>';
        $("#ifrm_sns").contents().find(".ec-solution-box.typeMember.gMessage .information .marketing").after(cont_info_txt);
    }
    
    
    
    
    $('.sns_login_Btn').click(function(){
        //console.log('t');
        sns_text();
    }); 
    
    
});
/* 상품카테고리 Class 추가 */
prdListOptionSet({
	'opt1' : {
		'name' : '판매가',
		'name2' : '가격',
		'class' : 'SP_dfList_price'
	},
	'opt2' : {
		'name' : '할인판매가',
		'name2' : ' 할인가',
		'class' : 'SP_dfList_salePrice'
	},
	'opt3' : {
		'name' : '상품간략설명',
		'class' : 'SP_dfList_simpleDesc'
	},
	'opt4' : {
		'name' : '상품요약설명',
		'name2' : '상품요약정보',
		'class' : 'SP_dfList_summaryDesc'
	},
	'opt5' : {
		'name' : '상품색상',
		'class' : 'SP_dfList_colorChip'
	},
	'opt6' : {
		'name' : '상품문의',
		'class' : 'SP_dfList_inquiryCount'
	},
	'opt7' : {
		'name' : '사용후기',
		'class' : 'SP_dfList_reviewCount'
	},
	'opt8' : {
		'name' : '트렌드',
		'class' : 'SP_dfList_trand'
	},
	'opt9' : {
		'name' : '브랜드',
		'class' : 'SP_dfList_brand'
	},
	'opt10' : {
		'name' : '영문상품명',
		'class' : 'SP_dfList_engName'
	},
	'opt11' : {
		'name' : '상품명',
		'class' : 'SP_dfList_prdName'
	},
	'opt12' : {
		'name' : '소비자가',
		'class' : 'SP_dfList_consumerPrice'
	},
	'opt13' : {
		'name' : '제조사',
		'class' : 'SP_dfList_produceCompany'
	},
	'opt14' : {
		'name' : '재고 수량',
		'class' : 'SP_dfList_stockCount'
	},
	'opt15' : {
		'name' : '수량',
		'class' : 'SP_dfList_prdCount'
	},
	'opt16' : {
		'name' : '사이즈',
		'class' : 'SP_dfList_prdSize'
	},
	'opt17' : {
		'name' : '색상',
		'name2' : 'Color',
		'class' : 'SP_dfList_prdColor'
	},
	'opt18' : {
		'name' : '사이즈-Color',
		'class' : 'SP_dfList_prdSizeColor'
	},
	'opt19' : {
		'name' : '배송방법',
		'name2' : '배송기간',
		'class' : 'SP_dfList_delivery'
	},
	'opt20' : {
		'name' : 'RATE',
		'name2' : '배송비',
		'class' : 'SP_dfList_deliveryInfo'
	},

    
});

/* ====================================  옵션셋팅  ====================================  */


/* PRD OPTION HIDE */
SP$('.SP_prdList_item .SP_conts > span').removeAttr('style');
/* PRD OPTION HIDE */
if(SP$('.SP_prdList_item').length){
  
	SP$('.SP_prdList_item').each(function(i,v){
        
		var $target = SP$(v);
		// console.log(v);
		var $targetItemList = $target.find('.SP_prdListItemInfo');
		if($targetItemList.length){
           
			/* ENG PRD NAME*/
			/*
			var engName = $targetItemList.find('.SP_dfList_engName .SP_conts span').text();
				$target.find('.SP_engName_wrap .SP_title').html(engName);
				if(!engName){
					$target.find('.SP_engName_wrap').remove();
				}
			*/
			/* COLOR CHIP */
			/*
			var colorChipClone = $targetItemList.find('.SP_dfList_colorChip > span').clone();
				$target.find('.SP_colorchip_wrap').html(colorChipClone);
				if(!$target.find('.SP_colorchip_wrap').children().length){
					$target.find('.SP_colorchip_wrap').remove();
				}
			*/
			/* SUMMERY - 상품요약설명*/
			/*
			var summaryDesc = $targetItemList.find('.SP_dfList_summaryDesc .SP_conts span').text();
				$target.find('.SP_summary_wrap p').text(summaryDesc);
				if(!summaryDesc){
					$target.find('.SP_summary_wrap').remove();
				}
				*/
				/* 커스텀 아이콘 = BEST */
				/*
				if(summaryDesc.indexOf('BEST') !== -1){
					$target.find('.SP_cstBestIcon').css('display','block');
				}
				*/
				/* 커스텀 아이콘 = BEST */
							
				/* 커스텀 아이콘 = 재입고 */
				/*
				if(summaryDesc.indexOf('재입고') !== -1){
					var strTest = summaryDesc.split('_');
					var strTest_length = strTest.length;
					if(strTest_length === 2) {
						var strDesc = strTest[1],
							strDesc = strDesc.split('/'),
							strMonth = strDesc[0],
							strDay = strDesc[1],
							strDay = numberRes(strDay);
						$target.find('.SP_custormIcon_wrap').css('display','none');
						$target.find('.SP_custormIcon2_wrap').css('display','block');
						$target.find('.SP_cstRePrdMonth').text(strMonth);
						$target.find('.SP_cstRePrdDay').text(strDay);
					} else if(strTest_length === 3) {
						var strDesc = strTest[2],
							strDesc = strDesc.split('/'),
							strMonth = strDesc[0],
							strDay = strDesc[1],
							strDay = numberRes(strDay);
						$target.find('.SP_custormIcon_wrap').css('display','none');
						$target.find('.SP_custormIcon2_wrap').css('display','block');
						$target.find('.SP_cstRePrdMonth').text(strMonth);
						$target.find('.SP_cstRePrdDay').text(strDay);
					}
				}
				*/

			/* SUMMERY - 상품간략설명*/
			/*
			var simpleDesc = $targetItemList.find('.SP_dfList_simpleDesc .SP_conts span').text();
				$target.find('.SP_simple_wrap p').text(simpleDesc);
				if(!simpleDesc){
					$target.find('.SP_simple_wrap').remove();
				}
			*/
			/* price - 판매가 */
			var customPrice = $targetItemList.find('.SP_dfList_consumerPrice .SP_conts span').text();
			var price = $targetItemList.find('.SP_dfList_price .SP_conts span').text();
			var salePrice = $targetItemList.find('.SP_dfList_salePrice .SP_conts > span').text();
            //console.log(customPrice + '/' + price + '/' + salePrice);
				salePrice = salePrice.replace(/ /gi, ""); 
				if(salePrice) {
                    
					var salePrice_Array = salePrice.split('(');
					salePrice_Array = salePrice_Array[0];
					// console.log('salePrice_Array:',salePrice_Array);
				}
				salePrice = numberRes(salePrice);
				salePrice = comma(salePrice);
			if(customPrice && price && !salePrice) {
                 	
				$target.find('.SP_price_wrap .SP_price').text(customPrice);
				$target.find('.SP_price_wrap .SP_sale_price').text(price);
				$target.find('.SP_price_wrap .SP_price').addClass('strike');
				var DisCountPercent = (numberRes(customPrice) - numberRes(price)) / numberRes(customPrice);
					DisCountPercent = Math.round(DisCountPercent * 100);
				var salePercent = DisCountPercent + '%';
				$target.find('.SP_price_wrap .SP_salePercent').text(salePercent);
			} else if(!customPrice && price && salePrice) {
                
				$target.find('.SP_price_wrap .SP_price').text(price);
				$target.find('.SP_price_wrap .SP_sale_price').text(salePrice_Array);
				$target.find('.SP_price_wrap .SP_price').addClass('strike');
				var DisCountPercent = (numberRes(price) - numberRes(salePrice_Array)) / numberRes(price);
					DisCountPercent = Math.round(DisCountPercent * 100);
				var salePercent = DisCountPercent + '%';
				$target.find('.SP_price_wrap .SP_salePercent').text(salePercent);
			} else {
             
				$target.find('.SP_price_wrap .SP_price').text(price);
			}


				

			/* 할인율 계산 */
			/*
			if(salePrice){
					// $target.find('.SP_cstSalePerIconNum').text(salePercent)
					// $target.find('.SP_cstSalePerIcon').css('display','block')
			}else{
				// $target.find('.SP_price_wrap .SP_salePercent').remove();
				// $target.find('.SP_price_wrap .SP_sale_price').remove();
			}*/
			
			/* time - 타임세일 */
			var timeSaleDay = $target.find('.SP_dfList_endTime .title + p + p').text(),
				timeSaleDayArray = timeSaleDay.split('~'),
				timeSaleStart = timeSaleDayArray[0],
				timeSaleEnd = timeSaleDayArray[1];
			
			$target.find('.SP_time_wrap .startDate').text(timeSaleStart);
			$target.find('.SP_time_wrap .endDate').text(timeSaleEnd);
			
			/* 커스텀 시간 체크 - new icon */
			/*
			var $TimeWork = $target.find('.SP_prdRegister_date').text(); // 상품등록된 날짜 
				$TimeWork = $TimeWork.replace(',',''); // 상품등록된 날짜에서 string 콤마 제거
			var $prdRegi = new Date($TimeWork); // string을 date로 계산
				$prdRegi = $prdRegi.setDate($prdRegi.getDate()+14);  // 계산된 날짜에서 14일 더하기
				$prdRegi = new Date($prdRegi) // 14일이 더해진 달력값 셋
			
			var $nowTime = new Date(); // 현재시간
			// 현재 시간이 상품등록 14일후 날짜 및 시간 초 보다 크거나 같을때 NEW ICON 삭제
			if($nowTime <= $prdRegi){
				$target.find('.SP_cstNewIcon').fadeIn();
			}
			*/
			
			/* icon - 품절상품 */
			if($target.find('.SP_prdIcon_wrap .icon .soldoutIcon img').length) {
				$target.find('.SP_thumbHover_cont span.SP_basketIconBtn').addClass('active');
				$target.find('.SP_thumb_wrap .SP_thumbHover_wrap').addClass('active');
			}
            
            /* 재입고 알림 미노출 상품 */
            if($target.find('.restockNoneChk').text().indexOf('미노출')!==-1){
				$target.find('.SP_thumbHover_cont span.SP_basketIconBtn').removeClass('active');
				$target.find('.SP_thumb_wrap .SP_thumbHover_wrap').removeClass('active');
            }
			
		}
	});

}

//SP$('.SP_description').remove();
/* 숫자만 남기기 자르기 */
function numberRes(str){
	var str;
	str = str.replace(/[^0-9]/g,"");
	return str;
}

/* 천 단위, 추가 */
function comma(str) {
	str = String(str);
	return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

function removeComma(str){
	n = parseInt(str.replace(/,/g,""));
	return n;
}

function date_to_str(format){
    var year = format.getFullYear();
    var month = format.getMonth() + 1;
    if(month<10) month = '0' + month;
    var date = format.getDate();
    if(date<10) date = '0' + date;
    var hour = format.getHours();
    if(hour<10) hour = '0' + hour;
    var min = format.getMinutes();
    if(min<10) min = '0' + min;
    var sec = format.getSeconds();
    if(sec10) sec = '0' + sec;
	return year + "-" + month + "-" + date + " " + hour + ":" + min + ":" + sec;
}


// 계속 쓸 변수 여기서 한 번에 정의 
var $window = $(window);
var prdCateView = ['/event/index.html','/product/list.html'];
var currentUrl = window.location.href;
var scrollHeader = $('#fix_position'); //헤더 
var topBanner = $('.top_banner_container'); //탑배너 
var prdCataCon = $('.prd_cate_container'); //카테고리 메뉴 
var prdCateWrap = $('.prd_cate_wrap'); //카테고리 메뉴 하위 
var prdListGnb = $('.prdList_GNB'); //상품 분류 페이지 정렬방식/필터 등 영역
var contents = $('#contents'); //컨텐츠 
var prdDetailPage = $('.prdDetailStyle').length;
var prdDetailTabbar = $('#SMS_tabProduct');
var prdCateChk = false;
var offsetSet = $('.offset-set-chk');
var hdContainerAll = $('.hd_container_all');
if(offsetSet.length > 0){
    var offsetSetChk = $('.offset-set-chk').offset().top;
}else{
    var offsetSetChk = 0;
}

// ------------------------------------------- 이 영역 수정 금지 ----------------------------------------------- //
var feedTypeCateChk = false; //피드형 카테인지 확인 (true이면 피드형)
var depth2CateHideChk = false; //중카테 감춤 카테인지 확인 (true이면 중카테 감춤 카테고리)
var filterHideChk = false; //필터 감춤 카테인지 확인 (true이면 필터 감춤 카테고리)
var curationHideChk = false; //큐레이션 감춤 카테인지 확인 (true이면 큐레이션 감춤 카테고리)
var filterChkArrayTESTAll = new Array(); //필터체크용 배열
var menuCateNo = getParameterByName('cate_no'); //카테고리 넘버 확인

var prdItemChk = $('.prd_list_container [data-prd-list="ori"] .common_items > li').length; //최초 상품 진열 개수 찾기..(페이지네이션 관련)
var filterUseChk = false; //필터 사용 가능한지 체크
var filterClick = ''; //로딩 완료 전 클릭한 필터 찾기 

var allPrdArray = new Array();
var cateFind = $('meta[property="og:title"]').attr('content');
//console.log(cateFind);

var windowPathName = window.location.pathname;
var windowloSearch = window.location.search

/* -- 삭제, 주석, 위치 이동 금지 영역 -- */
//preload 이미지 진행 
var newArray = new Array();
$('.preload_img, .preload_img img').each(function(){
    var src = $(this).attr('src');
    var dataSrc = $(this).data('src');
    if(typeof src != undefined){
        newArray.push(src);
    }
    if(typeof dataSrc != undefined){
        newArray.push(dataSrc);
    }
});
if(newArray.length != 0){
    preloading(newArray);
}

//$('#sltop_ban').children().addClass('swiper-slide');
//$('#sltop_ban').addClass('swiper-wrapper');
//$('#sltop_ban').parent().addClass('swiper-container top_banner_container');
//
//$('#sltop_ban img').each(function(){
//    var img = $(this);
//    var index = img.index();
//    if(index != 0){
//        var src = img.attr('src');
//        img.addClass('swiper-lazy');
//        img.attr('data-src',src);
//        img.attr('src','data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
//    }else{
//        var src = img.attr('src');
//        preloading([src]);
//    }
//});
//
//var topBnSwiper = new Swiper('.top_banner_container' , {
//    slidesPerView: 1,
//    observer: true,
//    observeParents: true,
//    loop: true,
//    lazy: {
//        loadPrevNext: true,
//        loadPrevNextAmount: 1,
//        loadOnTransitionStart: true
//    },
//    autoplay: {
//        delay: 3000,
//    },
//});


// 햄버거 메뉴 -------------------------------------------------------------
$('.hd_cate_container .recommend_crew .imgBox img').each(function(){
    var img = $(this);
    var src = img.attr('src');
    img.attr('data-src',src);
    img.attr('src','data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
});
jQuery1_11_2('.SMS_menu').on('click',function(){
    if($('#side_menu .recommend_crew').hasClass('load-complete') == false){
        hamRecoImgFunc();
    }
});
function hamRecoImgFunc(){
    $('.hd_cate_container .recommend_crew .imgBox img').each(function(){
        var img = $(this);
        var dataSrc = img.data('src');
        if(typeof dataSrc != 'undefined'){
            img.attr('src',dataSrc);
            img.removeAttr('data-src');
        }
    });
    $('.hd_cate_container .recommend_crew').addClass('load-complete');
}
// 햄버거 메뉴 -------------------------------------------------------------

cate_width();
//헤더 카테고리 width 값 구하기
$('.SP_topBanner').css('display','block');

// 할인판매가 클래스 붙여주기 -------------------------------------------------------------
jQuery1_11_2('.SP_prdList_item').each(function(){
    jQuery1_11_2(this).find('.SP_description li').each(function(){
        if(jQuery1_11_2(this).find('.title span').text().trim().indexOf('할인판매가') > -1){
            jQuery1_11_2(this).addClass('SP_dfList_salePrice');
        }
    });
    var test = jQuery1_11_2(this).find('.SP_dfList_salePrice .SP_conts').text();
    jQuery1_11_2(this).find('.SP_sale_price').text(comma(test));
});
// 할인판매가 클래스 붙여주기 -------------------------------------------------------------

// 최근 본 디자인(상품상세) - 나만의 커스텀 오호라 링크 변경 --------------------------------
//var $Detail_rencent_Length = SP$('.recentDesign_wrap .recentDesign .product_list').length;
//if($Detail_rencent_Length){
//    var chk_prd_name = '나만의 커스텀 오호라';
//    $('.recentDesign_wrap .recentDesign .product_list').each(function(){
//        var product_name = $(this).find('.name').text();
//        if(product_name == chk_prd_name) {
//            var product_url = $(this).find('a').attr('href');
//            var split_url = product_url.split('?');
//            var page_url = '/new_promotion.html?' + split_url[1];
//            $(this).find('a').attr('href', page_url);
//        }
//    });
//}
// 최근 본 디자인(상품상세) - 나만의 커스텀 오호라 링크 변경 ----------------------------------
// 최근 본 디자인(상품상세) 슬라이드 ----------------------------------------------------------
//if($Detail_rencent_Length){
//    // 가격 text 변경
//    $('.recentDesign .product_list').each(function(){
//        var recent_price = $(this).find('.price').text();
//        recent_price = rechange(recent_price);
//        recent_price = numberWithCommas(recent_price);
//        $(this).find('.price').text(recent_price);
//    });
//
//    if($Detail_rencent_Length > 5){
//        var Dotswid = 100 / $Detail_rencent_Length;
//        SP$('.recentDesign').slick({
//            infinite: true,
//            speed: 500,
//            slidesToShow: 5,
//            slidesToScroll: 5,
//            dots: true,
//            arrows: false,
//            autoplay: false,
//            autoplaySpeed: 3000,
//        });
//        $Detail_rencent_Length ? SP$('.recentDesign .slick-dots li').css({'width':Dotswid+'%'}) : console.log('slides not found');
//        SP$('.recentDesign_wrap').fadeIn();
//    } else {
//        SP$('.recentDesign_wrap .recentDesign .product_list').css({'width':'calc(20% - 5px)'});
//        SP$('.recentDesign_wrap').fadeIn();
//    }
//} else {
//    // 최근 본 상품 없을 때
//    SP$('.recentDesign_wrap').fadeIn();
//}
// 최근 본 디자인(상품상세) 슬라이드 ----------------------------------------------------------

//추천인 텍스트 변경 --------------------------------------------------------------------------
var mile_name = $('.SP_myMileagelist_tab li.selected a').text();
if(mile_name.length > 0){
    var pointTR = $('.xans-myshop-mileagehistorypackage tr');
    pointTR.each(function(){
        //console.log(this);
        $(this).find('td:contains(추천한 신규 가입자)').text('친구 추천 적립금 1,000원');
        $(this).find('td').css('opacity','1');
    });  
}
//추천인 텍스트 변경 --------------------------------------------------------------------------

//footer 토글 제어 --------------------------------------------------------------------------
$('#footer .footer_menu .list .arr').click(function(){
    if($(this).hasClass('on') == true){
        $(this).removeClass('on');
        $(this).parents('li.list').find('.sub').slideUp(300);
    }else{
        $(this).addClass('on');
        $(this).parents('li.list').find('.sub').slideDown(300);
    }
});
//footer 토글 제어 --------------------------------------------------------------------------

//로그인 완료 페이지 데이터 붙이기 ----------------------------------------------------------
if($('#SMS_login_warp').length > 0){
    if($('#loginChk_nomember').length == 0){
        membershipFunc();
        function membershipFunc(){
            var checkSessionMember = sessionStorage.getItem('member_1');
            if(checkSessionMember){
                var data_json = JSON.parse(checkSessionMember);
                var groupName = data_json.data.group_name; //그룹명
                var memberName = data_json.data.name; //회원이름
                var memberId = data_json.data.member_id; //회원 아이디
                var memberEmal = data_json.data.email; //회원이메일

                if(groupName == ''){
                    groupName = '관리자';
                }

                $('.member_result_form .bottom_area .id span').text(memberId);
                $('.member_result_form .bottom_area .name span').text(memberName);
                $('.member_result_form .bottom_area .email span').text(memberEmal);
                $('.member_result_form .groupname').text(groupName);
                $('.member_result_form').removeClass('displaynone');

            }else{
                setTimeout(membershipFunc,100);
            }
        }
    }
} 
//로그인 완료 페이지 데이터 붙이기 ----------------------------------------------------------

//헤더 카테고리 영역 노출될 경우 정의 ---------------------------------------- 

var prdCateChk = false;
prdCateView.forEach(function(el,index){
    if(windowPathName == el){
        prdCateChk = true;
    }else{
        if(!prdCateChk){
            prdCateChk = false;
        }
    }
});

if(prdCateChk){
    $('#fix_position .prd_cate_container').show();
}
var hdHeight = $('#SMS_fixed_wrap').outerHeight();
$('#wrap').attr('style','padding-top: '+ hdHeight +'px !important;');
$('.hd_cate_container .common_reco_section').addClass('swiper-container');
//헤더 카테고리 영역 노출될 경우 정의 ----------------------------------------

// 스크롤 시 헤더 동작 -------------------------------------------------------
$(window).scroll(function(){
    var wT = $(window).scrollTop();
    if(wT > 0){
        //$('#fix_position.modify').addClass('fixed');
    }else{
        //$('#fix_position.modify').removeClass('fixed');
    }
});
// 스크롤 시 헤더 동작 -------------------------------------------------------

//헤더의 검색 아이콘 클릭했을 때 
    $('.small_icon.search_fixed_btn').click(function(){
        if($('.hd_cate_container').is(':visible')){
            $('.hd_cate_container').slideUp();
            $('.small_icon.m_menu').removeClass('active');
            $('.prd_cate_container').removeClass('no-hover');
        }
        var hasActive = $(this).hasClass('active');
        if(hasActive){
            $(this).removeClass('active');
            $('.hd_search_container').slideUp();
        }else{
            $(this).addClass('active');
            $('.hd_search_container').slideDown();
        }
    });

    //햄버거 메뉴 클릭했을 때
    $('.small_icon.m_menu').click(function(){
        if($('.hd_search_container').is(':visible')){
            $('.hd_search_container').slideUp();
            $('.small_icon.search_fixed_btn').removeClass('active');
        }
        var hasActive = $(this).hasClass('active');
        if(hasActive){
            $(this).removeClass('active');
            $('.hd_cate_container').slideUp();
            $('.prd_cate_container').removeClass('no-hover');
        }else{
            $(this).addClass('active');
            $('.hd_cate_container').slideDown();
            $('.prd_cate_container').addClass('no-hover');
        }
    });



    //크루 추천
    var crew_swiper = new Swiper('.hd_cate_container .recommend_crew.swiper-container', {
        slidesPerView: 'auto',
        spaceBetween: 0,
        scrollbar: {
            el: '.recommend_crew .swiper-scrollbar',
        },
        observer: true,
        observeParents: true,
    });

//장바구니 확인창 노출 제어 --------------------------------------------------
jQuery1_11_2('#for_opt_select_chk').on('change',function(){
    console.log('change');
    basketConfirmShow();
});
//장바구니 확인창 노출 제어 --------------------------------------------------


if(currentUrl.indexOf('/product/list.html?cate_no=') > -1 || currentUrl.indexOf('/product/detail.html') > -1){
}else{
    sessionStorage.removeItem('iv_cate_no');
    sessionStorage.removeItem('iv_feed_cateChk');
    sessionStorage.removeItem('iv_cate_scroll');
    sessionStorage.removeItem('iv_cate_view');
    sessionStorage.removeItem('iv_cate_html');
}


// 숫자만 추출
function rechange(mon){
    var ori_mon = Number(mon.replace(/[^0-9]/g,''));
    return ori_mon;
}

// 숫자 콤마 넣기
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

//콤마찍기
function comma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}
//콤마풀기
function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
}

//짝수인가 검증
function isEven(n) {
    n = Number(n);
    return n === 0 || !!(n && !(n%2));
}

//홀수인가 검증
function isOdd(n) {
    return isEven(Number(n) + 1);
}

// '-' 제거
function removeDashFunc(text){
    var reg = /\-/g;
    if(reg.test(text)){
        return text.replace(reg,'').trim();
    }else{
        return text;
    }
}

//숫자만 남기기
function onlyNumbFunc(text){
    var reg = /[^0-9]/g;
    if(reg.test(text)){
        return text.replace(reg,'').trim();
    }else{
        return text;
    }
}

//숫자 없애기 
function removeNumbFunc(text){
    var reg = /[0-9]/g;
    if(reg.test(text)){
        return text.replace(reg,'').trim();
    }else{
        return text;
    }    
}

//특수문자 제거
function regExp(str){  
    var reg = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi;
    if(reg.test(str)){
        return str.replace(reg, "").trim();    
    } else {
        return str;
    }  
}

//url 파라미터 가져오기
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

//dimmed 생성
function makeDimmed() {
    var dimmedAlreayExistChk = $('.dimmed').length;
    if(dimmedAlreayExistChk == 0){
        $('body').append('<div class="dimmed on"></div>');
    }
}
//dimmed 제거
function removeDimmed() {
    $('.dimmed').remove();
}

//배열값 내 앞뒤 공백 제거
function arrayTrimFunc(array){
    $.each(array, function (idx, val) {
        array[idx] = $.trim(this);
    });
}

//배열값 내 특정 텍스트 존재하는지 확인 
function checkAvailability(arr, val) {
    return arr.some(function(arrVal) {
        return val === arrVal;
    });
}

//공백을 +로 변환 (옵션명)
function blankToPlusFunc(text){
    if(text.search(/\s/) != -1) { 
        text = text.replace(/\s/g, "+");
    }
    return text;
}

//파일 존재하는지 확인
function UrlExists(url)
{
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    return http.status!=404;
}

//헥사코드 변환
function rgb2hex(rgb) {
    if (  rgb.search("rgb") == -1 ) {
        return rgb;
    } else {
        rgb = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
        function hex(x) {
            return ("0" + parseInt(x).toString(16)).slice(-2);
        }
        return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
    }
}

//오늘 날짜 생성
function dateToday() {
    var Now = new Date(),
        StrNow = String(Now),
        nowYear = String(Now.getFullYear()),
        nowMon = String(Now.getMonth() + 1),
        nowDay = String(Now.getDate()),
        nowHours = String(Now.getHours()), // 시
        nowMinutes = String(Now.getMinutes()),  // 분
        nowSeconds = String(Now.getSeconds()),  // 초
        nowMilliseconds = String(Now.getMilliseconds()); // 밀리

    if (nowMon.length == 1) {
        nowMon = "0" + nowMon
    }
    if (nowDay.length == 1) {
        nowDay = "0" + nowDay
    }
    if (nowHours.length == 1) {
        nowHours = "0" + nowHours
    }
    if (nowMinutes.length == 1) {
        nowMinutes = "0" + nowMinutes
    }
    if (nowSeconds.length == 1) {
        nowSeconds = "0" + nowSeconds
    }

    //var array = [nowYear,nowMon,nowDay,nowHours,nowMinutes,nowSeconds];
    return nowYear+ '-' + nowMon+ '-' + nowDay+ '-' + nowHours+ '-' + nowMinutes+ '-' + nowSeconds;
}
//날짜 계산 함수 
function dateAddDel(selectDate, nNum, type) {
    //배열의 경우 
    if (Array.isArray(selectDate)) {
        var sDate = selectDate.join('');
    }else{
        sDate = selectDate;
    }
    var yyyy = parseInt(sDate.substr(0, 4), 10);
    var mm = parseInt(sDate.substr(4, 2), 10);
    var dd = parseInt(sDate.substr(6, 2), 10);
    if (type == "d") {
        d = new Date(yyyy, mm - 1, dd + nNum);
    } else if (type == "m") {
        d = new Date(yyyy, mm - 1 + nNum, dd);
    } else if (type == "y") {
        d = new Date(yyyy + nNum, mm - 1, dd);
    }
    yyyy = d.getFullYear();
    mm = d.getMonth() + 1;
    mm = (mm < 10) ? '0' + mm : mm;
    dd = d.getDate();
    dd = (dd < 10) ? '0' + dd : dd;
    return yyyy + '' + mm + '' + dd;
}


//기간 계산 함수
function betweenDateFunc(stDate, endDate){
    // 시작일시
    if(typeof stDate == 'string'){
        var stTime = new Date(parseInt(stDate.substring(0,4), 10),
                              parseInt(stDate.substring(4,6), 10)-1,
                              parseInt(stDate.substring(6,8), 10),
                              parseInt(stDate.substring(8,10), 10),
                              parseInt(stDate.substring(10,12), 10),
                              parseInt(stDate.substring(12,14), 10)
                             );

    }else{
    	var stTime = stDate;
    }
    
    
    // 종료일시
    if(typeof endDate == 'string'){
        var endTime   = new Date(parseInt(endDate.substring(0,4), 10),
                                 parseInt(endDate.substring(4,6), 10)-1,
                                 parseInt(endDate.substring(6,8), 10),
                                 parseInt(endDate.substring(8,10), 10),
                                 parseInt(endDate.substring(10,12), 10),
                                 parseInt(endDate.substring(12,14), 10)
                                );
    }else{
    	var endTime = endDate;
    }
    // 두 일자(startTime, endTime) 사이의 차이를 구한다.
    var dateGap = endTime.getTime() - stTime.getTime();
    var timeGap = new Date(0, 0, 0, 0, 0, 0, endTime - stTime); 
    // 두 일자(startTime, endTime) 사이의 간격을 "일-시간-분-초"로 표시한다.
    var diffDay  = Math.floor(dateGap / (1000 * 60 * 60 * 24)); // 일수       
    var diffHour = timeGap.getHours();       // 시간
    var diffMin  = timeGap.getMinutes();      // 분
    var diffSec  = timeGap.getSeconds();      // 초
    //if( diffDay <= 0 && (diffHour == 14 && diffMin == 0 && diffSec == 0 ) || diffHour < 14 ){
    //    var result = 'doing';
    //}else{
    //    var result = 'end';
    //}
    var result = [dateGap, diffDay, diffHour, diffMin, diffSec];
    return result;
}

//상품 정보 함수 
function prdItemInfoFunc(el){
    //setTimeout(function(){
        var CHDone = el.find('.hash_container').hasClass('done');
        if(!CHDone){
            
            //해시태그 생성 -----------------------------------------------
            var hashCon = el.find('.info_container .subname').text();
            hashCon = hashCon.split('#');
            hashCon.forEach(function(el2, idx2){
                var text = el2.trim();
                var firstLetter = text.charAt(0);
                var textcolor = '#9a9a9a';
                var regExp = /[\{\}\[\]\/?.,;:|\)*~`!^\-+<>@\#$%&\\\=\(\'\"]/gi;
                if(regExp.test(firstLetter) == true){
                    var chkIndex = scArray.indexOf(firstLetter);
                    textcolor = colorArray[chkIndex];
                    text = text.split(firstLetter)[1];
                    var hashSpan = '<span style="color: '+ textcolor + '">#' + text + '</span>';
                }else{
                    if(text != ''){
                        var hashSpan = '<span style="color: '+ textcolor + '">#' + text + '</span>';
                    }
                }
                el.find('.hash_wrap').append(hashSpan);
            });	
            //해시태그 생성 -----------------------------------------------
            
            //가격에 특수문자 출력될 경우 지우기 
            el.find('.price_container p').each(function(){
                var text = $(this).text();
                if(text.indexOf('￦') > -1){
                    var textReplace = text.replace('￦','');
                    $(this).text(textReplace);
                }
            });
            // 할인율 찍어주기
            // 할인율 등록 방식에 따라 출력되는 부분이 달라 다르게 나올 수 있음
            
            var setCustomPrice = Number(uncomma(el.find('.display_커스텀판매가 > span').text()));
            var customPrice = Number(uncomma(el.find('.price_container .custom_price').text()));
            var normalPrice = Number(uncomma(el.find('.price_container .price').text()));
            var salePrice = Number(uncomma(el.find('.price_container .sale_price').text()));
            var setCustomPrice2 = Number(uncomma(el.find('.display_세트소비자가 > span').text()));


            if(normalPrice){
                //console.log(setCustomPrice, customPrice, normalPrice, salePrice, setCustomPrice2);

                if(customPrice == 0){
                    customPrice = Number(uncomma(el.find('.display_소비자가 > span').text()));
                }
                el.find('.price_container .custom_price').text(comma(customPrice));

                if(salePrice == 0){
                    if(el.find('[class*="할인판매가"]').length > 0){
                        if(el.find('.display_할인판매가').length > 0){
                            salePrice = Number(uncomma(el.find('.display_할인판매가 > span').text()));
                        }
                        if(el.find('[class*="할인판매가"]').not('.display_할인판매가').length > 0){
                            salePrice = Number(uncomma(el.find('[class*="할인판매가"]').not('.display_할인판매가').children('span').text()));
                        }
                    }

                }

                el.find('.price_container .sale_price').text(comma(salePrice));

                //console.log(customPrice);
                //console.log(normalPrice);
                //console.log(salePrice);

                //세트 커스텀 가격 존재 (이 부분을 1순위로 체크)
                if(setCustomPrice > 0){
                    var discount = Math.round(((normalPrice - setCustomPrice) / normalPrice) * 100);
                    el.find('.price_container .price').addClass('strike');
                    el.find('.price_container .price').before('<p class="setCustomPrice">'+comma(setCustomPrice)+'</p>');
                    var discountP = '<p class="discount">'+ discount+'%</p>';
                    el.find('.price_container').append(discountP);
                }else{
                    // 1. 커스텀 가격 존재 
                    if( (customPrice > 0 && normalPrice > 0) || (setCustomPrice2 > 0 && normalPrice > 0) ){
                        if(setCustomPrice2 == 0){
                            var discount = Math.round(((customPrice - normalPrice) / customPrice) * 100);
                            var discountP = '<p class="discount">'+ discount+'%</p>';
                            el.find('.price_container').append(discountP);
                            el.find('.price_container .custom_price').removeClass('displaynone').addClass('strike');
                        }else{
                            var discount = Math.round(((setCustomPrice2 - normalPrice) / setCustomPrice2) * 100);
                            var discountP = '<p class="discount">'+ discount+'%</p>';
                            el.find('.price_container').append(discountP);
                            el.find('.price_container .custom_price').text(comma(setCustomPrice2));
                            el.find('.price_container .custom_price').removeClass('displaynone').addClass('strike');
                        }
                        // 2. 프로모션 할인 가격
                    }else if( (customPrice == 0 && salePrice > 0) || (setCustomPrice2 == 0 && salePrice > 0) ){
                        var discount = Math.round(((normalPrice - salePrice) / normalPrice) * 100);
                        var discountP = '<p class="discount">'+ discount+'%</p>';
                        el.find('.price_container').append(discountP);
                        el.find('.price_container .price').removeClass('displaynone').addClass('strike');
                        el.find('.price_container .sale_price').removeClass('displaynone');
                    }
                }

            }else{
            	el.find('.price_container .price').text('0');
            }

            
            //재입고 알림 아이콘 출력되지 않는 오류 잡기 - 품절 아이콘 있을때만 동작 
            var altCheckArray = new Array();
            el.find('.base_mask .icons > span').each(function(){
                if($(this).find('img').length > 0){
                    var AltVal = $(this).find('img').attr('alt');
                    if(AltVal == '품절'){
                        altCheckArray.push('true');
                        $(this).addClass('displaynone');
                    }else{
                        altCheckArray.push('false');
                    }
                }else{
                    $(this).addClass('displaynone');
                }
            });
            //리뉴얼 BEST 리뷰 아이콘 띄우기 - 기존 베스트 리뷰 아이콘 있을 때 출력 
            var bestReviewIcon = el.find('.icons .best_review img').length;
            if(bestReviewIcon > 0){
                el.find('.base_img .BR_icon').addClass('on');
                el.find('.icons .best_review').addClass('displaynone');
            }
            if(el.find('.base_mask .icons > span:not(".displaynone")').length > 0){
                el.find('.icons').css({'margin-right': '4px'});
            }else{
                el.find('.icons').addClass('displaynone');
            }
            if(checkAvailability(altCheckArray, 'true') == true){
                el.find('.restockIcon').css('display','block');
                el.find('.container').addClass('soldout_prd');
                el.find('.soldout_img').show();
            }else{
                el.find('.Prev_Cart').css('display','block'); //장바구니 아이콘 출력되지 않는 오류가 있어서 display block 걸어줌    
                el.find('.soldout_img').hide();
            }
            var prdFilter = el.find('.display_상품필터값 > span').text();
            //console.log(prdFilter);
            var filterHash = '';
            if(prdFilter.length > 0){
                if(prdFilter.indexOf(',')){
                    filterHash = prdFilter.replace(/,/g, '#');
                    filterHash= '#' + filterHash;
                }else{
                    filterHash= '#' + filterHash;
                }
                //filterChkArrayTESTAll.push(test);
                el.find('.container').attr('data-filter',filterHash);
            }
            el.find('.hash_container').addClass('done'); //작업 완료시 .done 추가
            el.find('.container').addClass('complete');
        }
    //},50);
}


var setTimeoutCount = 0; 
//장바구니 옵션 확인창 띄우기
function basketConfirmShow(el){
    var confirmLength = jQuery1_11_2('#confirmLayer').length;
    if(confirmLength > 0){
        jQuery1_11_2('.xans-product-basketadd').fadeIn({
            complete: function() {
                setTimeout(function(){
                    jQuery1_11_2('#confirmLayer').addClass('modify');
                    jQuery1_11_2('#confirmLayer .content p').html('장바구니에 상품을 담았습니다.');
                    jQuery1_11_2('#confirmLayer').addClass('on');
                    if(el == 'addPopup'){
                        jQuery1_11_2('#confirmLayer').show(); //추가구성상품 담은 경우 confirmLayer를 보여줌
                    }
                },300);
                
                if(addProductCateArray.indexOf(Number(menuCateNo)) > -1){ // 추가구성상품관련 팝업이 떠야하는 카테고리인지 체크
                    setTimeout(function(){
                        if(jQuery1_11_2('#confirmLayer').css('display') == 'none'){
                            //console.log('장바구니 메세지 뜨지 않음');
                        } else {
                            addPrdLayerShow(el);
                        }
                    },350);
                }

                setTimeout(function(){
                    jQuery1_11_2('.xans-product-basketadd').fadeOut({
                        complete: function() {
                            jQuery1_11_2('#confirmLayer').hide();
                            //console.log('hide');
                            setTimeoutCount = 0;
                        }
                    });
                },1000);
            }
        });
        
    }else{
        if(setTimeoutCount < 10){
            setTimeout(function(){
                basketConfirmShow(el)
            },100);
            setTimeoutCount = setTimeoutCount + 1;
            //console.log(setTimeoutCount); 
        }else{
            // console.log('x'); 
            clearTimeout(function(){
                basketConfirmShow(el)
            });
            setTimeoutCount = 0;
        }
    }
}

// 단일상품인지 체크 후 장바구니옵션팝업창 열어주기
function addPrdLayerShow(el){
    if(el){
        if(el != 'addPopup'){
        	var cartEvent = jQuery1_11_2(el).find('img.cart').attr('onClick');
            if(cartEvent.indexOf('selectOptionCommon') == -1){
                var prdNum = jQuery1_11_2(el).closest('.container.complete').attr('data-prd-no');
                CAPP_SHOP_NEW_PRODUCT_OPTIONSELECT.selectOptionCommon(prdNum, menuCateNo, 'basket', ''); // 장바구니 담기 팝업
            }
        }
    }
}

var changeQueue = (function() {
    var list = [];
    var index = 0;
    return {
        enqueue: function(c) {
            list.push(c);
        },
        dequeue: function() {
            var o = list[index];
            index++;

            return o;
        },
        isEmpty: function() {
            return list.length - index === 0;
        }
    }
})();


//동적으로 상품 데이터 붙여넣기
function prdDataCallFunc(prdData){
    var prdItem = '';
    var filter;
    var value = prdData;
    var item_class = '';
    var product_no = value.product_no; //상품번호 
    var image_medium = value.image_medium; //상품 이미지 중간 사이즈 
    var image_big = value.image_big; //상품 이미지 큰 사이즈
    var product_name = value.product_name; //상품명 
    var param = value.param; //파라미터
    var priceWrap;
    var product_price = value.product_price; //판매가
    var ori_price = '<p class="price">' + comma(product_price) + '</p>';
    var soldoutChk = false;
    var bestItemChk = false;
    var origin_prd_price_sale = value.origin_prd_price_sale; //프로모션 할인가 
    var product_custom = value.product_custom; //소비자가 
    var discount = 0;
    
    if(value.basket_display != false){
        var basket_icon = value.basket_icon; //장바구니 아이콘 
    }else{
        var basket_icon = '';
    }
    //console.log(basket_icon);
    //if(product_price == ''){
    //	product_price = 0;
    //}
    
    
    var icons = '';
    var iconLength = 0; //displaynone 아닌 상태로 노출될 경우에만 가산
    var new_icon = value.new_icon; //뉴아이콘
    var sold_icon = value.soldout_icon; //품절 아이콘
    var recommend_icon = value.recommend_icon; //추천 아이콘
    var product_icons = value.product_icons; //뭔지 모르겠지만 아이콘
    var benefit_icons = value.benefit_icons; //뭔지 모르겠지만 아이콘 
    if(sold_icon  != ''){
        soldoutChk = true;
        icons = icons + '<span class="soldout displaynone">' + sold_icon + '</span>';
    }
    if(new_icon  != ''){
        iconLength = iconLength +1;
        icons = icons + '<span class="new">' + new_icon + '</span>';
    }
    if(recommend_icon  != ''){
        bestItemChk = true;
        icons = icons + '<span class="recommend displaynone">' + recommend_icon + '</span>';
    }
    if(product_icons  != ''){
        iconLength = iconLength +1;
        icons = icons + '<span class="best_review">' + product_icons + '</span>';
    }
    if(benefit_icons  != ''){
        icons = icons + '<span class="benefit_icon displaynone">' + benefit_icons + '</span>';
    }
    if(iconLength == 0){
        var iconStyle = ' displaynone" style="display: none;"';
    }else{
        var iconStyle = '" style="margin-right: 4px;"';
    }
    if(soldoutChk == true){
        item_class = item_class + ' soldout_prd';
        var soldout_img = '<span class="soldout_img" style="display: inline;"><span>coming<br>soon</span></span>';    
    }else{
        item_class = item_class;
        var soldout_img = '<span class="soldout_img" style="display: none;"><span>coming<br>soon</span></span>';    
    }
    if(bestItemChk == true){
        var best_review_icon = '<div class="BR_icon on"><p><b>BEST</b>리뷰</p></div>';
    }else{
        var best_review_icon = '<div class="BR_icon"><p><b>BEST</b>리뷰</p></div>';
    }
    
    //상품요약설명
    var summary_desc = value.summary_desc;
    var hash_wrap = '';
    if(typeof summary_desc == 'undefined'){
        summary_desc = '';
    }else{
        if(summary_desc != ''){
            var hashChk = new Array();
            var hashCon = summary_desc;
            hashCon = hashCon.split('#');
            hashCon.forEach(function(el2, idx2){
                var text = el2.trim();
                var firstLetter = text.charAt(0);
                var textcolor = '#9a9a9a';
                var regExp = /[\{\}\[\]\/?.,;:|\)*~`!^\-+<>@\#$%&\\\=\(\'\"]/gi;
                if(regExp.test(firstLetter) == true){
                    var chkIndex = scArray.indexOf(firstLetter);
                    textcolor = colorArray[chkIndex];
                    text = text.split(firstLetter)[1];
                    var hashSpan = '<span style="color: '+ textcolor + '">#' + text + '</span>';
                }else{
                    if(text != ''){
                        var hashSpan = '<span style="color: '+ textcolor + '">#' + text + '</span>';
                    }else{
                    	var hashSpan = '';
                    }
                }
                hash_wrap = hash_wrap + hashSpan;
            });	
        }
    }
    
    //상품간단설명?
    var simple_desc = value.simple_desc;
    if(typeof simple_desc == 'undefined'){
        simple_desc = '';
    }
    var productListitem = value["@Listitem"]; //상품정보 가져옴
    var filterHash = '';
    var productListitemAppend = '';
    var setCustomPrice = 0; //세트 커스텀 상품 가격
    var setCustomPrice2 = 0; //세트 소비자가 상품 가격
    
    productListitem.forEach(function(e,i){
        if(e.item_display == true){
            var itemTitle = e.item_title;
            if(itemTitle.indexOf('>') > -1){
                var itemTitleStripTag = itemTitle.split('>')[1];
                if(itemTitle.indexOf('<') > -1){
                    itemTitleStripTag = itemTitleStripTag.split('<')[0];
                }
            }
            var title = '<strong class="title">'+ itemTitle +'</strong>';
            if(e.item_title_display == false){
                var title = '<strong class="title displaynone">'+ itemTitle +'</strong>';
            }
            var itemContent = e.item_content;
            if(typeof itemTitleStripTag != 'undefined'){
                var prdListitemSingle = '<div class=" display_'+ itemTitleStripTag +' xans-record-">' + title + itemContent + '</div>';
            }else{
                var prdListitemSingle = '<div class="xans-record-">' + title + itemContent + '</div>';
            }
            productListitemAppend = productListitemAppend + prdListitemSingle;

            
            
            
            if(itemTitleStripTag == '상품필터값'){
                var prdChk = product_no;
                var filterChkArrayTEST = new Array(); 
                if(itemContent.indexOf('>') > -1){
                    var itemContentStripTag = itemContent.split('>')[1];
                    if(itemContent.indexOf('<') > -1){
                        itemContentStripTag = itemContentStripTag.split('<')[0];
                    }
                }
                var filterValue = itemContentStripTag;
                if(filterValue.length > 0){
                    if(filterValue.indexOf(',')){
                        filterHash = itemContentStripTag.replace(/,/g, '#');
                        filterHash= '#' + filterHash;
                        var filterChk = filterValue.split(',');
                    }else{
                    	filterHash= '#' + filterHash;
                    }
                    if(Array.isArray(filterChk)){
                        filterChk.forEach(function(el, idx){
                            el = el.trim();
                            if(el.length == 0){
                                filterChk.splice(idx,1);
                            }
                        });
                        filterChkArrayTEST = filterChk;
                    }else{
                        filterChkArrayTEST.push(filterValue);
                    }
                    filter = filterChkArrayTEST;
                    //filterChkArrayTESTAll.push(test);
                }else{
                    filter = [];
                }
            }else{

            }

            if(itemTitleStripTag == '커스텀판매가'){
                if(itemContent.indexOf('>') > -1){
                    var itemContentStripTag = itemContent.split('>')[1];
                    if(itemContent.indexOf('<') > -1){
                        itemContentStripTag = itemContentStripTag.split('<')[0];
                    }
                }
                setCustomPrice = Number(uncomma(itemContentStripTag));
            }

            
            if(itemTitleStripTag == '세트소비자가'){
                if(itemContent.indexOf('>') > -1){
                    var itemContentStripTag = itemContent.split('>')[1];
                    if(itemContent.indexOf('<') > -1){
                        itemContentStripTag = itemContentStripTag.split('<')[0];
                    }
                }
                setCustomPrice2 = Number(uncomma(itemContentStripTag));
            }

        }
    });

    if(product_price == ''){
    	product_price = 0;
        priceWrap = '<p class="price">' + comma(product_price) + '</p>';
    }else{
        if(setCustomPrice == 0){
            if(typeof origin_prd_price_sale == 'undefined'){
                origin_prd_price_sale = '';
                var promotion_prc = '<p class="sale_price displaynone">'+ origin_prd_price_sale +'</p>';
            }else{
                var promotion_prc = '<p class="sale_price">'+ comma(origin_prd_price_sale) +'</p>';
                if(origin_prd_price_sale < product_price){
                    ori_price = '<p class="price strike mPriceStrike">' + comma(product_price) + '</p>';
                    discount = Math.round(((Number(product_price) - Number(origin_prd_price_sale)) / Number(product_price)) * 100);
                }
            }
            if(product_custom == '' || product_custom == null){
                product_custom = '0';
                var custom_prc = '<p class="custom_price displaynone">'+ product_custom +'</p>';

                if(setCustomPrice2 > 0){
                    if(product_price < setCustomPrice2){
                        var custom_prc = '<p class="custom_price strike mPriceStrike">'+ comma(setCustomPrice2) +'</p>';
                        discount = Math.round(((Number(setCustomPrice2) - Number(product_price)) / Number(setCustomPrice2)) * 100);
                    }else{
                        var custom_prc = '<p class="custom_price displaynone">'+ comma(setCustomPrice2) +'</p>';
                    }
                }
            }else{
                if(product_price < product_custom){
                    var custom_prc = '<p class="custom_price strike mPriceStrike">'+ comma(product_custom) +'</p>';
                    discount = Math.round(((Number(product_custom) - Number(product_price)) / Number(product_custom)) * 100);
                }else{
                    var custom_prc = '<p class="custom_price displaynone">'+ comma(product_custom) +'</p>';
                }
            }
            var setCustom_prc = '<p class="setCustomPrice displaynone">'+ 0 +'</p>';
        }else{
            if(setCustomPrice < product_price){
                ori_price = '<p class="price strike mPriceStrike">' + comma(product_price) + '</p>';
                discount = Math.round(((Number(product_price) - Number(setCustomPrice)) / Number(product_price)) * 100);
            }
            var promotion_prc = '<p class="sale_price displaynone">'+ 0 +'</p>';
            var custom_prc = '<p class="custom_price displaynone">'+ 0 +'</p>';
            var setCustom_prc = '<p class="setCustomPrice">'+ comma(setCustomPrice) +'</p>';
        }

        if(discount != 0){
            var discountP = '<p class="discount">'+ discount+'%</p>';
        }else{
            var discountP = '';
        }
        priceWrap = custom_prc + setCustom_prc + ori_price + promotion_prc + discountP;
    }



    
    
    
    

    prdItem += '<div class="complete container ' + item_class +'" data-prd-no="'+ product_no +'" data-filter="'+filterHash+'"><dl><a href="/product/detail.html'+ param +'" class="viewlink"></a>';
    prdItem += '<div class="base_img">'+ best_review_icon;
    prdItem += '<div class="thumb"><img loading="lazy" class="*lazyload thumb_img" data-original="" data-src="'+ image_medium +'" alt="" width="800" height="800">';
    prdItem += '<img loading="lazy" decoding ="async" class="*lazyload hover_img" data-original="" data-src="'+ image_big +'" alt="" width="800" height="800">';
    prdItem += '<div class="sticker"><div class="new">NEW</div><div class="percent"><div class="dcPercent"></div></div><div class="best">BEST</div></div>'+ soldout_img +'</div></div>';
    prdItem += '<div class="base_mask"><dd class="info_container"><p class="name">'+ product_name +'</p><p class="subname">'+ summary_desc +'</p><p class="subnameSimple">'+ simple_desc +'</p></dd>';
    prdItem += '<dd class="soldout_container" style="display: none;"><p class="soldout">(품절)</p></dd>';
    prdItem += '<dd class="price_container">'+ priceWrap +'</dd>';
    prdItem += '<dd class="icons '+ iconStyle +'">'+ icons +'</dd><div class="prdInfo_bottom">';
    prdItem += '<div class="crema_container"><div class="crema_wrap"><p class="rv_value"><span class="crema-product-reviews-score" data-product-code="'+ product_no +'" data-star-style="single" data-format="{{{stars}}} {{{score}}}" data-hide-if-zero="1"></span></p>';
    prdItem += '<p class="rv_count"><span class="rv_icon"><img src="/web/upload/rv_icon2.png"></span><span class="count crema-product-reviews-count" data-product-code="'+ product_no +'" data-format="{{{count}}}" data-hide-if-zero="1">0</span></p>';
    prdItem += '</div></div></div><div class="hash_container done"><div class="hash_wrap">'+ hash_wrap +'</div></div><div class="Prev_Cart" onclick="basketConfirmShow(this);">'+ basket_icon +'</div>';
    prdItem += '<div class="rv_icon"><a href="/product/detail.html'+ param +'"><img src="/web/upload/rv_icon1.png"><span class="count crema-product-reviews-count" data-product-code="'+ product_no +'" data-format="{{{count}}}" data-hide-if-zero="1">0</span>';
    prdItem += '</a></div><div class="only_info_chk displaynone"><div class="xans-element- xans-product xans-product-listitem">'+ productListitemAppend +'</div></div><div class="restockIcon"></div></div></dl></div>';

    var prdResult = {item: prdItem, prdNo: product_no, soldout: soldoutChk};
    return prdResult;
}


//썸네일 이미지 lazyload
function thumbnailCallFunc(el){
    if(el.hasClass('load_complete') == false){
        setTimeout(function(){
            el.find('.thumb img').each(function(){
                var dataSrc = $(this).data('src');
                if(typeof dataSrc != 'undefined'){
                    $(this).attr('src',dataSrc);
                    $(this).removeAttr('data-src');
                    el.addClass('load_complete');
                }
            });
        },100);
    }else{
        return false;
    }
}

cate_width();
//헤더 카테고리 width 값 구하기
function cate_width(){
    $('.prd_cate_wrap > li').each(function(){
        var array_list = [];
        var cate_sub_li = 	$(this).find('.prd_cate_sub > li');
        var length = cate_sub_li.length;

        for ( var i = 0; i < length ; i++){
            var width = $(cate_sub_li).eq(i).width();
            array_list.push(width);
        }
        var max = getMax(array_list);
        //console.log(max);
        $(cate_sub_li).css('min-width',max + 5);
    });
}
//헤더 카테고리의 width 값 중 최댓값 
function getMax(ary) {
    if ( ary.length > 0){
        var max = ary[0];
        for(var i = 1; i < ary.length; i++) {
            if(max < ary[i])
                max = ary[i];
        }
        return max;
    }
}

//이미지 preloading
function preloading (imageArray) { 
    let n = imageArray.length; 
    for (let i = 0; i < n; i++) { 
        if(typeof imageArray[i] != 'undefined'){
            let img = new Image(); 
            img.src = imageArray[i]; 
        }
    } 
}

if($('.prd_list_container').length == 0){
    var CAPP_SHOP_NEW_PRODUCT_OPTIONSELECT = {
        sLayerID: 'capp-shop-new-product-optionselect-layer',
        sBackLayerID: 'capp-shop-new-product-optionselect-backlayer',
        sIframeID: 'capp-shop-new-product-optionselect-iframe',
        iProductNo: 0,
        iCategoryNo: 0,
        sActionType: '',
        sIsMobile: '',
        selectOptionCommon: function(iProductNo, iCategoryNo, sActionType, sIsMobile)
        {
            this.iProductNo = iProductNo;
            this.sActionType = sActionType;
            this.iCategoryNo = iCategoryNo;
            this.sIsMobile = sIsMobile;
            this.createLayer();
        },

        createLayer: function()
        {
            if (this.sIsMobile) {
                var container = '<div id="'+this.sLayerID+'" style="position:fixed;z-index:10001;display:block;top:50px;left:0px;width:100%;height:630px;"><iframe id="'+this.sIframeID+'" scroll="0" scrolling="no" frameBorder="0"  style="height:100%;width:100%;"></iframe></div>';
                EC$('body').append(EC$(container));
            } else {
                var container = '<div id="'+this.sLayerID+'" style="position:absolute;z-index:10001;display:block;width:600px;height:630px;"><iframe id="'+this.sIframeID+'" scroll="0" scrolling="no" frameBorder="0" style="height:100%;width:100%;background: transparent;" allowtransparency="true"></iframe></div>';
                EC$('body').append(EC$('<div id="' + this.sBackLayerID +'" style="position:absolute;top:0px;left:0px;z-index:10000;"></div>')).append(EC$(container));

                EC$('#' + this.sBackLayerID).on('click', function() {
                    CAPP_SHOP_NEW_PRODUCT_OPTIONSELECT.closeOptionCommon();
                });

                EC$('#' + this.sBackLayerID).css({width: EC$("body").width(),height: EC$("body").height(),opacity: .4}).show();
            }

            var url = '/product/basket_option.html?product_no=' + this.iProductNo + '&sActionType=' + this.sActionType + '&cate_no=' + this.iCategoryNo;

            EC$('#' + this.sIframeID).attr('src', url);
            EC$('#' + this.sIframeID).on("load",function() {
                EC$(".close",this.contentWindow.document.body).on("click", function() {
                    CAPP_SHOP_NEW_PRODUCT_OPTIONSELECT.closeOptionCommon();
                });
            });

            CAPP_SHOP_NEW_PRODUCT_OPTIONSELECT.centerLayer();
            EC$('#' + this.sLayerID).show();
        },

        closeOptionCommon: function()
        {
            EC$('div').remove('#' + this.sBackLayerID);
            EC$('#' + this.sIframeID).remove();
            EC$('div').remove('#' + this.sLayerID);
        },

        centerLayer: function() {
            var oThis = EC$('#' + this.sLayerID);
            var oWindow = EC$(window);
            oThis.css({
                position: "absolute",
                top: ~~((oWindow.height() - oThis.outerHeight()) / 2) + oWindow.scrollTop() + "px",
                left: '50%',
                'margin-left': ((oThis.outerWidth() / 2) * -1)+'px'
            });
            return this;
        }
    };
}

$(document).ready(function(){
    if (typeof(EC_SHOP_MULTISHOP_SHIPPING) != "undefined") {
        var sShippingCountryCode4Cookie = 'shippingCountryCode';
        var bShippingCountryProc = false;

        // 배송국가 선택 설정이 사용안함이면 숨김
        if (EC_SHOP_MULTISHOP_SHIPPING.bMultishopShippingCountrySelection === false) {
            $('.xans-layout-multishopshipping .xans-layout-multishopshippingcountrylist').hide();
            $('.xans-layout-multishoplist .xans-layout-multishoplistmultioption .xans-layout-multishoplistmultioptioncountry').hide();
        } else {
            $('.thumb .xans-layout-multishoplistitem').hide();
            var aShippingCountryCode = document.cookie.match('(^|;) ?'+sShippingCountryCode4Cookie+'=([^;]*)(;|$)');
            if (typeof(aShippingCountryCode) != 'undefined' && aShippingCountryCode != null && aShippingCountryCode.length > 2) {
                var sShippingCountryValue = aShippingCountryCode[2];
            }

            // query string으로 넘어 온 배송국가 값이 있다면, 그 값을 적용함
            var aHrefCountryValue = decodeURIComponent(location.href).split("/?country=");

            if (aHrefCountryValue.length == 2) {
                var sShippingCountryValue = aHrefCountryValue[1];
            }

            // 메인 페이지에서 국가선택을 안한 경우, 그 외의 페이지에서 셋팅된 값이 안 나오는 현상 처리
            if (location.href.split("/").length != 4 && $(".xans-layout-multishopshipping .xans-layout-multishopshippingcountrylist").val()) {
                $(".xans-layout-multishoplist .xans-layout-multishoplistmultioption a .ship span").text(" : "+$(".xans-layout-multishopshipping .xans-layout-multishopshippingcountrylist option:selected").text().split("SHIPPING TO : ").join(""));

                if ($("#f_country").length > 0 && location.href.indexOf("orderform.html") > -1) {
                    $("#f_country").val($(".xans-layout-multishopshipping .xans-layout-multishopshippingcountrylist").val());
                }
            }
            if (typeof(sShippingCountryValue) != "undefined" && sShippingCountryValue != "" && sShippingCountryValue != null) {
                sShippingCountryValue = sShippingCountryValue.split("#")[0];
                var bShippingCountryProc = true;

                $(".xans-layout-multishopshipping .xans-layout-multishopshippingcountrylist").val(sShippingCountryValue);
                $(".xans-layout-multishoplist .xans-layout-multishoplistmultioption a .ship span").text(" : "+$(".xans-layout-multishopshipping .xans-layout-multishopshippingcountrylist option:selected").text().split("SHIPPING TO : ").join(""));
                var expires = new Date();
                expires.setTime(expires.getTime() + (30 * 24 * 60 * 60 * 1000)); // 30일간 쿠키 유지
                document.cookie = sShippingCountryCode4Cookie+'=' + $(".xans-layout-multishopshipping .xans-layout-multishopshippingcountrylist").val() +';path=/'+ ';expires=' + expires.toUTCString();
                if ($("#f_country").length > 0 && location.href.indexOf("orderform.html") > -1) {
                    $("#f_country").val(sShippingCountryValue).change();;
                }
            }
        }
        // 언어선택 설정이 사용안함이면 숨김
        if (EC_SHOP_MULTISHOP_SHIPPING.bMultishopShippingLanguageSelection === false) {
            $('.xans-layout-multishopshipping .xans-layout-multishopshippinglanguagelist').hide();
            $('.xans-layout-multishoplist .xans-layout-multishoplistmultioption .xans-layout-multishoplistmultioptionlanguage').hide();
        } else {
            $('.thumb .xans-layout-multishoplistitem').hide();
        }

        // 배송국가 및 언어 설정이 둘 다 사용안함이면 숨김
        if (EC_SHOP_MULTISHOP_SHIPPING.bMultishopShipping === false) {
            $(".xans-layout-multishopshipping").hide();
            $('.xans-layout-multishoplist .xans-layout-multishoplistmultioption').hide();
        } else if (bShippingCountryProc === false && location.href.split("/").length == 4) { // 배송국가 값을 처리한 적이 없고, 메인화면일 때만 선택 레이어를 띄움
            var sShippingCountryValue = $(".xans-layout-multishopshipping .xans-layout-multishopshippingcountrylist").val();
            $(".xans-layout-multishopshipping .xans-layout-multishopshippingcountrylist").val(sShippingCountryValue);
            $(".xans-layout-multishoplist .xans-layout-multishoplistmultioption a .ship span").text(" : "+$(".xans-layout-multishopshipping .xans-layout-multishopshippingcountrylist option:selected").text().split("SHIPPING TO : ").join(""));
            // 배송국가 선택을 사용해야 레이어를 보이게 함
            if (EC_SHOP_MULTISHOP_SHIPPING.bMultishopShippingCountrySelection === true) {
                $(".xans-layout-multishopshipping").show();
            }
        }

        $(".xans-layout-multishopshipping .close").bind("click", function() {
            $(".xans-layout-multishopshipping").hide();
        });

        $(".xans-layout-multishopshipping .ec-base-button a").bind("click", function() {
            var expires = new Date();
            expires.setTime(expires.getTime() + (30 * 24 * 60 * 60 * 1000)); // 30일간 쿠키 유지
            document.cookie = sShippingCountryCode4Cookie+'=' + $(".xans-layout-multishopshipping .xans-layout-multishopshippingcountrylist").val() +';path=/'+ ';expires=' + expires.toUTCString();

            // 도메인 문제로 쿠키로 배송국가 설정이 안 되는 경우를 위해 query string으로 배송국가 값을 넘김
            var sQuerySting = (EC_SHOP_MULTISHOP_SHIPPING.bMultishopShippingCountrySelection === false) ? "" : "/?country="+encodeURIComponent($(".xans-layout-multishopshipping .xans-layout-multishopshippingcountrylist").val());

            location.href = '//'+$(".xans-layout-multishopshipping .xans-layout-multishopshippinglanguagelist").val()+sQuerySting;
        });
        $(".xans-layout-multishoplist .xans-layout-multishoplistmultioption a").bind("click", function() {
            $(".xans-layout-multishopshipping").show();
        });
    }
});
var mainContainer = $('.main_container'); //메인페이지
var instagramContainer = $('.instagram_container'); //인스타그램페이지
var eventContainer = $('.event_instagram'); //이벤트페이지

var arrInsta = [];
var itemID = 0;
var startNum = 0;
var callStop = false;

var instaItemsswiper = '';
function initSwiper(){
    instaItemsswiper = new Swiper('.insta_prd_wrapper', {
        slidesPerView: 2.5,
        slidesPerGroup: 1,
        spaceBetween: 20
    });
};

// 메인페이지
if (mainContainer.length > 0) {
    callStop = true;
    $(".insta_review_wrap").addClass("swiper-container");
    $(".insta_review_wrap .items").addClass("swiper-wrapper");

    jQuery1_11_2.ajax({
        url: 'https://www.devohora.kr/api/main',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if(!!data){

                $(".insta_reivew .insta_review_wrap .items").empty();

                jQuery1_11_2.each(data.data, function(idx, item){

                    arrInsta.push(item);

                    var html = '';
                    var thumbSrc = item.main_img;
                    var instaID = item.account;
                    var stDate = item.date;
                    var endDate = dateToday();
                    var dateDiff = betweenDateFunc2(stDate, endDate); // 게시 경과기간
                    
                    if (dateDiff == 0) {
                    	var instaDate = "today";
                    } else {
                    	var instaDate = dateDiff + " days ago";
                    }
                    
                    html += '<div class="insta_review_li swiper-slide" data-id="'+ item.id +'"><a href="#none"><div class="insta_img_wrap">';
                    html += '<div class="thumb_img"><img src="'+ thumbSrc +'" alt="인스타그램 이미지" class="insta_img"></div>'
                    html += '<div class="text_area">'
                    html += '<span class="insta_id"><img src="/web/upload/common/ico_insta_w.png" alt="인스타그램"> @'+ instaID +'</span>'
                    html += '<span class="insta_date">' + instaDate + ' </span>'
                    html += '</div></div></a></div>'
                    $(".insta_reivew .insta_review_wrap .items").append(html);
                });
            };
        },
        error: function(){
            console.log('error');
        },
        complete: function(){            
            // 메인페이지 슬라이드
            var InstaReviewswiper = new Swiper('.insta_review_wrap', {
                slidesPerView: 3,
                slidesPerColumn: 2,
                slidesPerGroup:3,
                spaceBetween: 15,
                navigation: {
                    nextEl: ".insta_section .swiper-button-next",
                    prevEl: ".insta_section .swiper-button-prev",
                },
                scrollbar: {
                    el: ".insta_section .swiper-scrollbar",
                },
                preloadImages: false,
                // Enable lazy loading
                lazy: {
                    loadPrevNext: true,
                    loadPrevNextAmount: 1,
                    loadOnTransitionStart: true
                },
            });
        }
    });
}



// 인스타그램 페이지
if (instagramContainer.length > 0) {
    var cntStart = 30;
    var cntCall = 15;
    var feedLength = 0;
    
    function instalistCallFunc(cntItem){
    	jQuery1_11_2.ajax({
            url: 'https://www.devohora.kr/api/list?start='+ startNum +'&length='+ cntItem,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(!!data){
                    startNum = startNum + cntItem;
                    feedLength = feedLength + cntItem;

                    var total = data.total; // 총 피드 개수                    

                    jQuery1_11_2.each(data.data, function(idx, item){

                        arrInsta.push(item);

                        var html = '';
                        var thumbSrc = item.main_img;
                        var instaID = item.account;
                        var stDate = item.date;
                        var endDate = dateToday();
                        var dateDiff = betweenDateFunc2(stDate, endDate); // 게시 경과기간
                        if (dateDiff == 0) {
                            var instaDate = "today";
                        } else {
                            var instaDate = dateDiff + " days ago";
                        }

                        html += '<div class="insta_review_li" data-id="'+ item.id +'"><a href="#none"><div class="insta_img_wrap">';
                        html += '<div class="thumb_img"><img src="'+ thumbSrc +'" alt="인스타그램 이미지" class="insta_img"></div>'
                        html += '<div class="text_area">'
                        html += '<span class="insta_id"><img src="/web/upload/common/ico_insta_w.png" alt="인스타그램"> @'+ instaID +'</span>'
                        html += '<span class="insta_date">'+ instaDate +'</span>'
                        html += '</div></div></a></div>'
                        if($('.instagram_container .insta_review_wrap .items > .insta_review_li[data-id="'+ item.id +'"]').length == 0){
                            $(".instagram_container .insta_review_wrap .items").append(html);
                        }
                    });
                };
                
                if(feedLength >= total) {
                	$('.btn_wrap .more_btn').hide();
                    callStop = true;
                } else {
                	$('.btn_wrap .more_btn').show();
                }
            },
            error: function(){
                console.log('error');
            },
            complete: function(){
                // more(더보기) 버튼 보이기
				$(".instagram_container .btn_wrap").show();
            }
        });
    }
}



// 이벤트 페이지
if (eventContainer.length > 0) {
    var cntStart = 6;
    var cntCall = 4;
    var feedLength = 0;
    
    function eventlistCallFunc(cntItem){
        jQuery1_11_2.ajax({
            url: 'https://www.devohora.kr/api/event?start='+ startNum +'&length='+ cntItem,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(!!data){
                    startNum = startNum + cntItem;
                    feedLength = feedLength + cntItem;

                    var total = data.total; // 총 피드 개수

                    jQuery1_11_2.each(data.data, function(idx, item){

                        arrInsta.push(item);

                        var html = '';
                        var thumbSrc = item.main_img;
                        var instaID = item.account;
                        var stDate = item.date;
                        var endDate = dateToday();
                        var dateDiff = betweenDateFunc2(stDate, endDate); // 게시 경과기간
                        var instaContent = item.content;
                        instaContent = instaContent.replace(/\n/g, '<br>')

                        if (dateDiff == 0) {
                            var instaDate = "today";
                        } else {
                            var instaDate = dateDiff + " days ago";
                        }

                        html += '<div class="insta_review_li" data-id="'+ item.id +'"><a href="#none"><div class="insta_img_wrap">';
                        html += '<div class="thumb_img"><img src="'+ thumbSrc +'" alt="인스타그램 이미지" class="insta_img"></div>'
                        html += '<div class="text_area">'
                        html += '<span class="insta_id"><img src="/web/upload/common/ico_insta_w.png" alt="인스타그램"> @'+ instaID +'</span>'
                        html += '<span class="insta_date">'+ instaDate +'</span>'
                        html += '</div></div>'
                        html += '<div class="insta_cont_wrap">'
                        html += '<div class="text_area">'
                        html += '<span class="insta_id"><img src="/web/upload/common/ico_insta_w.png" alt="인스타그램"> @'+ instaID +'</span>'
                        html += '<span class="insta_date">'+ instaDate +'</span>'
                        html += '</div>'
                        html += '<p>'+ instaContent +'</p>'
                        html += '</div></a></div>'
                        if($('.event_instagram .insta_review_wrap .items > .insta_review_li[data-id="'+ item.id +'"]').length == 0){
                            $(".event_instagram .insta_review_wrap .items").append(html);
                        }
                        if(feedLength >= total) {
                            $('.event_instagram .btn_wrap').hide();
                            callStop = true;
                        } else {
                            $('.event_instagram .btn_wrap').show();
                        }
                    });
                };
            },
            error: function(){
                console.log('error');
            },
            complete: function(){
                // more(더보기) 버튼 보이기
                $(".event_instagram .btn_wrap .more_btn").show();
            }
        });
    }
}



$(document).ready(function(){
    if(instagramContainer.length > 0) {
        instalistCallFunc(cntStart);
        // more(더보기) 버튼 클릭 시 다음 목록 호출
        jQuery1_11_2('.btn_wrap .more_btn').on('click', function(){
			instalistCallFunc(cntCall);
        });
    } else if (eventContainer.length > 0) {
    	eventlistCallFunc(cntStart);
        // more(더보기) 버튼 클릭 시 다음 목록 호출
        jQuery1_11_2('.btn_wrap .more_btn').on('click', function(){
			eventlistCallFunc(cntCall);
        });
    }
    
    if(eventContainer.length > 0) {
        
    } else {
    	// 리스트에서 아이템 눌렀을 때 팝업창 띄우기
        jQuery1_11_2(document).on('click', ".insta_review_li", function(){            
            var $this = $(this);
            itemID = $this.data('id');
            popupCallFunc(itemID);
            $('html').css('overflow-y', 'hidden');
            $(".insta_review_popup").css('display','flex');
        });

        // 팝업 내 이전 버튼 눌렀을 때 이전 아이템 내용 호출
        jQuery1_11_2('.insta_review_popup .prev_btn').on('click', function(){            
            var itemFind  = itemID;
            arrInsta.forEach(function(el,idx){
                if(el.id == itemFind){
                    var prevIdx = idx-1;
                    if(typeof arrInsta[prevIdx] != 'undefined'){
                        instaItemsswiper.destroy(); // 관련디자인 스와이프 기능 오류로 destroy 시켜줌
                        itemID = Number(arrInsta[prevIdx].id);
                        popupCallFunc(itemID);
                    }else{
                        alert("이전 내용이 없습니다.");
                    }
                }
            });
        });
        
        // 팝업 내 다음 버튼 눌렀을 때 다음 아이템 내용 호출
        jQuery1_11_2(".insta_review_popup .next_btn").on('click', function(){
            
            
            var itemFind = itemID;
            arrInsta.forEach(function(el,idx){
                if(el.id == itemFind){
                    var nextIdx = idx+1;
                    if(typeof arrInsta[nextIdx] != 'undefined'){
                        instaItemsswiper.destroy(); // 관련디자인 스와이프 기능 오류로 destroy 시켜줌
                        itemID = Number(arrInsta[nextIdx].id);
                        popupCallFunc(itemID);
                    } else {
                        if(! callStop){
                            instaItemsswiper.destroy(); // 관련디자인 스와이프 기능 오류로 destroy 시켜줌
                            if(instagramContainer.length > 0) {
                            	instalistCallFunc(cntStart);
                            } else {
                            	eventlistCallFunc(cntStart);
                            }    
                        } else {
                            alert("다음 내용이 없습니다.");
                        }
                    }
                };
            });
        });
    }
    
    // 팝업 창 닫기
    jQuery1_11_2(document).on('click', ".insta_review_popup .close", function(){
        instaItemsswiper.destroy(); // 관련디자인 스와이프 기능 오류로 destroy 시켜줌
        $(".insta_review_popup").hide();
        $('html').css('overflow-y', 'auto');
    });
    
    // instagram/index.html 상단배너 슬라이드
    if (instagramContainer.length > 0) {
        var bannerSlide = $('.insta_banner_wrap .swiper-slide');
        if(bannerSlide.length > 1) {
            var instaBannerswiper = new Swiper('.insta_banner_wrap', {
                autoplay: {
                    delay: 2500,
                },
            });
        }
    }
});

// 팝업 불러오는 함수
function popupCallFunc(itemID){
	var popupContent = $('.insta_review_popup');
    var popupContentImg = popupContent.find('.insta_img_wrap .thumb_img img');
    var popupContentId = popupContent.find('.insta_img_wrap .insta_id');
    var popupContentDate = popupContent.find('.insta_img_wrap .insta_date');
    var relationPrdArea = $('.insta_prd_wrapper');
    
    //클릭했을때, 필요한 영역 비워둠
    popupContentImg.attr('src','');
    popupContentId.empty();
    relationPrdArea.empty();
    
    if(arrInsta.length > 0){
    	jQuery1_11_2.each(arrInsta, function(idx, item){
            
        	if(itemID == item.id){
                var stDate = item.date;
                var endDate = dateToday();
                var dateDiff = betweenDateFunc2(stDate, endDate); // 게시 경과기간
                if (dateDiff == 0) {
                    var instaDate = "today";
                } else {
                    var instaDate = dateDiff + " days ago";
                }
                
            	popupContentImg.attr('src',item.main_img);
                popupContentImg.attr('alt',item.content);
                popupContentId.text('@' + item.account);
                popupContentDate.text(instaDate);
                
                var relationPrd = item.Products;
                
                
                if(relationPrd.length > 0){
                    relationPrdArea.empty();
                	//$('.insta_prd_wrapper').addClass('swiper-container');
                    $('.insta_prd_wrapper').append('<ul class="swiper-wrapper"></ul>');
                    
                    jQuery1_11_2.each(relationPrd, function(idx, item){
                    	var html = '';
                        
                        var relationPrdLinkHead = '/product/detail.html?product_no=';
                        var relationPrdLink = relationPrdLinkHead + item.id;
                        
                        html += '<li class="swiper-slide"><a href="'+ relationPrdLink +'">';
                        html += '<img src="'+ item.image +'" alt="'+ item.product_name +'" class="item_img">';
                        html += '<p class="item_name">'+ item.product_name +'</p>';
                    	html += '</a></li>';
                        
                        relationPrdArea.find('.swiper-wrapper').append(html);
                    });
                    
                    
                    
                } else {
                	relationPrdArea.append('<p class="no_relation">관련디자인이 없습니다.</p>')
                }
            }
        });
        
        initSwiper();
    }
}


//기간 계산 함수
function betweenDateFunc2(stDate, endDate){
    var ar1 = stDate.split('-'); // 
    var ar11 = ar1[2].split(' '); // ar1의 일(day), 시간이 붙어있는 것을 분리
    var ar2 = endDate.split('-');
    var da1 = new Date(ar1[0], ar1[1], ar11[0]);
    var da2 = new Date(ar2[0], ar2[1], ar2[2]);
    var dif = da2 - da1;
    var cDay = 24 * 60 * 60 * 1000;// 시 * 분 * 초 * 밀리세컨
    
    var dateDiff = parseInt(dif/cDay);
    
    return dateDiff;
}















