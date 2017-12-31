function SetViewTableDiv(tableName){
    var str = AdFindObjectID(tableName + 'Loc').style.display;
    if (str == 'block' || str == ''){
        AdFindObjectID(tableName + 'Loc').style.display = "none";
        AdFindObjectID(tableName).src = themeUrl + "/images/AdImgDown.gif";
    }else{
        AdFindObjectID(tableName + 'Loc').style.display = "block";
        AdFindObjectID(tableName).src = themeUrl + "/images/AdImgUp.gif";
    }
    
}
function AdFindObjectID(idName){
    return document.getElementById(idName);
}
function AdFindObjectName(strName){
    return document.getElementsByName(strName);
}
function SetBlockAdWebServer(strName,intTabLength){
    try{
        var strDivName = strName.substring(0,strName.length-2);
        var AdDiv = AdFindObjectID(strDivName);
        for (var i = 1; i <= intTabLength; i++){
            var stri = strDivName;
            if (i<10) 
                stri = strDivName+'0'+i;
            else 
                stri = strDivName+i;
            AdFindObjectID(stri).className = "tabgrey";
        }
        var strfile = AdFindObjectID(strName).headers;
        AdFindObjectID(strName).className = "tabgreen";
        AdDiv.innerHTML = AdGenerateHTML('/AdTest/XML/ad' + strfile + '.xml','/AdTest/XSLT/ad' + strfile + '.xsl');
    }
    catch(ex){}
}
function SetBlockAdWebClient(strName,intTabLength){
    try{
        var strDivName = strName.substring(0,strName.length-2);
        for (var i = 1; i <= intTabLength; i++){
            var stri = strDivName;
            if (i<10) 
                stri = strDivName+'0'+i;
            else 
                stri = strDivName+i;
            AdFindObjectID(stri).className = "tabgrey";
            AdFindObjectID(AdFindObjectID(stri).headers).style.display = "none";
        }
        AdFindObjectID(strName).className = "tabgreen";
        AdFindObjectID(AdFindObjectID(strName).headers).style.display = "block";
    }
    catch(ex){}
}