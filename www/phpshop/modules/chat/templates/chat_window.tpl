<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
    <HEAD>
        <TITLE>PHPShop.Chat Free</TITLE>
        <META http-equiv="Content-Type" content="text-html; charset=windows-1251">
        <LINK href="./templates/skin/@chat_mod_skin@.css" type="text/css" rel="stylesheet">
        <SCRIPT language="JavaScript" type="text/javascript" src="./lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <SCRIPT language="JavaScript" type="text/javascript" src="./ajax/phpshopchat.js"></SCRIPT>
        <SCRIPT src="./lib/soundmanager2/soundmanager2-nodebug-jsmin.js"></SCRIPT>
        <script type="text/javascript" src="./lib/swfupload/swfupload.js"></script>
        <script type="text/javascript" src="./lib/swfupload/swfupload.queue.js"></script>
        <script type="text/javascript" src="./lib/swfupload/upload/js/fileprogress.js"></script>
        <script type="text/javascript" src="./lib/swfupload/upload/js/handlers.js"></script>
        <SCRIPT>
            soundManager.setup({
                url: './lib/soundmanager2/',
                debugMode: false,
                onready: function() {

                    mySound = soundManager.createSound({
                        id: 'aSound',
                        url: '@chat_mod_sound@'
                    });
                }
            });

            var swfu;
            
            function PHPShop_upload() {
                var settings = {
                    flash_url: "./lib/swfupload/swfupload.swf",
                    flash9_url: "./lib/swfupload/swfupload_fp9.swf",
                    upload_url: "./lib/swfupload/upload/upload.php",
                    post_params: {"PHPSESSID": "@php echo session_id(); php@"},
                    file_size_limit: "3 MB",
                    file_types: "*.jpg;*.gif;*.png;*.JPG;*.PNG",
                    file_types_description: "Web Image Files",
                    file_upload_limit: 3,
                    file_queue_limit: 1,
                    custom_settings: {
                        progressTarget: "fsUploadProgress",
                        cancelButtonId: "btnCancel"
                    },
                    debug: false,
                    // Button settings
                    button_image_url: "./lib/swfupload/upload/SmallSpyGlassWithTransperancy_17x18.png",
                    button_width: "130",
                    button_height: "18",
                    button_placeholder_id: "spanButtonPlaceHolder",
                    button_text: '<span class="theFont">Прикрепить файл</span>',
                    button_text_style: ".theFont { font-family: Helvetica, Arial, sans-serif; font-size: 12pt; }",
                    button_text_left_padding: 18,
                    button_text_top_padding: 0,
                    // The event handler functions are defined in handlers.js
                    swfupload_preload_handler: preLoad,
                    swfupload_load_failed_handler: loadFailed,
                    file_queued_handler: fileQueued,
                    file_queue_error_handler: fileQueueError,
                    file_dialog_complete_handler: fileDialogComplete,
                    upload_start_handler: uploadStart,
                    upload_progress_handler: uploadProgress,
                    upload_error_handler: uploadError,
                    upload_success_handler: uploadSuccess,
                    upload_complete_handler: uploadComplete,
                    queue_complete_handler: queueComplete	// Queue plugin event
                };

                swfu = new SWFUpload(settings);
            };

        </SCRIPT>


    </HEAD>
    <BODY onLoad="PHPShopChat_ping();PHPShopChat_email();PHPShop_upload();">
        <table width="100%" height="100%">
            <tr valign="top" >
                <td height="70%"> <div name="chat_mod_content" id="chat_mod_content">@chat_mod_content@</div>
                </td>
            <tr>
            <tr valign="top" >
                <td height="10%">
                    <textarea name="chat_mod_user_text" id="chat_mod_user_text" @chat_mod_disable@></textarea>
                    <div style="float:left">
                        <form id="form1"  method="post" enctype="multipart/form-data">

                            <div class="fieldset flash" id="fsUploadProgress" style="display:none">
                                <span class="legend">Upload Queue</span>
                            </div>
                            <div id="divStatus" style="display:none">0 Files Uploaded</div>
                            <div>
                               <div style="border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 2px;float:left">
                    <span id="spanButtonPlaceHolder"></span></div>
                                <span style="display:none"><input id="btnCancel" type="button" value="Отменить" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 27px;" /></span>
                            </div>
                        </form>
                    </div>
                    <div style="float:left;padding-left:5px"> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title=Смеется onclick="emoticon(':-D');" alt=Смеется src="templates/smiley/grin.gif" border=0> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title=Улыбается onclick="emoticon(':)');" alt=Улыбается src="templates/smiley/smile3.gif" border=0> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title=Грустный onclick="emoticon(':(');" alt=Грустный src="templates/smiley/sad.gif" border=0> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title="В шоке" onclick="emoticon(':shock:');" alt="В шоке" src="templates/smiley/shok.gif" border=0> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title=Самоуверенный onclick="emoticon(':cool:');" alt=Самоуверенный src="templates/smiley/cool.gif" border=0> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title=Стесняется onclick="emoticon(':blush:');" alt=Стесняется src="templates/smiley/blush2.gif" border=0> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title=Танцует onclick="emoticon(':dance:');" alt=Танцует src="templates/smiley/dance.gif" border=0> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title=Счастлив onclick="emoticon(':rad:');" alt=Счастлив src="templates/smiley/happy.gif" border=0> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title="Под столом" onclick="emoticon(':lol:');" alt="Под столом" src="templates/smiley/lol.gif" border=0> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title="В замешательстве" onclick="emoticon(':huh:');" alt="В замешательстве" src="templates/smiley/huh.gif" border=0> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title=Загадочный onclick="emoticon(':rolly:');" alt=Загадочный src="templates/smiley/rolleyes.gif" border=0> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title=Злой onclick="emoticon(':thuf:');" alt=Злой src="templates/smiley/threaten.gif" border=0> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title="Показывает язык" onclick="emoticon(':tongue:');" alt="Показывает язык" src="templates/smiley/tongue.gif" border=0> 
                       <!-- <IMG onmouseover="this.style.cursor = 'pointer';" title=Умничает onclick="emoticon(':smart:');" alt=Умничает src="templates/smiley/umnik2.gif" border=0> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title=Запутался onclick="emoticon(':wacko:');" alt=Запутался src="templates/smiley/wacko.gif" border=0> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title=Сожалеет onclick="emoticon(':sorry:');" alt=Сожалеет src="templates/smiley/sorry.gif" border=0>
                        <IMG onmouseover="this.style.cursor = 'pointer';" title="Нет Нет" onclick="emoticon(':nono:');" alt="Нет Нет" src="templates/smiley/nono.gif" border=0> 
                        <IMG onmouseover="this.style.cursor = 'pointer';" title=Скептический onclick="emoticon(':dry:');" alt=Скептический src="templates/smiley/dry.gif" border=0> </DIV>
                    -->
                       </div>
                </td>
            </tr>
            <tr valign="top">
                <td height="100">
                    <div id="help"><img src="./templates/help.png" align="absmiddle" hspace="3">Используйте сочетание клавиш <b>CTRL + Enter</b> для быстрого ответа</div>
                    <div id="exit">
                        <button onclick="PHPShopChat_exit();">
                            <img src="./templates/exit.gif" align="absmiddle" hspace="5">Выход
                        </button>
                    </div>
                    <div id="post">
                        <button onclick="PHPShopChat_write('@chat_mod_disable@');">
                            <img src="./templates/post.gif" align="absmiddle" hspace="5" id="chat_mod_send_button_icon"><span id="chat_mod_send_button_text">Отправить</span>
                        </button>
                        <input type="hidden"  value="@chat_mod_product_name@" id="chat_mod_product_name" name="chat_mod_product_name">
                        <input type="hidden"  value="@chat_mod_dir@" id="chat_mod_dir" name="chat_mod_dir">
                        <input type="hidden"  value="@chat_mod_time@" id="chat_mod_time" name="chat_mod_time">
                    </div>

                </td>
            </tr>
        </table>
    </BODY>
</HTML>