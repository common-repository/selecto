/*
Plugin Name: Selecto
Plugin URI: http://cmscript.net
Description: Universal tool to collect users selections and number of options to utilize that data. Increasing significantly the site interactivity and usability.
Version: 1.1
Author: Dmitry Vadis <dmvadis@gmail.com> 
Author URI: -
Copyright: Copyright (C) Dmitry Vadis - All rights reserved worldwide 2011-2013, by the Dmitry Vadis.
License: GPL2.

Questions, suggestions and requests please address to info@cmscript.net.
*/
function appendsSideSlider(){
	
	var bodys = document.getElementsByTagName("body")[0];
	var div = document.createElement('div');
									 div.setAttribute('class','rfq_panel_Qi7255');
	bodys.appendChild(div);
	var a = document.createElement('a');
									 a.setAttribute('class','trigger');
	bodys.appendChild(a);
}
	function rfq_strResume(s, resumeLen) {
		if(s.length > resumeLen){ var ss = s.substring(0, resumeLen)+"..."; return ss; }
		return s;
	}
	function rfq_formatTitle(s,resumeLen) {
		if((s.search("<a") >= 0) || (s.search("<A") >= 0)){ 
			var marker = ">";
			var st = s.search(marker);
			var ed = s.search("</a>");
			var s1 = s.substring(0 , st + marker.length);
			var s2 = s.substring(st + marker.length, ed);
			var s3 = s.substring(ed);
			s2 = rfq_strResume(s2, resumeLen);
			//alert("PASS sss: " + s1+s2+s3);
			return s1+s2+s3;
		}
		return s;
	}
	
	var g_rfq_StrResumeLen=30;
	var g_checkAll=false;
	function rfq_showLinks() {
		var jcooky = rfq_Storage('RFQ_BAG');
		if((jcooky.Q==undefined) || ((jcooky.Q!=undefined) && !jcooky.Q.length)){
			var html = '<span style="color:#ffffff;font-size:18px;font-family: Georgia;">'+RFQ_MSG_7+'</span><p> </p>';
			html =  html + '<div class="cfq" style="align:left; font-size:10px;font-family: Georgia;"><div style="color:#ffffff;font-size:12px;font-family: Georgia;">'+RFQ_MSG_48+'</div><ul style="list-style: none; margin-left:0px;padding:0px;text-align: left;">';
				html = html + '<li style="height:16px;"><input class="bulkact" type="checkbox" name="rfq-checkallpage" id="rfq-checkallpage" />&nbsp;\
				<label class="rfq_acts" for="rfq-checkallpage" >'+RFQ_MSG_49+'</label></li>';
			html = html + "</ul><br/></div>";  
			if(jQuery(".rfq_panel_Qi7255")){ jQuery(".rfq_panel_Qi7255").html(html); }
			return;
		}
		updateRFQCellsAttr(getAction());
		var jpanel = rfq_Storage('RFQ_PANEL');
		var j, t;
		var html = '<div style="color:#ffffff;font-size:18px;font-family: Georgia;">'+RFQ_MSG_8+'</div><div class="pfq" id="rfqpfq" ><ul style="list-style: none; margin-left:0px;padding:0px;text-align: left;">';
		for(var i=0;i<jcooky.Q.length;i++){
			if(jpanel.Q[i] !==undefined){
				t = jpanel.Q[i].split("`");
				j = t[0].replace('r', 'p');
				html = html + '<li style="height:18px;"><input class="supplier" checked="checked" type="checkbox" name="'+j+'" id="'+j+'" />&nbsp;' + unescape(t[1]) + '</li>';
			}
		}
		html = html + "</ul><br/></div>"; //class=\"grad_button bt-big\" 
		
		var chkall= g_checkAll ? 'checked="checked"' : '';
		
		//..add common commands: add all, erase all
		html =  html + '<div class="cfq" style="align:left; font-size:10px;font-family: Georgia;"><div style="color:#ffffff;font-size:12px;font-family: Georgia;">'+RFQ_MSG_48+'</div><ul style="list-style: none; margin-left:0px;padding:0px;text-align: left;">';
			html = html + '<li style="height:16px;"><input class="bulkact" type="checkbox" name="rfq-checkallpage" id="rfq-checkallpage" '+chkall+' />&nbsp;\
			<label class="rfq_acts" for="rfq-checkallpage" >'+RFQ_MSG_49+'</label></li>\
			<li style="height:16px;"><input class="bulkact" type="checkbox" name="rfq-deleteall" id="rfq-deleteall" />&nbsp;\
				<label class="rfq_acts" for="rfq-deleteall" >'+RFQ_MSG_51+'</label></li>';
		html = html + "</ul><br/><div class=\"rfq_menu\" style=\"font-size:18px;font-family: Georgia;height:30px;width: 240px;padding: 8px 5px 8px 5px;color: #ffffff; margin-left:-100px;overflow: visible;\" >"+RFQ_MSG_26+"</div><div id=\"sendbutton\"></div></div>"; //class=\"grad_button bt-big\" 
		if(jQuery(".rfq_panel_Qi7255")){ jQuery(".rfq_panel_Qi7255").html(html); }
		
		//..live the send button
		jQuery("div#sendbutton").html("<input type=\"button\" class=\"sendbutton\" name=\"send\" value=\""+RFQ_MSG_27+"\" title=\""+RFQ_MSG_27+"\" />");
		var lid=getAction();
		if(lid){ jQuery(".sendbutton").val(availableActionsDescrBT[lid]).css('font-size', menuAuxDscr[lid]+'px').attr('title',availableActionsDescrBTITLE[lid]); }
	}
	function toStr(i) {
		var t = typeof i =="string";
		if(t){return true; }
		t = toString.call(i) == '[object String]';
		if(t){return true; }
		if((i != null) && !isNaN (i-0)){ i.toString(); }
		return String(i);
	}
	  
	//var allActions = ['0','1','2','3','4','5'];
	var allActions = ['0','1','2'];
	var downloadActions = ['0','1'];
	var availableActions = Array();//[0,1,2,3,4];
	
	var finalActions = new Array();
	var availableActionsDescr = 
	[
	 RFQ_MSG_29, // 'Download zipped listing',
	 RFQ_MSG_30, // 'Download listing',
	 RFQ_MSG_33 // 'No actions are setup'
	];
	//alert(availableActionsDescr[0]);
	var availableActionsDescrBT = 
	[
	 RFQ_MSG_35, // 'Download zipped listing',
	 RFQ_MSG_36, // 'Download listing',
	 RFQ_MSG_39 // 'No actions'
	];
	var availableActionsDescrBTITLE = 
	[
	 RFQ_MSG_41, // 'Download zipped listing with articles/items you\'ve selected',
	 RFQ_MSG_42, // 'Download unzipped listing with articles/items you\'ve selected',
	 RFQ_MSG_45 // 'No actions are setup yet. Please setup some actions from admin side'
	];
	var menuAuxDscr = 
	[
	 '18',
	 '18',
	 '30'
	];
	function actionDesr(i) {
		return availableActionsDescr[i];
	}
	function actionCount(i) {
		var i = i.replace('rfq_act', '');
		setCooky ('RFQact', i.replace('rfq_act', ''), 'M', 5);
		updateRFQCellsAttr(i);
	}
	function updateRFQCellsAttr(i) {
		var mark_mode=1;

        if(jQuery('.rfq') && jQuery('.rfq').hasClass('rfq')) {
			if(mark_mode) { jQuery('.rfq').addClass('rfqd').removeClass('rfq'); }
		}
        else if(jQuery('.rfqd') && jQuery('.rfqd').hasClass('rfqd')) {
			if(!mark_mode) { jQuery('.rfqd').addClass('rfq').removeClass('rfqd'); }
		}
		
	}
	function getAction() {
		//if(!availableActions.length){ setCooky ('RFQact', (allActions.length-1), 'M', 5); return allActions.length-1; }
		if(!availableActions.length){ setCooky ('RFQact', '0', 'M', 5); return '0'; }
		return getCooky ('RFQact');
	}
	//====================================================================================================================================================
	// USE COUNT  START ======================================================================================
	//====================================================================================================================================================
	function setUseCount() {
		jQuery.get( RFQ_URL+"front/dlfr.php?ucnt=1", '', function(data,status){
			USE_COUNT = data;
		});
	}	
	
	function getUseCount() {
		return USE_COUNT;
	}	
	//====================================================================================================================================================
	// USE COUNT  END ======================================================================================
	//====================================================================================================================================================
	function getLimitMessage(countVal,msgsARR) {
				if(userLevel == USERLEVEL_GUEST){
					alert(eval("RFQ_MSG_"+msgsARR[0])+countVal+eval("RFQ_MSG_"+msgsARR[1]));
				}
				else{
					alert(eval("RFQ_MSG_"+msgsARR[2])+countVal+eval("RFQ_MSG_"+msgsARR[4]));
				}
	}
	
	function getSelLimit(count) {
		if(admin_nolimits && (userLevel == USERLEVEL_ADMIN)){ return false; }
		var i=0,action = getAction(); 
		//..first check if action is correct and respective to one of current action modes
		if(!downloads) { if(allActions[i] == action){ action =''; } } i++; 
		if(!download_unzip) { if(allActions[i] == action){ action =''; } } i++; 
		
		if(action === ''){ action = availableActions[0]; }
		i=0;
		//..now check if current mode of action reach the limit
		switch(action){
			case '0': 
				if(downloads) { 
					if(count > downloads_limit){ getLimitMessage(count,[18,19,22,23]); return true; } 
					if(count > download_listing_quota){ getLimitMessage(count,[18,19,22,23]); return true; } 
				}
				break;
			case '1': 
				if(downloads && download_unzip) { 
					if(count > download_listing_quota){ getLimitMessage(count,[18,19,22,23]); return true; } 
				}  
				break;
		}
		
		return false;
	}
	
	var enterCaptcha = 0;
	function getPluginLimit() {
		if(admin_nolimits && (userLevel == USERLEVEL_ADMIN)){ return false; }
		
		var action = getAction();
		var useCount = getUseCount(); 
		
		// for use_captcha_consecutive to enter captcha on consecutive use
		if(downloads && (downloadActions.indexOf(action) >= 0) && (downloads_limit < useCount)) { 
			getLimitMessage(downloads_limit,[18,19,22,23]); 
			return true; 
		}
		return false;
	}
	
    finalActions.indexOf = function(obj, start) {
         for (var i = (start || 0), j = this.length; i < j; i++) {
             //alert((this[i].toString() === obj.toString())+", this[i]: "+this[i]+", obj: "+obj);
			 if (this[i].toString() === obj.toString()) { return i; }
         }
         return -1;
    }
	finalActions.uniqueADD=function(o){
  		//var j = parseInt(o,10);
  		//var i = this.toString().toLowerCase().indexOf(o.toLowerCase());
  		var i = this.indexOf(o);
		//alert(this+", i:"+i);
		if( i < 0){ this[this.length] = o; }
  		else{ this.splice(i,1); }
	}	
	finalActions.uQ=function(){
		var t=[]; var j=0;
		for(var i=0;i<this.length;i++){
			if(t.indexOf(this[i].toString()) < 0){
				t[j] = this[i].toString(); j++;
			}
		}
		//this.splice(0,this.length,t.join(','));
		this.splice(0,this.length);
		for(i=0;i<t.length;i++){
			this[i] = t[i].toString();
		}
	}	
	finalActions.set=function(o){
  		if((o == '') || (o == null) || (o == undefined)){return;}
		
		var a = o.split(",");
		var j=this.length;
		for(var i=0;i<a.length;i++){
			if(this.indexOf(a[i].toString()) < 0){
				this[j] = a[i].toString(); j++;
			}
		}
		//this.uQ();
	}	
	function rfq_actMenu() {		
		var currAction = String(getCooky('RFQact'));//.str.split(",");
		var html = '<div align="left" style="position: relative;height:120px;bottom: 10px;"><fieldset><legend><div style="color:#ffffff;font-size:18px;font-family: Georgia;">'+RFQ_MSG_46+'</div></legend>';
		html = html + '<ul style="list-style: none;margin-left:0px;padding:0px 0px 0px 0px;">';
		//alert("availableActions: "+availableActions.length);
		for(var i=0;i<availableActions.length;i++){
			if(currAction.indexOf(availableActions[i]) < 0){
				html = html + '<li class="rfq_actMenu" style="height:16px;font-size:14px;"><input class="action" type="radio" name="actions" id="rfq_act'+availableActions[i]+'" value="1" />&nbsp;<label  class="rfq_act" id="rfq_act_lb'+availableActions[i]+'" for="rfq_act'+availableActions[i]+'" >'+actionDesr(availableActions[i])+'</label></li>';
			}
			else {
				html = html + '<li class="rfq_actMenu" style="height:16px;font-size:14px;"><input class="action" type="radio" name="actions" id="rfq_act'+availableActions[i]+'" checked="checked" value="1" />&nbsp;<label class="rfq_act" id="rfq_act_lb'+availableActions[i]+'" for="rfq_act'+availableActions[i]+'" >'+actionDesr(availableActions[i])+'</label></li>';
			}
		}
		html = html + "</ul></fieldset></div>";
		if(jQuery(".rfq_menu")){ jQuery(".rfq_menu").html(html); }
	}
	function rfq_noSel() {
		var jcooky = rfq_Storage('RFQ_BAG');
		if(!jcooky.Q.length){
			alert(RFQ_MSG_10+"\n"+RFQ_MSG_11);
			return false;
		}
		return true;
	}
