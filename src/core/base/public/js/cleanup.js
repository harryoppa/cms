(()=>{"use strict";$(document).ready((function(){$(document).on("click",".btn-trigger-cleanup",(function(a){a.preventDefault(),$("#cleanup-modal").modal("show")})),$(document).on("click","#cleanup-submit-action",(function(a){a.preventDefault(),a.stopPropagation();var e=$(a.currentTarget);e.addClass("button-loading");var n=$("#form-cleanup-database");$.ajax({type:"POST",cache:!1,url:n.prop("action"),data:new FormData(n[0]),contentType:!1,processData:!1,success:function(a){a.error?TVHung.showError(a.message):TVHung.showSuccess(a.message),e.removeClass("button-loading"),$("#cleanup-modal").modal("hide")},error:function(a){e.removeClass("button-loading"),TVHung.handleError(a)}})}))}))})();