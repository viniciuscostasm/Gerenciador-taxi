/* Bookmarks ---------------------------------------------------------------- */

var bookmarks = function(){
    var lasthash = '';
    var isie = false;
    var iec = 0;
    var iev = 0;
    var bookmarked = new Array();
    var params = new Array();
    return {
        initialize:function(){
            var quirks = document.compatMode;
            if(document.all){
                if(/MSIE (\d+\.\d+);/.test(navigator.userAgent)){
                    iev = new Number(RegExp.$1);
                }
                if(iev>=8 && quirks=='BackCompat' || iev<8){
                    bookmarks.iframe();
                    isie = true;
                }
            }
            setInterval("bookmarks.checkhash();", 400);
        },
        sethash:function(hash,url,container,param){
            if(hash){
                if(isie){
                    iec++;
                }
                var str = hash + ',' + url + ',' + container + ',' + iec;

                var num = '';
                var partof = false;
                lasthash = hash;
                window.location.href = hash;
                for(var i=0;i<bookmarked.length;i++){
                    var tmp = bookmarked[i].split(",");
                    if(tmp[0]==hash){
                        partof = true;
                        num = tmp[3];
                    }
                }
                if(isie){
                    if(!partof){
                        bookmarks.setiframe(hash,iec);
                    }else{
                        bookmarks.setiframe(hash,num);
                    }
                }

                if(!partof){
                    bookmarked.push(str);
                    params.push(param);
                }
            }
        },
        checkhash:function(){
            var obj = window.location.hash;
            var purl, pctn, phas, parm;

            if(obj){
                if(obj!=lasthash){
                    if(lasthash!=undefined){
                        for(var i=0;i<bookmarked.length;i++){
                            var tmp = bookmarked[i].split(",");
                            if(tmp[0]==obj){
                                phas = tmp[0];
                                purl = tmp[1];
                                pctn = tmp[2];
                                parm = params[i];
                                break;
                            }
                        }
                        if(phas && purl && pctn){
                            lasthash = phas;
                            jQuery.gAjax.load(purl, parm, pctn,'');
                        }
                    }
                }
            }
        },
        iframe:function(){
            var bug = document.createElement("iframe");
            bug.src = 'blank.html';
            bug.id = 'bugframe';
            bug.style.width = '100px';
            bug.style.height = '100px';
            bug.style.display = 'none';
            document.body.appendChild(bug);
        },
        setiframe:function(f,num){
            document.getElementById('bugframe').src = 'blank.html?' + num + f;
        },
        fixiframe:function(f){
            var obj = window.location.hash;
            if(f){
                if(f!=obj){
                    window.location.href = f;
                }
            }
        }
    };
}();