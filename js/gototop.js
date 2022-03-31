;
(function(win, doc){
    win.sUI = {}; // my ui option list
    sUI.goUp = function(){
        var vendors = ['webkit', 'moz'];
        for(var x = 0; x < vendors.length && !win.requestAnimationFrame; ++x) {
            win.requestAnimationFrame = win[vendors[x]+'RequestAnimationFrame'];
            win.cancelAnimationFrame = win[vendors[x]+'CancelAnimationFrame'] || win[vendors[x]+'CancelRequestAnimationFrame'];
        }

        var lastTime = 0;
        if (!win.requestAnimationFrame)
            win.requestAnimationFrame = function(callback, element) {
                var currTime = new Date().getTime();
                var timeToCall = Math.max(0, 16 - (currTime - lastTime));
                var id = win.setTimeout(function() { callback(currTime + timeToCall); }, timeToCall);
                lastTime = currTime + timeToCall;
                return id;
            };

        if (!win.cancelAnimationFrame)
            win.cancelAnimationFrame = function(id) {clearTimeout(id);};

        // top button
        var btnTop = doc.querySelector('.btn-top');
        var domHtml = doc.documentElement; // html over IE5
       // var scrollDuration = 500; // set scroll time

        // toggle top button
        var scrollFunction = function(){
            if(domHtml.scrollTop > 100 || doc.body.scrollTop  > 100){
                btnTop.style.display = 'block';
            }else{
                btnTop.style.display = 'none';
            }
        }

        // when scrollbar is scrolled, all browser are work.
        //win.onscroll = function(){scrollFunction()};
        win.addEventListener('scroll', scrollFunction)

        // go to top of page
        var scrollToTop = function(e) {
            event.preventDefault ? event.preventDefault() : (event.returnValue=false);
            var t = 500;
            console.log(t)
            console.log(this)
            // cancel if already on top
            if (domHtml.scrollTop === 0) return;

            var totalScrollDistance = domHtml.scrollTop;
            var scrollY = totalScrollDistance, oldTimestamp = null;

            function step (newTimestamp) {
            console.log(scrollY)
                // callback method is passed a single argument, a OMHighResTimeStamp
                // indicate current time
                if (oldTimestamp !== null) {
                    // if duration is 0 scrollY will be -Infinity
                    scrollY -= totalScrollDistance * (newTimestamp - oldTimestamp) / t; // t or scrollDuration

                    if (scrollY <= 0) return domHtml.scrollTop = 0;
                    domHtml.scrollTop = scrollY;
                }
                oldTimestamp = newTimestamp;
                win.requestAnimationFrame(step); // over IE10
            }
            win.requestAnimationFrame(step);
        }

        // execute scrollToTop function, go to top of page, when top button is clicked.
        btnTop.addEventListener('click', scrollToTop)
    };
    sUI.goUp();

    sUI.fullPage = function(param){
        'use strict';

        var option = {
            container: '#fullPage',
            pageClassName: '.section',
            easing: 'easeInOutQuart',
            duration: 1000
        };
        // easing option
        // easeInOutQuart, easeInOutCubic

        // get fullPage ID
        if(typeof param === "object"){
            console.log('object')
            option = {
                container: param.container,
                pageClassName: param.pageClassName,
                easing: param.easing,
                duration: param.duration
            };
        }else if(typeof param === "string"){
            console.log('string')
            option.conatiner = param;
        }else{
            console.log('empty')
        }

        var setup =  {
            winW: 0,
            winH: 0,
            container: undefined,
            page: undefined,
            pageLength: 0,
            marginTop: 0
        };


        // SETUP
        function getSetup(){
            setup.winW = win.innerWidth;
            setup.winH = win.innerHeight;
            setup.container = doc.querySelector(option.container);
            setup.page = doc.querySelectorAll(option.pageClassName);
            setup.page[0].classList.add('active');
            setup.pageLength = setup.page.length;
            setup.container.style.marginTop = setup.marginTop + 'px';
        }

        // ACTION
        function moveTo(from, to){
            var start = new Date().getTime();

            var timer = setInterval(function() {
                var time = new Date().getTime() - start;
                var mt = easing[option.easing](time, from, to - from, option.duration);
                setup.container.style.marginTop = Math.round(mt) + 'px';

                if (time >= option.duration) clearInterval(timer);
            }, 1000 / 60);
        }

        //
        // http://easings.net/#easeInOutQuart
        //  t: current time
        //  b: beginning value
        //  c: change in value
        //  d: duration
        //
        var easing = {}
        easing.easeInOutQuart = function (t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t * t * t + b;
            return -c / 2 * ((t -= 2) * t * t * t - 2) + b;
        }
        easing.easeInOutCubic = function (t, b, c, d) {
            if ((t/=d/2) < 1) return c/2*t*t*t + b;
            return c/2*((t-=2)*t*t + 2) + b;
        };

        // EVENT handler
        var wheelDeltaY; //'up' || 'down'
        var pageIndex = 0;
        var prevMarginTop = setup.marginTop;
        function getDeltaY(deltaY){
            var prevPageIndex = pageIndex;
            if(deltaY > 0 ){
                wheelDeltaY = 'up';
                if(pageIndex < setup.pageLength-1){
                    console.log(setup.pageLength)
                    ++pageIndex;
                }
            }else{
                wheelDeltaY = 'down';
                if(pageIndex > 0 && pageIndex < setup.pageLength){
                    --pageIndex;
                }
            }
            setup.page[prevPageIndex].classList.remove('active');
            setup.page[pageIndex].classList.add('active');

            setup.marginTop = pageIndex * -setup.winH;
            moveTo(prevMarginTop, setup.marginTop);
            prevMarginTop = setup.marginTop;
        }

        var listeningWheelEvent = true;
        var prevTime = 0;
        function wheelHandler(e){
            var curTime = new Date().getTime();

            if(curTime - prevTime < option.duration){
                listeningWheelEvent = false;
                return false;
            }else{
                listeningWheelEvent = true;
                getDeltaY(e.deltaY);
            }
            prevTime = curTime;
        }


        // BIND EVENTS, pointer
        function bindContainerPointer(){
            win.addEventListener('wheel', wheelHandler);
        }

        // UNBIND EVENTS
        function unbindWheelEvent(){
            win.removeEventListener('wheel', wheelHandler)
        }

        // DESTROY
        function destroy(){
            unbindWheelEvent();
        }
        // INIT
        function init(){
            getSetup();
            bindContainerPointer();
        }
        init();
    };
})(window, document);