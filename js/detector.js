function detectHandHeld(){
    var ua = navigator.userAgent.toLowerCase();
    var isAndroid = ua.indexOf("android") > -1;
    var isiPhone = ua.indexOf("iphone") > -1;
    var isiPad = ua.indexOf('ipad') > -1;
    var isiPod = ua.indexOf('ipod') > -1;
    var isCE = ua.indexOf('windows ce') > -1;
    if(isAndroid || isiPhone || isiPad || isiPod || isCE){
    var question = confirm("Browse mobile website?");
    if(question)
        window.location = 'm/index.php';
    else
        return;
        
    }
}