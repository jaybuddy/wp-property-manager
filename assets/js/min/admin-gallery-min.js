!function($){"use strict";$(document).ready(function(){if(!e)var e=$(document).find(".redux-container-gallery");$(e).each(function(){var e=$(this),a=e;e.hasClass("redux-field-container")||(a=e.parents(".redux-field-container:first")),a.hasClass("redux-field-init")&&(a.removeClass("redux-field-init"),e.on({click:function(e){var a=$(this).closest("fieldset");if("clear-gallery"===e.currentTarget.id){var i=a.find(".gallery_values").val("");return void a.find(".screenshot").html("")}if("undefined"!=typeof wp&&wp.media&&wp.media.gallery){e.preventDefault();var l=$(this),r=a.find(".gallery_values").val(),n;n=r?'[gallery ids="'+r+'"]':'[gallery ids="0"]';var t=wp.media.gallery.edit(n);return t.state("gallery-edit").on("update",function(e){a.find(".screenshot").html("");var i,l="",r,n=e.models.map(function(e){return i=e.toJSON(),r="undefined"!=typeof i.sizes.thumbnail?i.sizes.thumbnail.url:i.url,l="<a class='of-uploaded-image' href='"+r+"'><img class='redux-option-image' src='"+r+"' alt='' /></a>",a.find(".screenshot").append(l),e.id});a.find(".gallery_values").val(n.join(","))}),!1}}},".gallery-attachments"))})})}(jQuery);