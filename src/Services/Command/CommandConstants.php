<?php

namespace App\Services\Command;

class CommandConstants
{
    CONST MSG_ERROR     = -1;
    CONST MSG_INFO      = 0;
    CONST MSG_SUCCESS   = 1;
    CONST MSG_WARNING   = 2;

    CONST LOGGER_COMMENT            = '<comment>></comment> <info>%s</info>';
    CONST REMOVING_MESSAGE_TEXT     = '<comment>></comment> <info>Removing "%s"</info>';
}
