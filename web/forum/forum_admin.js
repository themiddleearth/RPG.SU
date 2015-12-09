function get_url( name )
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return results[1];
}

function un_select_reply(id,img_domain)
{    
	el = document.getElementById(id);
    if (el)
    {
        if (el.src == "http://"+img_domain+"/forum/img/topic_selected.gif")
        {
            el.src = "http://"+img_domain+"/forum/img/topic_unselected.gif";
            el.selected = 0;
        }
        else
        {
            el.src = "http://"+img_domain+"/forum/img/topic_selected.gif";
            el.selected = 1;
        }
    }
    moder_select = document.getElementById("moder_select")
    if (moder_select)
    {
        kol=0;  
        list_img = document.getElementsByTagName("img");     
        for (i=0;i<list_img.length;i++)
        {
            img = list_img[i];
            if (img.id.substring(0,6)=="imgsel"){
                if (img.selected==1)
                {
                    kol++;
                }
            }
        }       
        moder_select.value = "� ����������� ("+kol+"): ";
    }
}

function action_moder()
{
    sel = document.getElementById("select_moder");
    if (sel)
    {
        var ar = new Array();
        kol=0;  
        list_img = document.getElementsByTagName("img");     
        for (i=0;i<list_img.length;i++)
        {
            img = list_img[i];
            if (img.id.substring(0,6)=="imgsel"){
                if (img.selected==1)
                {
                    kol++;
                    ar[kol-1]=img.id.substring(6);
                }
            }
        }    
        if (kol>0)
        {   
            var page = get_url('page');			
			value = sel.value;
            str_get="index.php?page="+page+"&moder="+value+"&ar="+ar.toString();
            // alert(str_get);
            location.replace(str_get);
        }
    }
}