//===================================================================================
	function rfq_addsb(add)	{
		
		if(add){
			jQuery("#rfqpfq").css({
				height: "80px", 
				'overflow-y':"scroll",
				'overflow-x':"hidden"
			});
			alert(jQuery("#rfqpfq").css("height"));
			alert(jQuery("#rfqpfq").css("overflow-y"));
		}
		else{
			jQuery("rfqpfq").css({
				height: "auto", 
				'overflow-y':"visible"
			});
		}
	}
	function dumpStr( length )	{
		return " ( ==Size limit== )";
	}
	function inject2Selected_aStyle( htmlStr )	{
		if(htmlStr.indexOf("<a") >= 0) { htmlStr = htmlStr.replace("<a", '<a style="color:#9FC54E;"' ); return htmlStr; }
		if(htmlStr.indexOf("<A") >= 0) { htmlStr = htmlStr.replace("<A", '<a style="color:#9FC54E;"' ); return htmlStr; }
		return htmlStr;
	}
	function countProperties( obj )	{
		var keys = [];
        for (k in obj) {
            if (Object.prototype.hasOwnProperty.call(obj, k)) {
                keys.push(k);
				//alert(keys+"---"+k);
            }
        }
        return keys.length;
	}
	function rfq_fl(pb,show){
		var jcooky = rfq_Storage('RFQ_BAG');
		var jpanel = rfq_Storage('RFQ_PANEL');
		
		if (!jcooky.Q) { jcooky.Q = []; }
		if (!jpanel.Q) { jpanel.Q = []; }
		//alert("1: "+jcooky.Q);
		if (pb.checked) {

			var rfq_but = pb.id;/*alert("o: "+rfq_but);*/
			var link = rfq_but.replace('rfq', '');
			var shtml = jQuery("#hrfq"+link).html();
			shtml = inject2Selected_aStyle(shtml);
			shtml = rfq_formatTitle(shtml, g_rfq_StrResumeLen);
			var t = rfq_but+"`"+escape(shtml);
			var count = countProperties(jcooky.Q);
			
			if(getSelLimit(count+1)) { /*alert("cancel");  alert(document.getElementById(rfq_but).id+":"+rfq_but+", checked: "+jQuery(pb).attr('checked')+", id: "+jQuery(pb).attr('id')); jQuery(pb).attr('checked', '');document.getElementById(rfq_but).checked=''; */ pb.checked = ''; return false; }
			jcooky.Q.push(rfq_but);
			jpanel.Q.push(t);
			if(show==2){jQuery("#"+rfq_but).attr('checked','checked');}
		} else {
			var rfq_but = pb.id;
			for(var i=0;i<jcooky.Q.length;i++){
				if( rfq_but == jcooky.Q[i] ){
					jcooky.Q.splice(i,1);
				}
			}
			
			var t;// = escape(jQuery("#hrfq"+link).html());
			for(var i=0;i<jpanel.Q.length;i++){
				t = jpanel.Q[i].split("`");
				if( t[0] == rfq_but ){
					jpanel.Q.splice(i,1);
				}
			}
			if(show==2){jQuery("#"+rfq_but).attr('checked',false);}
		}

		rfq_Storage("RFQ_BAG",jcooky);
		rfq_Storage("RFQ_PANEL",jpanel);
		rfq_showLinks();
	}
	function actionsProc(this_id){
		var id = this_id.replace('rfq_act', '');
		jQuery(".sendbutton").val(availableActionsDescrBT[id]).css('font-size', menuAuxDscr[id]+'px').attr('title',availableActionsDescrBTITLE[id]); actionCount(this_id);
	}
	function in_array(v, arr){
		if (arr.indexOf(v) > -1) { return true; }
		return false;
	}
	function checkPage(isChecked){
		g_checkAll = isChecked;
		var b = rfq_Storage('RFQ_BAG');
		if(!isChecked){
			if(!b || (b && (b.Q === undefined))){ return; }
		}
		jQuery('.rfqChkbox').each(function() { 
			this.checked = g_checkAll;			
			if(g_checkAll){
				if((b.Q !== undefined) && b.Q.length){	if(jQuery.inArray(this.id, b.Q) < 0){ rfq_fl(this, 1); }}
				else { rfq_fl(this,1); }
			}
			else{
				if(b.Q.length){	if(jQuery.inArray(this.id, b.Q) >= 0){ rfq_fl(this, 2); }}
			}
		});
	}
