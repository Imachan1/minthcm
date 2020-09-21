<?php
function survey_url_display(Surveys $survey)
{
    if ($survey->status != 'Public') {
        return '';
    }
    global $sugar_config, $current_user;
    $url = $sugar_config['site_url'] . "/index.php?entryPoint=survey&id=" . $survey->id;
    $url_href = $url . '&employee=' . $current_user->id;
    return "<a href='$url_href'>$url</a>";
}
