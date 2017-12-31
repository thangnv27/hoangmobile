if (location.host !=='vnexpress.net')
{
    document.write('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    try{
        if (typeof vHanoi != "undefined")
            document.write('<tr><td>&nbsp;&nbsp;' + vHanoi + '</td><td></td><td>&nbsp;' + dHanoi + '&deg;C</td></tr>');
        if (typeof vHaiPhong != "undefined")
            document.write('<tr><td>&nbsp;&nbsp;' + vHaiPhong + '</td><td></td><td>&nbsp;' + dHaiPhong + '&deg;C</td></tr>');
        if (typeof vHue != "undefined")
            document.write('<tr><td>&nbsp;&nbsp;' + vHue + '</td><td></td><td>&nbsp;' + dHue + '&deg;C</td></tr>');
        if (typeof vDaNang != "undefined")
            document.write('<tr><td>&nbsp;&nbsp;' + vDaNang + '</td><td></td><td>&nbsp;' + dDaNang + '&deg;C</td></tr>');
        if (typeof vHoChiMinh != "undefined")
            document.write('<tr><td>&nbsp;&nbsp;' + vHoChiMinh + '</td><td></td><td>&nbsp;' + dHoChiMinh + '&deg;C</td></tr>');
    }
    catch (error){
        document.write('<a href="http://ppo.vn">PPO.VN</a>' + error.message);
    }
    document.write('<tr><td colspan="3" height="10px">&nbsp;</td></tr>');
    document.write('</table>');
}
else
    document.write('<a href="http://ppo.vn">PPO.VN</a>');