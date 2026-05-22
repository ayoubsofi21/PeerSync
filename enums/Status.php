<?php

enum Status: string
{
    case PENDING = 'PENDING';
    case ASSIGNED = 'ASSIGNED';
    case RESOLVED = 'RESOLVED';
}