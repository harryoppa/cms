(()=>{function e(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var t=function(){function t(){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t)}var a,n;return a=t,n=[{key:"init",value:function(){this.handleMultipleAdminEmails(),$("input[data-key=email-config-status-btn]").on("change",(function(e){var t=$(e.currentTarget),a=t.prop("id"),n=t.data("change-url");$.ajax({type:"POST",url:n,data:{key:a,value:t.prop("checked")?1:0},success:function(e){e.error?TVHung.showError(e.message):TVHung.showSuccess(e.message)},error:function(e){TVHung.handleError(e)}})})),$(document).on("change",".setting-select-options",(function(e){$(".setting-wrapper").addClass("hidden"),$(".setting-wrapper[data-type="+$(e.currentTarget).val()+"]").removeClass("hidden")})),$(".send-test-email-trigger-button").on("click",(function(e){e.preventDefault();var t=$(e.currentTarget),a=t.text();t.text(t.data("saving")),$.ajax({type:"POST",url:route("settings.email.edit"),data:t.closest("form").serialize(),success:function(e){e.error?TVHung.showError(e.message):(TVHung.showSuccess(e.message),$("#send-test-email-modal").modal("show")),t.text(a)},error:function(e){TVHung.handleError(e),t.text(a)}})})),$("#send-test-email-btn").on("click",(function(e){e.preventDefault();var t=$(e.currentTarget);t.addClass("button-loading"),$.ajax({type:"POST",url:route("setting.email.send.test"),data:{email:t.closest(".modal-content").find("input[name=email]").val()},success:function(e){e.error?TVHung.showError(e.message):TVHung.showSuccess(e.message),t.removeClass("button-loading"),t.closest(".modal").modal("hide")},error:function(e){TVHung.handleError(e),t.removeClass("button-loading"),t.closest(".modal").modal("hide")}})})),"undefined"!=typeof CodeMirror&&TVHung.initCodeEditor("mail-template-editor"),$(document).on("click",".btn-trigger-reset-to-default",(function(e){e.preventDefault(),$("#reset-template-to-default-button").data("target",$(e.currentTarget).data("target")),$("#reset-template-to-default-modal").modal("show")})),$(document).on("click","#reset-template-to-default-button",(function(e){e.preventDefault();var t=$(e.currentTarget);t.addClass("button-loading"),$.ajax({type:"POST",cache:!1,url:t.data("target"),data:{email_subject_key:$("input[name=email_subject_key]").val(),template_path:$("input[name=template_path]").val()},success:function(e){e.error?TVHung.showError(e.message):(TVHung.showSuccess(e.message),setTimeout((function(){window.location.reload()}),1e3)),t.removeClass("button-loading"),$("#reset-template-to-default-modal").modal("hide")},error:function(e){TVHung.handleError(e),t.removeClass("button-loading")}})}))}},{key:"handleMultipleAdminEmails",value:function(){var e=$("#admin_email_wrapper");if(e.length){var t=e.find("#add"),a=parseInt(e.data("max"),10),n=e.data("emails");0===n.length&&(n=[""]);var r=function(){e.find("input[type=email]").length>=a?t.addClass("disabled"):t.removeClass("disabled")},o=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";return t.before('<div class="d-flex mt-2 more-email align-items-center">\n                <input type="email" class="next-input" placeholder="'.concat(t.data("placeholder"),'" name="admin_email[]" value="').concat(e||"",'" />\n                <a class="btn btn-link text-danger"><i class="fas fa-minus"></i></a>\n            </div>'))};e.on("click",".more-email > a",(function(){$(this).parent(".more-email").remove(),r()})),t.on("click",(function(e){e.preventDefault(),o(),r()})),n.forEach((function(e){o(e)})),r()}}}],n&&e(a.prototype,n),t}();$(document).ready((function(){(new t).init()}))})();
