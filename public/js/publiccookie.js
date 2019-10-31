//域名
var domain_url = "http://www.1812.com";
//设置自定义过期时间cookie  
function setCookie(name,value,min)
{	//分钟 
	//console.log(time);
    //var msec = getMsec(time); //获取毫秒
	//console.log(msec);return;
    var exp = new Date();
    exp.setTime(exp.getTime() + min*1000*60);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString() + ";path=/";
}

function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)"); //正则匹配
    if(arr=document.cookie.match(reg)){
      return unescape(arr[2]);
    }
    else{
     return null;
    }
}

function delCookie(name)
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null){
      document.cookie= name + "="+cval+";expires="+exp.toGMTString() + ";path=/";
    }
}



function getQueryString(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var reg_rewrite = new RegExp("(^|/)" + name + "/([^/]*)(/|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	var q = window.location.pathname.substr(1).match(reg_rewrite);
	if(r != null){
		return unescape(r[2]);
	}else if(q != null){
		return unescape(q[2]);
	}else{
		return null;
	}
}

