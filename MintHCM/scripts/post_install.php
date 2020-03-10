<?php

function post_install()
{
    $this->translateRoles();
    $this->translateDashboardManagerAndBackups();
    $this->translateDashboards();
    $this->translateReports();
}
