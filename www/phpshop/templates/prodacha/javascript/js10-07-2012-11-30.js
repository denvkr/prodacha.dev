var idPlTitle = '';
var temp_ChildId = '';
var temp_CurrenElementId = '';
var menu_timeout_id = 0;

function catTiTFonOver(title1PanelId, title2PanelId)
{
	var elTiTId = document.getElementById(title1PanelId);
	if (idPlTitle != '' && idPlTitle != title1PanelId)
	{
		var elTiT3Id = document.getElementById(idPlTitle);
		if (elTiTId)
		{
			elTiTId.className = "divCatTitle";
			elTiT3Id.className = "divCatTitle";
		}
	}
	idPlTitle = title1PanelId;
	if (title2PanelId != '')
	{
		var elTiT2Id = document.getElementById(title2PanelId);
		if (elTiT2Id)
		{
			elTiT2Id.className = "divCatTitle_over";
		}
	}
}
function catTiTOver(CurrenElementId, ChildId)
{	
	clearTimeout(menu_timeout_id);
	if (temp_CurrenElementId != '' && temp_CurrenElementId != CurrenElementId)
	{
		var oTemp_ChildId = document.getElementById(temp_ChildId);
		if (oTemp_ChildId)
		{
			oTemp_ChildId.className = "divCatCont_off";
		}
	}
	
	temp_ChildId = ChildId;
	temp_CurrenElementId = CurrenElementId;

	if (CurrenElementId == undefined)
	{
		return false;
	}

	if (ChildId != '')
	{
		var oChildId = document.getElementById(ChildId);
		if (oChildId)
		{
			oChildId.className = "divCatCont";
		}
	}
}
function catTiTOut(CurrenElementId, ChildId, titlePanelId)
{
			
	if (CurrenElementId == undefined)
	{
		return false;
	}

	if (ChildId != '')
	{
		var oChildId = document.getElementById(ChildId);
		var elTiTId = document.getElementById(titlePanelId);
		if (oChildId)
		{
			menu_timeout_id = setTimeout(function (){oChildId.className = "divCatCont_off";elTiTId.className = "divCatTitle"}, 500);
		}
	}
}
/****************************************************************************************/
var menu_timeout = 0;
function podCatTiTOver(id, idDiv, titlePanelId)
{
	clearTimeout(menu_timeout);
	if (id == undefined)
	{
		return false;
	}
	if (id != '')
	{
		var subid = document.getElementById(id);
		var elTiTId = document.getElementById(titlePanelId);
		
		if (subid)
		{
			subid.className = "divCatCont";
			elTiTId.className = "divCatTitle_over";
		}
	}
}
function podCatTiTOut(id, idDiv, titlePanelId)
{
	clearTimeout(menu_timeout_id);
	if (id == undefined)
	{
		return false;
	}

	if (id != '')
	{
		var subid = document.getElementById(id);
		var elTiTId = document.getElementById(titlePanelId);
		if (subid)
		{
			menu_timeout = setTimeout(function (){subid.className = "divCatCont_off";elTiTId.className = "divCatTitle"}, 500);
		}
	}
}
function catalogAktiv(strId){
	var catIdActiv = document.getElementById(strId);
	catIdActiv.className = "divCat_on";
}
function catTiTFonClick(strUrl){
	location.href = strUrl;
}
/*************************************************************/
var user_ChildId = '';
var user_CurrenElementId = '';
var user_timeout_id = 0;

function avtorizationOn(CurrenElementId, ChildId)
{	
	clearTimeout(user_timeout_id);
	if (user_CurrenElementId != '' && user_CurrenElementId != CurrenElementId)
	{
		var oUser_ChildId = document.getElementById(temp_ChildId);
		if (oUser_ChildId)
		{
			oUser_ChildId.className = "userRoomHidden";
		}
	}
	
	user_ChildId = ChildId;
	user_CurrenElementId = CurrenElementId;

	if (CurrenElementId == undefined)
	{
		return false;
	}

	if (ChildId != '')
	{
		var oUserId = document.getElementById(ChildId);
		if (oUserId)
		{
			oUserId.className = "userRoomVisible";
		}
	}
}
function avtorizationOff(CurrenElementId, ChildId)
{
			
	if (CurrenElementId == undefined)
	{
		return false;
	}

	if (ChildId != '')
	{
		var oUserId = document.getElementById(ChildId);
		if (oUserId)
		{
			user_timeout_id = setTimeout(function (){oUserId.className = "userRoomHidden"}, 2500);
		}
	}
}
function avtorizationClickOn(strId){
	var SUserId = document.getElementById(strId);
	SUserId.className = "userRoomVisible";
};
function avtorizationClickOff(strId){
	var SUserId = document.getElementById(strId);
	SUserId.className = "userRoomHidden";
};
function TiTOut(id, idDiv, titlePanelId){
	
}
function TiTOver(id, idDiv, titlePanelId){
	
}