//===================================================================================
jQuery(document).ready(function() {
	appendsSideSlider();
	jQuery(".trigger").click(function(){
		jQuery(".rfq_panel_Qi7255").toggle("fast");
		jQuery(this).toggleClass("active");
		return false;
	});


	var i = 0, j = 0;
	//if(downloads) { availableActions[j] = allActions[i]; j++; } i++; 
	//if(downloads && download_unzip) { availableActions[j] = allActions[i]; j++; } i++; 
	if(downloads) { availableActions.push(allActions[i]); } i++; 
	if(downloads && download_unzip) { availableActions.push(allActions[i]); } i++; 
	
	if(!availableActions.length){ 
		availableActions[j] = allActions[i];
	}

	rfq_showLinks();
	jQuery(document).on('click', ".enquiry_send",function() {return rfq_noSel();});
	jQuery(document).on('click', ".enquiry_send_bottom", function() {return rfq_noSel();});
	
	jQuery(document).on('click', ".sendbutton",function() { 
		setUseCount();
		if(getPluginLimit()){ return; }
		
		switch(getAction()){
			case "0":
			case "1": if(downloads) { download_File(getAction()); } else { alert(RFQ_MSG_25); } break;	
		}
	});
	
	jQuery("div#sendbutton").html("<input type=\"button\" class=\"sendbutton\" name=\"send\" value=\""+RFQ_MSG_27+"\" title=\""+RFQ_MSG_27+"\" />");
	var lid=getAction();
	if(lid){ jQuery(".sendbutton").val(availableActionsDescrBT[lid]).css('font-size', menuAuxDscr[lid]+'px').attr('title',availableActionsDescrBTITLE[lid]); }
									
	jQuery(document).on('click', ".rfq_menu", function() { /*if(jQuery(this).html() == 'Actions') { rfq_actMenu(); }*/  rfq_actMenu(); })
	
	jQuery(document).on('click', ".rfq_menu input:radio.action", function() {/* alert("Ooook: "+this.id);*/actionsProc(this.id); /* alert(availableActionsDescr[id]);*/  })
	jQuery(document).on('click', ".rfq_menu label.rfq_act", function() { actionsProc(this.id.replace('_lb', '')); })
	jQuery(document).on('click', ".pfq input:checkbox.supplier", function() { var o=Object(); o.id=this.id; o.checked=this.checked; var j = o.id; o.id = j.replace('p', 'r'); rfq_fl(o,2);})
	jQuery(document).on('click', ".rfq input:checkbox", function() {	rfq_fl(this,1);	});
	jQuery(document).on('click', ".rfqd input:checkbox", function() { rfq_fl(this,1); });
	jQuery(document).on('click', "#rfq-deleteall", function() { 
		if(this.checked){
			var pagechk = "rfq-checkallpage";
			jQuery('label[for="' + pagechk + '"]').html(RFQ_MSG_49);
			jQuery('#'+pagechk).attr('checked', false);
			checkPage(false);
			g_storageSet("RFQ_BAG",'',1);
			g_storageSet("RFQ_PANEL",'',1);
			rfq_showLinks();
		}
	});
	jQuery(document).on('click', "#rfq-checkallpage", function() { 
		checkPage(this.checked);
		rfq_showLinks();
		if(this.checked){ jQuery('label[for="' + this.id + '"]').html(RFQ_MSG_50); }
		else { jQuery('label[for="' + this.id + '"]').html(RFQ_MSG_49); }
	})
	// check all selected articles 
	var b = rfq_Storage('RFQ_BAG');
	if( typeof b.Q != 'undefined' )
	{
		for(var i=0;i<b.Q.length;i++){
			if(jQuery(".rfq").html() !== undefined){ jQuery(".rfq input:checkbox[id='"+b.Q[i]+"']").attr('checked','checked'); }
			else{ jQuery(".rfqd input:checkbox[id='"+b.Q[i]+"']").attr('checked','checked'); }
		}
	}
});
/*======================================================================================================================================================*/
function download_File(actIndex) {
	jQuery.post( RFQ_URL+"front/dlfr.php", rfq_Storage("RFQ_BAG"), function(data,status){
			do_download(actIndex);
	});
}	
function do_download(actIndex) {
    var ifr;
    var ifrID = 'hifr';
    ifr = document.getElementById(ifrID);
    if (ifr === null) {
        ifr = document.createElement('iframe');  
        ifr.id = ifrID;
        ifr.style.display = 'none';
        document.body.appendChild(ifr);
    }
	ifr.src = RFQ_URL+"front/dlfr.php?dl="+actIndex+"&p="+RFQ_CONFIG;
	g_storageSet("RFQ_BAG",'',1);
	g_storageSet("RFQ_PANEL",'',1);
	rfq_showLinks();
}
function errorDownloadMsg(msg) { alert(msg); }
