/* 
 * Dropper v1.0.1 - 2015-04-04 
 * A jQuery plugin for simple drag and drop uploads. Part of the Formstone Library. 
 * http://classic.formstone.it/dropper/ 
 * 
 * Copyright 2015 Ben Plum; MIT Licensed 
 */

; (function ($, window)
{
    "use strict";

    var supported = (window.File && window.FileReader && window.FileList);

    /**
	 * @options
	 * @param action [string] "Where to submit uploads"
	 * @param label [string] <'Drag and drop files or click to select'> "Dropzone text"
	 * @param maxQueue [int] <2> "Number of files to simultaneously upload"
	 * @param maxSize [int] <5242880> "Max file size allowed"
	 * @param postData [object] "Extra data to post with upload"
	 * @param postKey [string] <'file'> "Key to upload file as"
	 */

    var options = {
        action: "",
        label: "拖拽文件或者选择文件",
        maxQueue: 10,
        maxSize: 209715200, // 20 mb
        postData: {},
        postKey: "file"
    };

    /**
	 * @events
	 * @event start.dropper ""
	 * @event complete.dropper ""
	 * @event fileStart.dropper ""
	 * @event fileProgress.dropper ""
	 * @event fileComplete.dropper ""
	 * @event fileError.dropper ""
	 */

    var pub = {

        /**
		 * @method
		 * @name defaults
		 * @description Sets default plugin options
		 * @param opts [object] <{}> "Options object"
		 * @example $.dropper("defaults", opts);
		 */
        defaults: function (opts)
        {
            options = $.extend(options, opts || {});

            return (typeof this === 'object') ? $(this) : true;
        }
    };

    /**
	 * @method private
	 * @name _init
	 * @description Initializes plugin
	 * @param opts [object] "Initialization options"
	 */
    function _init(opts)
    {
        var $items = $(this);

        if (supported)
        {
            // Settings
            opts = $.extend({}, options, opts);

            // Apply to each element
            for (var i = 0, count = $items.length; i < count; i++)
            {
                _build($items.eq(i), opts);
            }
        }

        return $items;
    }

    /**
	 * @method private
	 * @name _build
	 * @description Builds each instance
	 * @param $nav [jQuery object] "Target jQuery object"
	 * @param opts [object] <{}> "Options object"
	 */
    function _build($dropper, opts)
    {
        opts = $.extend({}, opts, $dropper.data("dropper-options"));

        var html = "";

        html += '<div class="dropper-dropzone">';

        //Mark begin
        if ($dropper.next().val() != '' && opts.label!="+")
        {
            html += $dropper.next().val() + " - <a class='delete-file' href='javascript:void(0);'>删除</a> | <a class='open-file' href='javascript:void(0);'>预览</a>";

            if (opts.crop)
            {
                html += " | <a class='crop-file' href='javascript:void(0);'>修图</a>"
            }
        }
        else
        {
            html += opts.label;
        }
        //Mark end

        html += '</div>';
        html += '<input class="dropper-input" type="file"';
        if (opts.maxQueue > 1)
        {
            html += ' multiple';
        }
        html += '>';

        $dropper.addClass("dropper")
				.append(html);

        var data = $.extend({
            $dropper: $dropper,
            $input: $dropper.find(".dropper-input"),
            queue: [],
            total: 0,
            uploading: false
        }, opts);

        $dropper.on("click.dropper", ".dropper-dropzone", data, _onClick)
				.on("dragenter.dropper", data, _onDragEnter)
				.on("dragover.dropper", data, _onDragOver)
				.on("dragleave.dropper", data, _onDragOut)
				.on("drop.dropper", ".dropper-dropzone", data, _onDrop)
				.data("dropper", data);

        data.$input.on("change.dropper", data, _onChange);

        //Mark begin
        $dropper.find(".dropper-dropzone .delete-file").click(function (e2)
        {
            e2.stopPropagation();
            e2.preventDefault();

            if (confirm('确定删除该文件吗？'))
            {
                $.get("/ajax/delete.php?f=" + opts.postData.path + $dropper.next().val(), function ()
                {
                    $dropper.find(".dropper-dropzone").html(opts.label);

                    $dropper.next().val('');
                });
            };
        });

        $dropper.find(".dropper-dropzone .open-file").click(function (e2)
        {
            e2.stopPropagation();
            e2.preventDefault();

            window.open(opts.postData.path + $dropper.next().val());

        });

        $dropper.find(".dropper-dropzone .crop-file").click(function (e2)
        {
            e2.stopPropagation();
            e2.preventDefault();

            $("#cropbox").attr("src", opts.postData.path + $dropper.next().val());

            $.fancybox.open('#inline1');

            $('#cropbox').Jcrop({
                boxWidth: 600,
                setSelect: [10, 10, 360, 360],
                aspectRatio: 1,
                onSelect: updateCoords
            }, function ()
            {
                jcrop_api = this;
                jcrop_api.setImage(opts.postData.path + $dropper.next().val());
            });

            $('#x').val(10);
            $('#y').val(10);
            $('#w').val(360);
            $('#h').val(360);
            $('#p').val(opts.postData.path);
            $('#f').val($dropper.next().val());
        });

        if (opts.label == "+")
        {
            if (!opts.postData.normal)
            {
                var s = $("#pic_box").parent().find("textarea").val();

                if (s)
                {
                    var temp = s.split('\n');

                    for (var k = 0; k < temp.length ; k++)
                    {
                        $("#pic_box").append('<div class="pic_item"><img src="/upload/exercise/img/' + temp[k] + '" /> <a href="javascript:void(0);" onclick="delete_pic(this);">删除</a></div>');
                    }
                }
            }
        }
        //Mark end
    }

    /**
	 * @method private
	 * @name _onClick
	 * @description Handles click to dropzone
	 * @param e [object] "Event data"
	 */
    function _onClick(e)
    {
        e.stopPropagation();
        e.preventDefault();

        var data = e.data;

        //Mark begin
        if (data.$dropper.next().val() == "" || data.label == "+")
        {
            data.$input.trigger("click");
        }
        else
        {
            alert("请先删除旧文件后，再上传新文件.");
        }
        //Mark end
    }

    /**
	 * @method private
	 * @name _onChange
	 * @description Handles change to hidden input
	 * @param e [object] "Event data"
	 */
    function _onChange(e)
    {
        e.stopPropagation();
        e.preventDefault();

        var data = e.data,
			files = data.$input[0].files;

        if (files.length)
        {
            _handleUpload(data, files);
        }
    }

    /**
	 * @method private
	 * @name _onDragEnter
	 * @description Handles dragenter to dropzone
	 * @param e [object] "Event data"
	 */
    function _onDragEnter(e)
    {
        e.stopPropagation();
        e.preventDefault();

        var data = e.data;

        data.$dropper.addClass("dropping");
    }

    /**
	 * @method private
	 * @name _onDragOver
	 * @description Handles dragover to dropzone
	 * @param e [object] "Event data"
	 */
    function _onDragOver(e)
    {
        e.stopPropagation();
        e.preventDefault();

        var data = e.data;

        data.$dropper.addClass("dropping");
    }

    /**
	 * @method private
	 * @name _onDragOut
	 * @description Handles dragout to dropzone
	 * @param e [object] "Event data"
	 */
    function _onDragOut(e)
    {
        e.stopPropagation();
        e.preventDefault();

        var data = e.data;

        data.$dropper.removeClass("dropping");
    }

    /**
	 * @method private
	 * @name _onDrop
	 * @description Handles drop to dropzone
	 * @param e [object] "Event data"
	 */
    function _onDrop(e)
    {
        e.preventDefault();

        var data = e.data,
			files = e.originalEvent.dataTransfer.files;

        data.$dropper.removeClass("dropping");

        //Mark begin
        if (data.$dropper.next().val() == "" || data.label == "+")
        {
            _handleUpload(data, files);
        }
        else
        {
            alert("请先删除旧文件后，再上传新文件.");
        }
        //Mark end
    }

    /**
	 * @method private
	 * @name _handleUpload
	 * @description Handles new files
	 * @param data [object] "Instance data"
	 * @param files [object] "File list"
	 */
    function _handleUpload(data, files)
    {
        var newFiles = [];

        for (var i = 0; i < files.length; i++)
        {
            var file = {
                index: data.total++,
                file: files[i],
                name: files[i].name,
                size: files[i].size,
                started: false,
                complete: false,
                error: false,
                transfer: null
            };

            newFiles.push(file);
            data.queue.push(file);
        }

        if (!data.uploading)
        {
            $(window).on("beforeunload.dropper", function ()
            {
                return 'You have uploads pending, are you sure you want to leave this page?';
            });

            data.uploading = true;
        }

        data.$dropper.trigger("start.dropper", [newFiles]);

        //Mark begin
        data.$dropper.find(".dropper-dropzone").text("0%")
        //Mark end

        _checkQueue(data);
    }

    /**
	 * @method private
	 * @name _checkQueue
	 * @description Checks and updates file queue
	 * @param data [object] "Instance data"
	 */
    function _checkQueue(data)
    {
        var transfering = 0,
			newQueue = [];

        // remove lingering items from queue
        for (var i in data.queue)
        {
            if (data.queue.hasOwnProperty(i) && !data.queue[i].complete && !data.queue[i].error)
            {
                newQueue.push(data.queue[i]);
            }
        }

        data.queue = newQueue;

        for (var j in data.queue)
        {
            if (data.queue.hasOwnProperty(j))
            {
                if (!data.queue[j].started)
                {
                    var formData = new FormData();

                    formData.append(data.postKey, data.queue[j].file);

                    for (var k in data.postData)
                    {
                        if (data.postData.hasOwnProperty(k))
                        {
                            formData.append(k, data.postData[k]);
                        }
                    }

                    formData.append("fileIndex", j);

                    _uploadFile(data, data.queue[j], formData);
                }

                transfering++;

                if (transfering >= data.maxQueue)
                {
                    return;
                } else
                {
                    i++;
                }
            }
        }

        if (transfering === 0)
        {
            $(window).off("beforeunload.dropper");

            data.uploading = false;

            data.$dropper.trigger("complete.dropper");
        }
    }

    /**
	 * @method private
	 * @name _uploadFile
	 * @description Uploads file
	 * @param data [object] "Instance data"
	 * @param file [object] "Target file"
	 * @param formData [object] "Target form"
	 */
    function _uploadFile(data, file, formData)
    {
        if (file.size >= data.maxSize)
        {
            file.error = true;
            data.$dropper.trigger("fileError.dropper", [file, "文件太大"]);

            data.$dropper.find(".dropper-dropzone").html("<span style='color:red'>错误: 文件太大</span><br>" + data.label);

            _checkQueue(data);
        } else
        {
            file.started = true;
            file.transfer = $.ajax({
                url: data.action,
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                cache: false,
                xhr: function ()
                {
                    var $xhr = $.ajaxSettings.xhr();

                    if ($xhr.upload)
                    {
                        $xhr.upload.addEventListener("progress", function (e)
                        {
                            var percent = 0,
								position = e.loaded || e.position,
								total = e.total;

                            if (e.lengthComputable)
                            {
                                percent = Math.ceil(position / total * 100);
                            }

                            data.$dropper.trigger("fileProgress.dropper", [file, percent]);

                            //Mark begin
                            data.$dropper.find(".dropper-dropzone").text(percent + "%");
                            //Mark end

                        }, false);
                    }

                    return $xhr;
                },
                beforeSend: function (e)
                {
                    data.$dropper.trigger("fileStart.dropper", [file]);
                },
                success: function (response, status, jqXHR)
                {
                    file.complete = true;
                    data.$dropper.trigger("fileComplete.dropper", [file, response]);

                    //Mark begin
                    if (data.label != "+")
                    {
                        data.$dropper.next().val(response);
                        data.$dropper.find(".dropper-dropzone").html(response + " - <a class='delete-file' href='javascript:void(0);'>删除</a> | <a class='open-file' href='javascript:void(0);'>预览</a>");

                        if (data.crop)
                        {
                            data.$dropper.find(".dropper-dropzone").html(response + " - <a class='delete-file' href='javascript:void(0);'>删除</a> | <a class='open-file' href='javascript:void(0);'>预览</a> | <a class='crop-file' href='javascript:void(0);'>修图</a>");
                        }

                        data.$dropper.find(".dropper-dropzone .delete-file").click(function (e2)
                        {
                            e2.stopPropagation();
                            e2.preventDefault();

                            if (confirm('确定删除该文件吗？'))
                            {
                                $.get("/ajax/delete.php?f=" + data.postData.path + response, function ()
                                {
                                    data.$dropper.find(".dropper-dropzone").html(data.label);

                                    data.$dropper.next().val('');
                                });
                            };
                        });

                        data.$dropper.find(".dropper-dropzone .open-file").click(function (e2)
                        {
                            e2.stopPropagation();
                            e2.preventDefault();

                            window.open(data.postData.path + response);

                        });

                        data.$dropper.find(".dropper-dropzone .crop-file").click(function (e2)
                        {
                            e2.stopPropagation();
                            e2.preventDefault();

                            $("#cropbox").attr("src", data.postData.path + response);

                            $.fancybox.open('#inline1');

                            $('#cropbox').Jcrop({
                                boxWidth: 600,
                                setSelect: [10, 10, 360, 360],
                                aspectRatio: 1,
                                onSelect: updateCoords
                            }, function ()
                            {
                                jcrop_api = this;
                                jcrop_api.setImage(data.postData.path + response);
                            });

                            $('#x').val(10);
                            $('#y').val(10);
                            $('#w').val(360);
                            $('#h').val(360);
                            $('#p').val(data.postData.path);
                            $('#f').val(response);

                        });
                    }
                    else
                    {
                        if (!data.postData.normal)
                        {
                            data.$dropper.find(".dropper-dropzone").html("+");

                            $("#pic_box").append('<div class="pic_item"><img src="/upload/exercise/img/' + response + '" /> <a href="javascript:void(0);" onclick="delete_pic(this);">删除</a></div>');

                            var imgs = $("#pic_box img").map(function () { return $(this).attr("src").replace("/upload/exercise/img/", "") }).get().join('\n');

                            $("#pic_box").parent().find("textarea").val(imgs);
                        }
                        else
                        {
                            data.$dropper.find(".dropper-dropzone").html("+");
                        }
                    }
                    //Mark end

                    _checkQueue(data);
                },
                error: function (jqXHR, status, error)
                {
                    file.error = true;
                    data.$dropper.trigger("fileError.dropper", [file, error]);

                    data.$dropper.find(".dropper-dropzone").html("<span style='color:red'>错误: " + error + "</span><br>" + data.label);

                    _checkQueue(data);
                }
            });
        }
    }

    $.fn.dropper = function (method)
    {
        if (pub[method])
        {
            return pub[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method)
        {
            return _init.apply(this, arguments);
        }
        return this;
    };

    $.dropper = function (method)
    {
        if (method === "defaults")
        {
            pub.defaults.apply(this, Array.prototype.slice.call(arguments, 1));
        }
    };
})(jQuery, window);

function delete_pic(el)
{
    //if (confirm('确定删除该文件吗？'))
    {
        $.get("/ajax/delete.php?f=" + $(el).prev().attr("src"), function ()
        {
            $(el).parent().remove();

            var imgs = $("#pic_box img").map(function () { return $(this).attr("src").replace("/upload/exercise/img/", "") }).get().join('\n');

            $("#pic_box").parent().find("textarea").val(imgs);
        });
    };
}
