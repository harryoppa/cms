(()=>{var t,a={2297:()=>{function t(t,a){for(var i=0;i<a.length;i++){var r=a[i];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}var a=function(){function a(t){!function(t,a){if(!(t instanceof a))throw new TypeError("Cannot call a class as a function")}(this,a),this.$container=t,this.$avatarView=this.$container.find(".avatar-view"),this.$triggerButton=this.$avatarView.find(".mt-overlay .btn-outline"),this.$avatar=this.$avatarView.find("img"),this.$avatarModal=this.$container.find("#avatar-modal"),this.$loading=this.$container.find(".loading"),this.$avatarForm=this.$avatarModal.find(".avatar-form"),this.$avatarSrc=this.$avatarForm.find(".avatar-src"),this.$avatarData=this.$avatarForm.find(".avatar-data"),this.$avatarInput=this.$avatarForm.find(".avatar-input"),this.$avatarSave=this.$avatarForm.find(".avatar-save"),this.$avatarWrapper=this.$avatarModal.find(".avatar-wrapper"),this.$avatarPreview=this.$avatarModal.find(".avatar-preview"),this.support={fileList:!!$('<input type="file">').prop("files"),fileReader:!!window.FileReader,formData:!!window.FormData}}var i,r,e;return i=a,e=[{key:"isImageFile",value:function(t){return t.type?/^image\/\w+$/.test(t.type):/\.(jpg|jpeg|png|gif)$/.test(t)}},{key:"submitFail",value:function(t){TVHung.handleError(t)}}],(r=[{key:"init",value:function(){this.support.datauri=this.support.fileList&&this.support.fileReader,this.support.formData||this.initIframe(),this.initTooltip(),this.initModal(),this.addListener()}},{key:"addListener",value:function(){this.$triggerButton.on("click",$.proxy(this.click,this)),this.$avatarInput.on("change",$.proxy(this.change,this)),this.$avatarForm.on("submit",$.proxy(this.submit,this))}},{key:"initTooltip",value:function(){this.$avatarView.tooltip({placement:"bottom"})}},{key:"initModal",value:function(){this.$avatarModal.modal("hide"),this.initPreview()}},{key:"initPreview",value:function(){var t=this.$avatar.prop("src");this.$avatarPreview.empty().html('<img src="'+t+'">')}},{key:"initIframe",value:function(){var t="avatar-iframe-"+Math.random().toString().replace(".",""),a=$('<iframe name="'+t+'" style="display:none;"></iframe>'),i=!0,r=this;this.$iframe=a,this.$avatarForm.attr("target",t).after(a),this.$iframe.on("load",(function(){var t,a,e;try{a=this.contentWindow,t=(e=(e=this.contentDocument)||a.document)?e.body.innerText:null}catch(t){}t?r.submitDone(t):i?i=!1:r.submitFail("Image upload failed!"),r.submitEnd()}))}},{key:"click",value:function(){this.$avatarModal.modal("show")}},{key:"change",value:function(){var t,i;this.support.datauri?(t=this.$avatarInput.prop("files")).length>0&&(i=t[0],a.isImageFile(i)&&this.read(i)):(i=this.$avatarInput.val(),a.isImageFile(i)&&this.syncUpload())}},{key:"submit",value:function(){return this.$avatarSrc.val()||this.$avatarInput.val()?this.support.formData?(this.ajaxUpload(),!1):void 0:(TVHung.showError("Please select image!"),!1)}},{key:"read",value:function(t){var a=this,i=new FileReader;i.readAsDataURL(t),i.onload=function(){a.url=this.result,a.startCropper()}}},{key:"startCropper",value:function(){var t=this;this.active?this.$img.cropper("replace",this.url):(this.$img=$('<img src="'+this.url+'">'),this.$avatarWrapper.empty().html(this.$img),this.$img.cropper({aspectRatio:1,rotatable:!0,preview:this.$avatarPreview.selector,done:function(a){var i=['{"x":'+a.x,'"y":'+a.y,'"height":'+a.height,'"width":'+a.width+"}"].join();t.$avatarData.val(i)}}),this.active=!0)}},{key:"stopCropper",value:function(){this.active&&(this.$img.cropper("destroy"),this.$img.remove(),this.active=!1)}},{key:"ajaxUpload",value:function(){var t=this.$avatarForm.attr("action"),a=new FormData(this.$avatarForm[0]),i=this;$.ajax(t,{type:"POST",data:a,processData:!1,contentType:!1,beforeSend:function(){i.submitStart()},success:function(t){i.submitDone(t)},error:function(t,a,r){i.submitFail(t.responseJSON,a||r)},complete:function(){i.submitEnd()}})}},{key:"syncUpload",value:function(){this.$avatarSave.trigger("click")}},{key:"submitStart",value:function(){this.$loading.fadeIn(),this.$avatarSave.attr("disabled",!0).text("Saving...")}},{key:"submitDone",value:function(t){try{t=$.parseJSON(t)}catch(t){}t&&!t.error&&t.data?(this.url=t.data.url,this.support.datauri||this.uploaded?(this.uploaded=!1,this.cropDone()):(this.uploaded=!0,this.$avatarSrc.val(this.url),this.startCropper()),this.$avatarInput.val(""),TVHung.showSuccess(t.message)):TVHung.showError(t.message)}},{key:"submitEnd",value:function(){this.$loading.fadeOut(),this.$avatarSave.removeAttr("disabled").text("Save")}},{key:"cropDone",value:function(){this.$avatarSrc.val(""),this.$avatarData.val(""),this.$avatar.prop("src",this.url),$(".user-menu img").prop("src",this.url),$(".user.dropdown img").prop("src",this.url),this.stopCropper(),this.initModal()}}])&&t(i.prototype,r),e&&t(i,e),a}();$(document).ready((function(){new a($(".crop-avatar")).init()}))},4267:()=>{},1581:()=>{},1306:()=>{},266:()=>{},7585:()=>{},277:()=>{},537:()=>{},5973:()=>{},9278:()=>{},5867:()=>{},7418:()=>{},6570:()=>{},3538:()=>{},2783:()=>{},8865:()=>{},2170:()=>{},9540:()=>{},5131:()=>{},7944:()=>{},7994:()=>{}},i={};function r(t){var e=i[t];if(void 0!==e)return e.exports;var o=i[t]={exports:{}};return a[t](o,o.exports,r),o.exports}r.m=a,t=[],r.O=(a,i,e,o)=>{if(!i){var n=1/0;for(u=0;u<t.length;u++){for(var[i,e,o]=t[u],s=!0,v=0;v<i.length;v++)(!1&o||n>=o)&&Object.keys(r.O).every((t=>r.O[t](i[v])))?i.splice(v--,1):(s=!1,o<n&&(n=o));if(s){t.splice(u--,1);var h=e();void 0!==h&&(a=h)}}return a}o=o||0;for(var u=t.length;u>0&&t[u-1][2]>o;u--)t[u]=t[u-1];t[u]=[i,e,o]},r.o=(t,a)=>Object.prototype.hasOwnProperty.call(t,a),(()=>{var t={383:0,232:0,579:0,550:0,194:0,783:0,87:0,833:0,562:0,135:0,377:0,846:0,795:0,775:0,326:0,739:0,903:0,777:0,576:0,693:0,966:0};r.O.j=a=>0===t[a];var a=(a,i)=>{var e,o,[n,s,v]=i,h=0;if(n.some((a=>0!==t[a]))){for(e in s)r.o(s,e)&&(r.m[e]=s[e]);if(v)var u=v(r)}for(a&&a(i);h<n.length;h++)o=n[h],r.o(t,o)&&t[o]&&t[o][0](),t[n[h]]=0;return r.O(u)},i=self.webpackChunk=self.webpackChunk||[];i.forEach(a.bind(null,0)),i.push=a.bind(null,i.push.bind(i))})(),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(2297))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(9540))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(5131))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(7944))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(7994))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(4267))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(1581))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(1306))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(266))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(7585))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(277))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(537))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(5973))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(9278))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(5867))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(7418))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(6570))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(3538))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(2783))),r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(8865)));var e=r.O(void 0,[232,579,550,194,783,87,833,562,135,377,846,795,775,326,739,903,777,576,693,966],(()=>r(2170)));e=r.O(e)})();
