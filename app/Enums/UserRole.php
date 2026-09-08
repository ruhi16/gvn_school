<?php

namespace App\Enums;

class UserRole
{
    const ADMIN = 'admin';
    const STAFF = 'staff';
    const TEACHER = 'teacher';
    const STUDENT = 'student';
    const VISITOR = 'visitor';

    public static function getRoles()
    {
        return [
            self::ADMIN => 'Admin',
            self::STAFF => 'Staff',
            self::TEACHER => 'Teacher',
            self::STUDENT => 'Student',
            self::VISITOR => 'Visitor',
        ];
    }
}