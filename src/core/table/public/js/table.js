(()=>{function e(e,a){for(var t=0;t<a.length;t++){var n=a[t];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}!function(a,t){"use strict";var n=function(e,t){var n=e.ajax.params();return n.action=t,n._token=a('meta[name="csrf-token"]').attr("content"),n},o=function(e,t){var n=e+"/export",o=new XMLHttpRequest;o.open("POST",n,!0),o.responseType="arraybuffer",o.onload=function(){if(200===this.status){var e="",a=o.getResponseHeader("Content-Disposition");if(a&&-1!==a.indexOf("attachment")){var t=/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(a);null!=t&&t[1]&&(e=t[1].replace(/['"]/g,""))}var n=o.getResponseHeader("Content-Type"),r=new Blob([this.response],{type:n});if(void 0!==window.navigator.msSaveBlob)window.navigator.msSaveBlob(r,e);else{var s=window.URL||window.webkitURL,c=s.createObjectURL(r);if(e){var l=document.createElement("a");void 0===l.download?window.location=c:(l.href=c,l.download=e,document.body.appendChild(l),l.trigger("click"))}else window.location=c;setTimeout((function(){s.revokeObjectURL(c)}),100)}}},o.setRequestHeader("Content-type","application/x-www-form-urlencoded"),o.send(a.param(t))},r=function(e,t){var n=e.ajax.url()||"",o=e.ajax.params();return o.action=t,n.indexOf("?")>-1?n+"&"+a.param(o):n+"?"+a.param(o)};t.ext.buttons.excel={className:"buttons-excel",text:function(e){return'<i class="far fa-file-excel"></i> '+e.i18n("buttons.excel",TvhungVariables.languages.tables.excel?TvhungVariables.languages.tables.excel:"Excel")},action:function(e,a){window.location=r(a,"excel")}},t.ext.buttons.postExcel={className:"buttons-excel",text:function(e){return'<i class="far fa-file-excel"></i> '+e.i18n("buttons.excel",TvhungVariables.languages.tables.excel?TvhungVariables.languages.tables.excel:"Excel")},action:function(e,a){var t=a.ajax.url()||window.location.href,r=n(a,"excel");o(t,r)}},t.ext.buttons.export={extend:"collection",className:"buttons-export",text:function(e){return'<i class="fa fa-download"></i> '+e.i18n("buttons.export",TvhungVariables.languages.tables.export?TvhungVariables.languages.tables.export:"Export")+'&nbsp;<span class="caret"/>'},buttons:["csv","excel"]},t.ext.buttons.csv={className:"buttons-csv",text:function(e){return'<i class="fas fa-file-csv"></i> '+e.i18n("buttons.csv",TvhungVariables.languages.tables.csv?TvhungVariables.languages.tables.csv:"CSV")},action:function(e,a){window.location=r(a,"csv")}},t.ext.buttons.postCsv={className:"buttons-csv",text:function(e){return'<i class="fas fa-file-csv"></i> '+e.i18n("buttons.csv",TvhungVariables.languages.tables.csv?TvhungVariables.languages.tables.csv:"CSV")},action:function(e,a){var t=a.ajax.url()||window.location.href,r=n(a,"csv");o(t,r)}},t.ext.buttons.pdf={className:"buttons-pdf",text:function(e){return'<i class="far fa-file-pdf"></i> '+e.i18n("buttons.pdf","PDF")},action:function(e,a){window.location=r(a,"pdf")}},t.ext.buttons.postPdf={className:"buttons-pdf",text:function(e){return'<i class="far fa-file-pdf"></i> '+e.i18n("buttons.pdf","PDF")},action:function(e,a){var t=a.ajax.url()||window.location.href,r=n(a,"pdf");o(t,r)}},t.ext.buttons.print={className:"buttons-print",text:function(e){return'<i class="fa fa-print"></i> '+e.i18n("buttons.print",TvhungVariables.languages.tables.print?TvhungVariables.languages.tables.print:"Print")},action:function(e,a){window.location=r(a,"print")}},t.ext.buttons.reset={className:"buttons-reset",text:function(e){return'<i class="fa fa-undo"></i> '+e.i18n("buttons.reset",TvhungVariables.languages.tables.reset?TvhungVariables.languages.tables.reset:"Reset")},action:function(){a(".table thead input").val("").keyup(),a(".table thead select").val("").change()}},t.ext.buttons.reload={className:"buttons-reload",text:function(e){return'<i class="fas fa-sync"></i> '+e.i18n("buttons.reload",TvhungVariables.languages.tables.reload?TvhungVariables.languages.tables.reload:"Reload")},action:function(e,a){a.draw(!1)}},t.ext.buttons.create={className:"buttons-create",text:function(e){return'<i class="fa fa-plus"></i> '+e.i18n("buttons.create","Create")},action:function(){window.location=window.location.href.replace(/\/+$/,"")+"/create"}},void 0!==t.ext.buttons.copyHtml5&&a.extend(t.ext.buttons.copyHtml5,{text:function(e){return'<i class="fa fa-copy"></i> '+e.i18n("buttons.copy","Copy")}}),void 0!==t.ext.buttons.colvis&&a.extend(t.ext.buttons.colvis,{text:function(e){return'<i class="fa fa-eye"></i> '+e.i18n("buttons.colvis","Column visibility")}});var s=function(){function t(){!function(e,a){if(!(e instanceof a))throw new TypeError("Cannot call a class as a function")}(this,t),this.init(),this.handleActionsRow(),this.handleActionsExport()}var n,o;return n=t,(o=[{key:"init",value:function(){a(document).on("change",".table-check-all",(function(e){var t=a(e.currentTarget),n=t.attr("data-set"),o=t.prop("checked");a(n).each((function(e,t){o?a(t).prop("checked",!0):a(t).prop("checked",!1)}))})),a(document).on("change",".checkboxes",(function(e){var t=a(e.currentTarget),n=t.closest(".table-wrapper").find(".table").prop("id"),o=[],r=a("#"+n);r.find(".checkboxes:checked").each((function(e,t){o[e]=a(t).val()})),o.length!==r.find(".checkboxes").length?t.closest(".table-wrapper").find(".table-check-all").prop("checked",!1):t.closest(".table-wrapper").find(".table-check-all").prop("checked",!0)})),a(document).on("click",".btn-show-table-options",(function(e){e.preventDefault(),a(e.currentTarget).closest(".table-wrapper").find(".table-configuration-wrap").slideToggle(500)})),a(document).on("click",".action-item",(function(e){e.preventDefault();var t=a(e.currentTarget).find("span[data-href]"),n=t.data("action"),o=t.data("href");n&&"#"!==o&&(window.location.href=o)}))}},{key:"handleActionsRow",value:function(){var e=this;a(document).on("click",".deleteDialog",(function(e){e.preventDefault();var t=a(e.currentTarget);a(".delete-crud-entry").data("section",t.data("section")).data("parent-table",t.closest(".table").prop("id")),a(".modal-confirm-delete").modal("show")})),a(".delete-crud-entry").on("click",(function(e){e.preventDefault();var t=a(e.currentTarget);t.addClass("button-loading");var n=t.data("section");a.ajax({url:n,type:"DELETE",success:function(e){e.error?TVHung.showError(e.message):(window.LaravelDataTables[t.data("parent-table")].row(a('a[data-section="'+n+'"]').closest("tr")).remove().draw(),TVHung.showSuccess(e.message)),t.closest(".modal").modal("hide"),t.removeClass("button-loading")},error:function(e){TVHung.handleError(e),t.removeClass("button-loading")}})})),a(document).on("click",".delete-many-entry-trigger",(function(e){e.preventDefault();var t=a(e.currentTarget),n=t.closest(".table-wrapper").find(".table").prop("id"),o=[];if(a("#"+n).find(".checkboxes:checked").each((function(e,t){o[e]=a(t).val()})),0===o.length)return TVHung.showError(TvhungVariables.languages.tables.please_select_record?TvhungVariables.languages.tables.please_select_record:"Please select at least one record to perform this action!"),!1;a(".delete-many-entry-button").data("href",t.prop("href")).data("parent-table",n).data("class-item",t.data("class-item")),a(".delete-many-modal").modal("show")})),a(".delete-many-entry-button").on("click",(function(e){e.preventDefault();var t=a(e.currentTarget);t.addClass("button-loading");var n=a("#"+t.data("parent-table")),o=[];n.find(".checkboxes:checked").each((function(e,t){o[e]=a(t).val()})),a.ajax({url:t.data("href"),type:"DELETE",data:{ids:o,class:t.data("class-item")},success:function(e){e.error?TVHung.showError(e.message):TVHung.showSuccess(e.message),n.find(".table-check-all").prop("checked",!1),window.LaravelDataTables[t.data("parent-table")].draw(),t.closest(".modal").modal("hide"),t.removeClass("button-loading")},error:function(e){TVHung.handleError(e),t.removeClass("button-loading")}})})),a(document).on("click",".bulk-change-item",(function(t){t.preventDefault();var n=a(t.currentTarget),o=n.closest(".table-wrapper").find(".table").prop("id"),r=[];if(a("#"+o).find(".checkboxes:checked").each((function(e,t){r[e]=a(t).val()})),0===r.length)return TVHung.showError(TvhungVariables.languages.tables.please_select_record?TvhungVariables.languages.tables.please_select_record:"Please select at least one record to perform this action!"),!1;e.loadBulkChangeData(n),a(".confirm-bulk-change-button").data("parent-table",o).data("class-item",n.data("class-item")).data("key",n.data("key")).data("url",n.data("save-url")),a(".modal-bulk-change-items").modal("show")})),a(document).on("click",".confirm-bulk-change-button",(function(e){e.preventDefault();var t=a(e.currentTarget),n=t.closest(".modal").find(".input-value").val(),o=t.data("key"),r=a("#"+t.data("parent-table")),s=[];r.find(".checkboxes:checked").each((function(e,t){s[e]=a(t).val()})),t.addClass("button-loading"),a.ajax({url:t.data("url"),type:"POST",data:{ids:s,key:o,value:n,class:t.data("class-item")},success:function(e){e.error?TVHung.showError(e.message):TVHung.showSuccess(e.message),r.find(".table-check-all").prop("checked",!1),a.each(s,(function(e,a){window.LaravelDataTables[t.data("parent-table")].row(r.find('.checkboxes[value="'+a+'"]').closest("tr")).remove().draw()})),t.closest(".modal").modal("hide"),t.removeClass("button-loading")},error:function(e){TVHung.handleError(e),t.removeClass("button-loading")}})}))}},{key:"loadBulkChangeData",value:function(e){var t=a(".modal-bulk-change-items");a.ajax({type:"GET",url:t.find(".confirm-bulk-change-button").data("load-url"),data:{class:e.data("class-item"),key:e.data("key")},success:function(e){var n=a.map(e.data,(function(e,a){return{id:a,name:e}}));a(".modal-bulk-change-content").html(e.html);var o=t.find("input[type=text].input-value");o.length&&(o.typeahead({source:n}),o.data("typeahead").source=n),TVHung.initResources()},error:function(e){TVHung.handleError(e)}})}},{key:"handleActionsExport",value:function(){a(document).on("click",".export-data",(function(e){var t=a(e.currentTarget),n=t.closest(".table-wrapper").find(".table").prop("id"),o=[];a("#"+n).find(".checkboxes:checked").each((function(e,t){o[e]=a(t).val()})),e.preventDefault(),a.ajax({type:"POST",url:t.prop("href"),data:{"ids-checked":o},success:function(e){var a=document.createElement("a");a.href=e.file,a.download=e.name,document.body.appendChild(a),a.trigger("click"),a.remove()},error:function(e){TVHung.handleError(e)}})}))}}])&&e(n.prototype,o),t}();a(document).ready((function(){new s}))}(jQuery,jQuery.fn.dataTable)})();
