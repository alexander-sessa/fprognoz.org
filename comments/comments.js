class UploadAdapter{
        constructor(loader){this.loader = loader;}
        upload(){
                return this.loader.file
                .then(file=>new Promise((resolve,reject)=> {
                        this._initRequest();
                        this._initListeners(resolve,reject,file);
                        this._sendRequest(file);
                }));
        }
        abort(){if(this.xhr){this.xhr.abort();}}
        _initRequest(){
                const xhr=this.xhr=new XMLHttpRequest();
                xhr.open('POST','https://master-series.org/upload.php',true);
                xhr.responseType='json';
        }
        _initListeners(resolve,reject,file){
                const xhr=this.xhr;
                const loader=this.loader;
                const genericErrorText=`Невозможно залить файл: ${file.name}.`;
                xhr.addEventListener('error',()=>reject(genericErrorText));
                xhr.addEventListener('abort',()=>reject());
                xhr.addEventListener('load',()=>{
                        const response = xhr.response;
                        if(!response||response.error){return reject(response&&response.error?response.error.message:genericErrorText)}
                        resolve({default:response.url});
                });
                if(xhr.upload){
                        xhr.upload.addEventListener('progress',evt=>{
                                if(evt.lengthComputable){loader.uploadTotal=evt.total;loader.uploaded=evt.loaded;}
                        });
                }
        }
        _sendRequest(file){
                const data=new FormData();
                data.append('upload',file);
                data.append('hash',$('#comments_wrapper').data('hash'));
                this.xhr.send(data);
        }
}
function UploadAdapterPlugin(editor){editor.plugins.get('FileRepository').createUploadAdapter=(loader)=>{return new UploadAdapter(loader)}}
var cke_config={extraPlugins:[UploadAdapterPlugin],language:"ru"},editable=null,isEnabled=[],contentHTML=[],cke=[]
function c_quote(cid,inf){com=$("[commentid=\""+cid+"\"]");c_text=$("main",com).html();var begin=1+c_text.indexOf(">"),end=c_text.lastIndexOf("<");c_text=c_text.substr(begin,end-begin);var c_date=$(".c-comment-date",com).html(),sStr="<blockquote><p><sub>"+$(".c-comment-author",com).html()+" <em>писал"+inf+" "+c_date.split(" ").join(" в ")+"</em></sub></p><p>&bdquo;"+c_text+"&ldquo;</p></blockquote><p></p>";$("#cke"+cid).html($("#cke"+cid).html()+sStr)}
function changeRating(id,rate_yes,rate_no,vote){$("#r_yes"+id).html(rate_yes?rate_yes:"");$("#r_no"+id).html(rate_no?rate_no:"");$.get("comments/vote.php",{user:$("#comments_wrapper").data("name"),id:id,vote:vote,hash:$("#comments_wrapper").data("hash")})}
function saveContent(id,c_text){$.post("comments/save.php",{user:$("#comments_wrapper").data("name"),id:id,c_text:c_text,hash:$("#comments_wrapper").data("hash")})}
function modComment(id,man,status){$.get("comments/mod.php",{key:"content:"+id,man:man,status:status});$("#"+(status>0?"approve":"c_block")+id).remove()}
function isEditorEnabled(id){if(cke[id])return true;else cke[id]=1;return false}
function toggleEditor(id) {
        var reset=document.getElementById("reset"+id),toggle=document.getElementById("toggle"+id),content=document.getElementById("content"+id)
        if(isEnabled[id]==undefined)isEnabled[id]=null
        if(isEnabled[id]){
                editor=isEnabled[id]
                if(editor.model.document.differ)reset.style.display="inline"
                toggle.innerHTML="<i class=\"fas fa-edit\" aria-hidden=\"true\"></i> Исправить"
                editor.destroy()
                isEnabled[id]=null
                content.innerHTML=content.innerHTML.replace("img src","img class=\"img-fluid\" src")
                saveContent(id,content.innerHTML)
        }
        else{
                toggle.innerHTML="<i class=\"fas fa-save text-success\" aria-hidden=\"true\"></i><span class=\"text-success\">&nbsp;Записать<span>"
                InlineEditor
                        .create(document.querySelector("#content"+id),cke_config)
                        .then(function(editor){
                                isEnabled[id]=editor;
                                $("#content"+id).click().focus()
                        })
        }
}
function moreComments(c,m,s,f){$.post("/ajax.php",{m:m,s:s,c_from:f,coach:c},function(r){$("#more_comments").replaceWith(r)})}
$(document).ready(function(){
        $(".c-content").change(function(){
                if($("#toggle"+this.id.substr(7)+" i").hasClass("fa-edit")&&html.indexOf("img-fluid")==-1){
                        html=$(this).html()
                        $(this).html(html.replace("src=", "class=\"img-fluid\" src="))
                }
        })
})
