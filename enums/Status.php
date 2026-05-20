<?php
enum Status: string{
    case PENDING = 'pending';
    case ASSIGNED = 'assigned';
    case RESOLVED = 'resolved';
}