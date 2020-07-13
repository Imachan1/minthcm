<?php

class RelatedCommentsClass
{

    protected function getRecordId()
    {
        return $_GET['record'];
    }

    public function display_comments($bean)
    {
        if (empty($bean->id)) {
            return '';
        }
        $module_name = $bean->object_name; //get_class($bean); // Customs beans

        global $current_user;

        $html = $this->display_comments_head($this->getRecordId(), $module_name, $current_user->id);

        $comments = $bean->get_linked_beans('comments', 'Comments');
        if (!$comments) { //|| is_null($bean->id)
            $html .= $this->quick_edit_comments();
            return $html;
        }

        $html .= $this->divEnd();
        $main_comments = [];
        foreach ($comments as $comment) {
            if (!$comment->reply_to_id) {
                $main_comments[] = $comment;
            }
        }
        $this->sortComments($main_comments);

        foreach ($main_comments as $comment) {
            $html .= $this->display_single_comment($comment);
        }
        $html .= $this->divEnd();
        $html .= $this->quick_edit_comments();

        return $html;
    }
    protected function divEnd()
    {
        return '</div>';
    }
    protected function sortComments(&$comments)
    {
        usort(
            $comments,
            function ($a, $b) {
                $aDate = $a->fetched_row['date_entered'];
                $bDate = $b->fetched_row['date_entered'];
                if ($aDate < $bDate) {
                    return -1;
                } elseif ($aDate > $bDate) {
                    return 1;
                }

                return 0;
            }
        );
    }

    public function display_single_comment($comment, $reply = false)
    {

        /*if assigned user*/
        if ($comment->assigned_user_id) {
            $replies = $comment->get_linked_beans('replies', 'Comments');
            if (!empty($replies)) {
                $this->sortComments($replies);
            }

            $name = $comment->getAuthorFullName();
            $content = nl2br(html_entity_decode($comment->description));

            $img = $this->imagePhoto(!empty($comment->getAuthorPhoto()) ? $comment->assigned_user_id : ''); //$comment->assigned_user_id
            $header = $name . ': ' . $comment->date_entered;
            $html = <<<HTML
            <div class="comment-container" data-comment-id="$comment->id"
               style="display: grid; grid-template-columns: repeat(10, 1fr); margin: 10px auto; grid-auto-rows: minmax(3vw, auto); grid-gap: 10px; background: #cccccc; border-radius: 4px; padding: 10px;">
                <div class="" style=" grid-column: 1; justify-items: center; align-items: center;">$img</div>
                <div class="comment" style="grid-column: 2/11;">
                    <div class="header" style="border-bottom: 1px solid #aaaaaa;">
                        $header
                        <span class="action-menu" style="float: right;"><a class="reply" style="cursor: pointer;">Reply</a></span>
                    </div>
                <div class="comment-text">$content</div>
            </div>
        </div>
HTML;
            if ($reply) {
                $html = <<<HTML
                <div class="content-container"
                    style="display: grid; grid-template-columns: repeat(10, 1fr); margin: 10px auto; grid-auto-rows: minmax(3vw, auto); grid-gap: 10px; background: #cccccc; border-radius: 4px; padding: 10px;">
                    <div class="" style=" grid-column: 2; justify-items: center; align-items: center;">$img</div>
                    <div class="comment" style="grid-column: 3/11;">
                        <div class="header" style="border-bottom: 1px solid #aaaaaa;">$header</div>
                        <div class="comment-text">$content</div>
                    </div>
                </div>
HTML;
            }
            if (!empty($replies)) {
                $GLOBALS['log']->fatal("replies exists");
                foreach ($replies as $reply) {
                    $html .= display_single_comment($reply, true);
                }
            }
            return $html;
        }
    }

    protected function imagePhoto($assignedUserId)
    {
        $imageURL = "themes/SuiteP/images/no_photo.png";
        if (empty($assignedUserId)) {
            $imageURL = "index.php?entryPoint=download&id={$assignedUserId}_photo&type=Users";
        }

        return $img = '<span style="width: 3vw; height: 3vw; position: relative; border-radius: 50%; overflow: hidden; border:2px solid #aaaaaa;">' .
            '<img src="' . $imageURL . '" style="max-width: 100%; vertical-align: middle; position: absolute; top: 50%; left: 50%; transform: translateX(-50%) translateY(-50%);" >' .
            '</span>';
    }

