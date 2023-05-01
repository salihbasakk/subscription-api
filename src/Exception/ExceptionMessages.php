<?php

namespace App\Exception;

class ExceptionMessages
{
    const LANGUAGE_NOT_FOUND = 'System does not support your requested language';
    const OPERATING_SYSTEM_NOT_FOUND = 'System does not support your requested operating system';
    const APP_NOT_FOUND = 'System does not support your requested app';
    const SUBSCRIPTION_NOT_FOUND = 'Subscription not found';
    const DEVICE_APP_OS_DO_NOT_MATCH = 'Your device and requested app operating system do not match you can not subscribe.';
}