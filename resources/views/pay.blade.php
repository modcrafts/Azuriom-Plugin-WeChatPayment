@extends('layouts.app')

@section('title', '微信支付')

@section('content')
    <style>
        /*! normalize.css v4.2.0 | MIT License | github.com/necolas/normalize.css */button,hr,input{overflow:visible}audio,canvas,progress,video{display:inline-block}progress,sub,sup{vertical-align:baseline}[type=checkbox],[type=radio],legend{box-sizing:border-box;padding:0}html{font-family:sans-serif;line-height:1.15;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0}article,aside,details,figcaption,figure,footer,header,main,menu,nav,section{display:block}h1{font-size:2em;margin:.67em 0}figure{margin:1em 40px}hr{box-sizing:content-box;height:0}code,kbd,pre,samp{font-family:monospace,monospace;font-size:1em}a{background-color:transparent;-webkit-text-decoration-skip:objects}a:active,a:hover{outline-width:0}abbr[title]{border-bottom:none;text-decoration:underline;text-decoration:underline dotted}b,strong{font-weight:bolder}dfn{font-style:italic}mark{background-color:#ff0;color:#000}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative}sub{bottom:-.25em}sup{top:-.5em}audio:not([controls]){display:none;height:0}img{border-style:none}svg:not(:root){overflow:hidden}button,input,optgroup,select,textarea{font-family:sans-serif;font-size:100%;line-height:1.15;margin:0}button,select{text-transform:none}[type=reset],[type=submit],button,html [type=button]{-webkit-appearance:button}[type=button]::-moz-focus-inner,[type=reset]::-moz-focus-inner,[type=submit]::-moz-focus-inner,button::-moz-focus-inner{border-style:none;padding:0}[type=button]:-moz-focusring,[type=reset]:-moz-focusring,[type=submit]:-moz-focusring,button:-moz-focusring{outline:ButtonText dotted 1px}fieldset{border:1px solid silver;margin:0 2px;padding:.35em .625em .75em}legend{color:inherit;display:table;max-width:100%;white-space:normal}textarea{overflow:auto}[type=number]::-webkit-inner-spin-button,[type=number]::-webkit-outer-spin-button{height:auto}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}[type=search]::-webkit-search-cancel-button,[type=search]::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}summary{display:list-item}[hidden],template{display:none}
        /* CLEARFIX */ .clearfix:after {visibility: hidden;display: block;content: "";clear: both;height: 0;}* html .clearfix { zoom: 1; } /* IE6 */*:first-child+html .clearfix { zoom: 1; } /* IE7 */
    </style>
    <style>
        *{box-sizing:border-box}body{font-family:"Segoe UI",Helvetica,"Helvetica Neue LT Std",Tahoma,Geneva,Verdana,Arial,sans-serif;font-size:.85em;background-color:#F0F0F0}.hideOverflow{overflow:hidden}.showOverflow{overflow:visible}a,a:link,a:visited{color:#08a806;text-decoration:none;padding:0 .1em}a:hover,a:link:hover{background-color:#08a806;color:#FFF}#WeChatForm{width:23em;margin:3.5em auto}.form_container{background-color:#FFF;border:.1em solid #CCC;border-radius:1em;padding:2em;box-shadow:0 3px 0 rgba(0,0,0,.1);position:relative}.wechat_logo_container{position:relative;top:-1em;text-align:center;padding-bottom:.5em}.wechat_logo_container>img{height:2.5em}.lang-en .logo_zh,.lang-zh .logo_en{display:none!important}.lang_selector_container{position:absolute;right:1.2em;top:-1.5em}.form_group{display:block;margin-bottom:1em}.form_group .label{float:left;min-width:4.6em;color:#777;text-align:right;word-break:break-all}.form_group .label:after{content:":";display:block;margin:0 .5em;color:#AAA;float:right}.form_group .value{margin-left:5em;color:#222;text-overflow:ellipsis;word-break:break-all}.form_group .value.amount{font-weight:700}#qr_code{margin-top:1.5em;padding:1em;border:1px dotted #DDD}#QRCodeImageControl,svg{width:100%;height:100%;max-width:16.8em;max-height:16.8em}#return_url{padding-top:1em;text-align:center;display:none}#linkRedirectToMerchant{display:block;font-size:1.15em;padding:.5em .5em .8em 1em;-webkit-border-radius:.6em;border-radius:.6em;background-color:#EEE;border:1px solid #08a806}#linkRedirectToMerchant:hover{background-color:#08a806}#back_arrow{width:1em;height:1em;fill:#08a806;position:relative;top:.3em}#linkRedirectToMerchant:hover #back_arrow{fill:#FFF;border-color:#08a806}.helper_link_container{position:absolute;left:0;bottom:-1.5em;text-align:center;width:100%}#helper_overlay{background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAD0lEQVR42mJiYGAwBggwAABCADbJ7p4FAAAAAElFTkSuQmCC);position:fixed;top:0;left:0;width:100%;height:100%;overflow:auto;text-align:center}.helper_img{padding:2em 1em 1.2em 2em;background-color:#F0F0F0;border:.2em solid #AAA;width:25em;max-height:39em;margin:5% auto 0;border-radius:1.5em;position:relative}.lang-en .helper_img>.lang-zh,.lang-zh .helper_img>.lang-en{display:none}#helper_overlay img{max-height:35.5em!important;max-width:100%!important}#close_icon_container{position:absolute;right:-1.2em;top:-1.2em;cursor:pointer}#close_icon path{fill:#777}#close_icon:hover path{fill:#333}.lang-zh .form_group .label{min-width:4.6em}.lang-zh .form_group .value{margin-left:5em}@media (min-width:30.9375em){body{font-size:.95em}}@media (min-width:37.5em){body{font-size:1.15em}#helper_link>span,#linkRedirectToMerchant>span,.lang_selector_container{font-size:.8em!important}}
    </style>
    <form id="WeChatForm">
        <div class="wechat_logo_container">
            <img class="logo" src="{{ $logo }}" />
        </div>

        <div class="form_container">

            <div class="form_group">
                <div class="label">
                    <span id="lblAmount">金额</span>
                </div>
                <div class="value amount">
                    <span id="lblAmountValue">{{ $amount }} CNY</span>
                </div>
            </div>

            <div class="form_group">
                <div class="label">
                    <span id="lblOrderID">订单</span>
                </div>
                <div class="value">
                    <span id="lblOrderIDValue">{{ $orderid }}</span>
                </div>
            </div>

            <!--div class="form_group">
                <div class="label">
                    <span id="lblDescription">描述</span>
                </div>
                <div class="value hideOverflow" onclick="this.className = (this.className.indexOf('hideOverflow') == -1) ? this.className.replace( 'showOverflow' , 'hideOverflow' ) : this.className.replace( 'hideOverflow' , 'showOverflow' ) ">
                    <span id="lblDescriptionValue">{{ $description }}</span>
                </div>
            </div-->

            <div id="qr_code" >
                <div id="QRCodeImageControl">{{ $qrCode }}</div>
            </div>
            
            <div id="return_url">
                <a id="linkRedirectToMerchant" href="javascript:__doBack()">
                    <svg version="1.1" id="back_arrow" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	                     viewBox="0 0 476.737 476.737" style="enable-background:new 0 0 476.737 476.737;" xml:space="preserve">
                        <g>
                            <path d="M341.31,74.135c-0.078-4.985-2.163-9.911-5.688-13.438l-55-55C277.023,2.096,271.963,0,266.872,0
		                            s-10.151,2.096-13.75,5.697L69.841,188.978c-3.601,3.599-5.697,8.659-5.697,13.75s2.096,10.151,5.697,13.75l183.281,183.281
		                            c3.599,3.601,8.659,5.697,13.75,5.697s10.151-2.096,13.75-5.697l55-55c3.591-3.598,5.681-8.651,5.681-13.734
		                            s-2.09-10.136-5.681-13.734L221.06,202.728L335.622,88.166C339.287,84.499,341.387,79.318,341.31,74.135L341.31,74.135z"/>
                        </g>
                    </svg>

                    <span>返回</span>
                </a>
            </div>

        </div>
    </form>
    <script type="text/javascript">
        query();
        function query() {
            if (!window.EventSource) {
                alert("浏览器不支持 EventSource");
                return;
            }

            var eventSource = new EventSource("/wechatpayment/payquery/{{ $orderid }}");

            eventSource.onopen = function (e) {
                //console.log("onEventSourceOpen")
            }
            eventSource.onmessage = function (e) {
                //console.log(e.data)
                switch (e.data) {
                    case "success":
                        eventSource.close()
                        window.location.href="{{ $returnUrl }}";
                        break;
                    case "outdated":
                        alert("二维码已过期,请重新发起支付");
                        window.location.href="{{ route('shop.cart.index') }}";
                        break;
                    default:
                        break;
                }
            }
            eventSource.onerror = function (e) {
                var returnURL = document.getElementById("return_url");
                returnURL.style.display = "block";
                //console.log(e)
            }
        }

        function __doBack() {
            window.location.href="{{ $returnUrl }}";
        }
    </script>
@endsection