    public function quick_edit_comments()
    {
        global $action;
        global $currentModule;
        global $current_language;
        $mod_strings = return_module_language($current_language, 'News');

        //on DetailView only
        if ($action !== 'DetailView') {
            return;
        }

        //current record id
        $record = $_GET['record'];

        //Get Users roles
        require_once 'modules/ACLRoles/ACLRole.php';
        $user = $GLOBALS['current_user'];
        $id = $user->id;
        $acl = new ACLRole();
        $roles = $acl->getUserRoles($id);

        $sendLbl = translate('LBL_SEND_BUTTON_LABEL');
        $your_comment_str = translate('LBL_YOUR_COMMENT');

        $html = <<<HTML
    <div class="comment-form" style="margin: 10px auto; width: 100%;">
        <form id='comments' enctype="multipart/form-data">
            <div><label for="comment_text">{$your_comment_str}</label></div>
            <div style="margin: 5px auto; width: 100%;"><textarea id="comment_text" name="comment_text" cols="80" rows="4"></textarea></div>
            <input type='button' value='$sendLbl' onclick="addComment('$record')" title="$sendLbl" name="button" />
        </form>
    </div>
HTML;

        return $html;
    }
    public function display_comments_head($record, $module_name, $current_user_id)
    {

        $html = <<<HTML
    <script>
    $(document).on('click', "a.reply", function() {
        var parent_id = $(this).parents("div.comment-container").attr("data-comment-id");
        var reply_form = '<div class="reply-form" style="margin: 10px auto; width: 100%;"><form enctype="multipart/form-data">'
        + '<div><label for="comment_text">'+SUGAR.language.get('app_strings', 'LBL_YOUR_REPLY')+'</label></div>'
        + '<div style="margin: 5px auto; width: 100%;"><textarea id="comment_text" name="comment_text" cols="80" rows="4"></textarea></div>'
        + '<input type="button" value="'+SUGAR.language.get('app_strings', 'LBL_SEND_BUTTON_LABEL')+'" onclick="addComment(\'{$record}\',\''+ parent_id +'\')" title="'+SUGAR.language.get('app_strings', 'LBL_SEND_BUTTON_LABEL')+'" name="button"> </input>'
        + '</br></form></div>';

        if ($(this).parents("div.comment-container").nextAll().filter("div.reply-form").length == 0) {
            $(this).parents("div.comment-container").after(reply_form);
        }
    });

    function addComment(record, parent_id = null){
        loadingMessgPanl = new YAHOO.widget.SimpleDialog('loading', {
            width: '200px',
            close: true,
            modal: true,
            visible: true,
            fixedcenter: true,
            constraintoviewport: true,
            draggable: false
        });
        loadingMessgPanl.setHeader(SUGAR.language.get('app_strings', 'LBL_EMAIL_PERFORMING_TASK'));
        loadingMessgPanl.setBody(SUGAR.language.get('app_strings', 'LBL_EMAIL_ONE_MOMENT'));
        loadingMessgPanl.render(document.body);
        loadingMessgPanl.show();

        var comment_text = encodeURIComponent(document.getElementById('comment_text').value);

        var params = "record="+record+"&module=Comments&return_module={$module_name}&action=Save&return_id="+record+"&return_action=DetailView&relate_to={$module_name}&relate_id="+record+"&offset=1&description="
        + comment_text + "&parent_id=" + record + "&parent_type={$module_name}&name=" + comment_text.substring(0,255) + "&assigned_user_id={$current_user_id}";
        if (parent_id != null) {
            params += '&reply_to_id=' + parent_id;
        }

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.open("POST", "index.php", true);

        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.setRequestHeader("Content-length", params.length);
        xmlhttp.setRequestHeader("Connection", "close");

        //When button is clicked
        xmlhttp.onreadystatechange = function() {
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                $("[data-id=LBL_PANEL_COMMENTS]").load("index.php?module={$module_name}&action=DetailView&record="+record + " [data-id=LBL_PANEL_COMMENTS]", function(){
                    loadingMessgPanl.hide();
                });
            }
        }
        xmlhttp.send(params);
    }
</script>
HTML;
        return $html;

    }

}
