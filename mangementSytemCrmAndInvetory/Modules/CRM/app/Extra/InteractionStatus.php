<?php
namespace Modules\CRM\Extra;
enum InteractionStatus:string
{
    case Pending = "pending";
    case InProgress = "in_progress";
    case Resolved = "resolved";
}
