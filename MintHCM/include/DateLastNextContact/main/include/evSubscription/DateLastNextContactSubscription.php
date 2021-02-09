<?php

require_once('include/evSubscription/AbstractSubscriptionValidator.php');

class DateLastNextContactSubscription extends AbstractSubscriptionValidator
{
    const PACKAGE_ID = "date-last-next-contacts";
}

function getDLNCaccess()
{
    return json_decode(DateLastNextContactSubscription::validateKey());
}