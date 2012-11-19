<?php

function bandpageToken($client_id,$shared_secret) {

	if($_COOKIE['bp_access_token']) {

		$access_token = $_COOKIE['bp_access_token'];

	} else {

		//set POST variables
		$url = 'https://api-read.bandpage.com/token';
		$fields = array(
		    'client_id' => $client_id,
		    'grant_type' => "client_credentials"
		);

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($fields));
		curl_setopt($ch,CURLOPT_USERPWD, $client_id . ':' . $shared_secret);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//execute post
		$result = curl_exec($ch);

		$obj = json_decode($result);
		
		setcookie("bp_access_token", $obj->access_token, time()+$obj->expires_in-10);
		$access_token = $obj->access_token;

		//close connection
		curl_close($ch);

	}

	return $access_token;

}

$app_id = 'xxx';
$client_id = 'xxx';
$shared_secret = 'xxx';
$access_token = bandpageToken($client_id,$shared_secret);

?>
<!DOCTYPE html>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Bandpage Connect</title>
	<script type='text/javascript'>;(function(){var e="sdk-js.bandpage.com",t="/embedscript/connect";window._rm_lightningjs||function(e){function n(n,r){var i="1";return r&&(r+=(/\?/.test(r)?"&":"?")+"lv="+i),e[n]||function(){var i=window,s=document,o=n,u=s.location.protocol,a="load",f=0;(function(){function l(){n.P(a),n.w=1,e[o]("_load")}e[o]=function(){function a(){return a.id=s,e[o].apply(a,arguments)}var t=arguments,r=this,s=++f,u=r&&r!=i?r.id||0:0;return(n.s=n.s||[]).push([s,u,t]),a.then=function(e,t,r){var i=n.fh[s]=n.fh[s]||[],o=n.eh[s]=n.eh[s]||[],u=n.ph[s]=n.ph[s]||[];return e&&i.push(e),t&&o.push(t),r&&u.push(r),a},a};var n=e[o]._={};n.fh={},n.eh={},n.ph={},n.l=r?r.replace(/^\/\//,(u=="https:"?u:"http:")+"//"):r,n.p={0:+(new Date)},n.P=function(e){n.p[e]=new Date-n.p[0]},n.w&&l(),i.addEventListener?i.addEventListener(a,l,!1):i.attachEvent("on"+a,l),n.l&&function(){function e(){return["<",r,' onload="var d=',p,";d.getElementsByTagName('head')[0].",u,"(d.",a,"('script')).",f,"='",n.l,"'\">"].join("")}var r="body",i=s[r];if(!i)return setTimeout(arguments.callee,100);n.P(1);var u="appendChild",a="createElement",f="src",l=s[a]("div"),c=l[u](s[a]("div")),h=s[a]("iframe"),p="document",d="domain",v,m="contentWindow";l.style.display="none",i.insertBefore(l,i.firstChild).id=t+"-"+o,h.frameBorder="0",h.id=t+"-frame-"+o,/MSIE[ ]+6/.test(navigator.userAgent)&&(h[f]="javascript:false"),h.allowTransparency="true",c[u](h);try{h[m][p].open()}catch(g){n[d]=s[d],v="javascript:var d="+p+".open();d.domain='"+s.domain+"';",h[f]=v+"void(0);"}try{var y=h[m][p];y.write(e()),y.close()}catch(b){h[f]=v+'d.write("'+e().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}n.P(2)}()})()}(),e[n].lv=i,e[n]}var t="_rm_lightningjs",r=window[t]=n(t);r.require=n,r.modules=e}({}),function(n){if(n.bandpage)return;var r=_rm_lightningjs.require("$rm","//"+e+t),i=function(){},s=function(t){t.done||(t.done=i),t.fail||(t.fail=i);var n=r("load",t);n.then(t.done,t.fail);var s={done:function(e){return n.then(e,i),s},fail:function(e){return n.then(i,e),s}};return s},o=null;n.bandpage={load:s,ready:function(e){o.then(e,i)}},o=r("bootstrap",n.bandpage,window)}(window)})(this);</script>
    <script type="text/javascript">
    	bandpage.load({
    	    "done" : function() {
    	         var connection = bandpage.sdk.connect({
    	              appId : "<?=$app_id?>",
    	              access_token : "<?=$access_token?>",
    	              container : $('.btn-container').get(0),
    	              allow_reconnect : false
    	        });
				connection.on("bpconnect.complete", function(bands){
					console.log("User has authed bands!");
					console.log(bands);
				});
				connection.on("bpconnect.cancel", function(){
					console.log("User clicked the cancel button and connect window is closed");
				});
    	    },
    	    "fail" : function() {
    	        console.log("Failed to initialize sdk");
    	    }
	    });    	
    </script>
    <style>
    	.btn-container {
    		top: 50%;
    		position: absolute;
    		left: 50%;
    		margin: -22px 0 0 -117px;
    	}    	
    </style>
    </head>
	<body>
		<div class='btn-container'></div>
	</body>
</html>