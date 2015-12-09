<html>
<head>
<meta http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta http-equiv="content-language" content="ru">
<meta name="description" content="Многопользовательская ролевая OnLine игра по трилогии Дж.Р.Р.Толкиена 'ВЛАСТЕЛИН КОЛЕЦ'">
<meta name="keywords" content="фэнтези ролевая онлайн игра Средиземье Эпоха сражений online game items предметы поединки бои гильдии rpg кланы магия бк таверна">
<title>Чат игры Средиземье :: Эпоха Сражений</title>
<style type="text/css">@import url("../style/global.css");</style>
<style type="text/css">@import url("chat.css");</style>
</head>
<script type="text/javascript" src="chat.js" ></script>
<script type="text/javascript" language="JavaScript">
function delete_message(id)
{
	var oCurrentMessage = top.window.frames.chat.document.getElementById('chat_mess');
	oCurrentMessage.value='#delete:'+id;
	//sendMessage();
}

function mol_message(name, id)
{
	var oCurrentMessage = top.window.frames.chat.document.getElementById('chat_mess');
	oCurrentMessage.value='#mol:20:'+name+':'+id;
}

function ok_message(id)
{
	var oCurrentMessage = top.window.frames.chat.document.getElementById('chat_mess');
	oCurrentMessage.value='#ok:'+id;

  top.window.frames.chat.document.getElementById('say').click();
}

function priv(name)
{
	var oMessage = top.window.frames.chat.document.getElementById('chat_mess');
	var oToMessage = top.window.frames.chat.document.getElementById('too');
	oMessage.focus();
	if (name=='') name='Всем';
	if (name.length>10)
		oToMessage.size=name.length;
	else
		oToMessage.size=10;
	oToMessage.value=name;
}
function cha(name)
{
	var oMessage = top.window.frames.chat.document.getElementById('chat_mess');
	var oToMessage = top.window.frames.chat.document.getElementById('too');
	oMessage.focus();
	oMessage.value=name+', '+oMessage.value;
	oToMessage.value='Всем';
	oToMessage.size=10;
}
</script>
<body style="height:100%" onclick="hide_all();">
<span id="chat_text" style="display:block"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody id="chat_text_table"></tbody></table></span>
<span id="combat_text" style="display:none"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody id="combat_text_table"></tbody></table></span>
<span id="arcomage_text" style="display:none"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody id="arcomage_text_table"></tbody></table></span> 
</body>
</html>
