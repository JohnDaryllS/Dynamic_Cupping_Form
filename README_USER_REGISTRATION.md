# User Registration System

This document describes the new user registration system implemented for the SCA Arabica Cupping Form application.

## Overview

The system now allows users to register themselves instead of requiring admin creation. However, new users must wait for admin approval before they can log in and access the system.

## Features

### 1. User Self-Registration
- Users can create accounts through the registration form
- Registration includes: Full Name, Email, Password, and Password Confirmation
- Passwords are securely hashed using SHA-256
- Email validation and duplicate email checking
- Minimum password length requirement (6 characters)

### 2. Admin Approval System
- New users are created with `is_approved = 0` (pending)
- Admins can approve or reject new user registrations
- Approved users can log in normally
- Rejected users are permanently deleted from the system

### 3. Enhanced Security
- Users cannot log in until approved by an admin
- Clear feedback messages for pending approval status
- Secure password handling and validation

## Database Changes

### New Fields Added to `users` Table:
- `is_approved` (TINYINT(1)): 0 = pending, 1 = approved
- `created_at` (TIMESTAMP): When the user account was created

### Updated SQL Structure:
```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
);
```

## Files Added/Modified

### New Files:
- `register.php` - User registration form
- `update_database.php` - Database update script

### Modified Files:
- `login.php` - Added approval check and registration link
- `dashboard.php` - Added user approval interface
- `index.php` - Added registration button
- `specialty_coffee_depot.sql` - Updated database schema

## Installation Instructions

### 1. Update Database
Run the database update script to add new fields:
```
http://your-domain/update_database.php
```

### 2. Test Registration
1. Visit the registration page: `register.php`
2. Create a new user account
3. Try to log in (should show "pending approval" message)
4. Log in as admin and approve the user
5. User can now log in successfully

## User Flow

### Registration Flow:
1. User visits registration page
2. Fills out registration form
3. Account created with `is_approved = 0`
4. User sees "waiting for approval" message

### Approval Flow:
1. Admin logs into dashboard
2. Sees pending users with "Pending" status
3. Clicks "Approve" or "Reject" button
4. User status updated accordingly

### Login Flow:
1. User attempts to log in
2. System checks `is_approved` status
3. If pending: Shows "pending approval" message
4. If approved: Normal login process
5. If rejected: Account deleted, user must re-register

## Admin Interface

### User Management:
- View all users with their approval status
- Approve pending users
- Reject unwanted registrations
- Edit user information
- Change passwords
- Delete users

### Status Indicators:
- **Approved**: Green badge
- **Pending**: Yellow badge
- **Admin**: Always approved (green badge)

## Security Considerations

1. **Password Security**: All passwords are hashed using SHA-256
2. **Email Validation**: Proper email format validation
3. **Duplicate Prevention**: Email addresses must be unique
4. **Admin Control**: Only admins can approve/reject users
5. **Session Management**: Secure session handling for logged-in users

## Customization

### Styling:
- Registration form uses Bootstrap 5
- Responsive design for mobile devices
- Custom CSS for consistent branding

### Validation Rules:
- Password minimum length: 6 characters
- Required fields: Full Name, Email, Password, Confirm Password
- Email format validation
- Password confirmation matching

## Troubleshooting

### Common Issues:
1. **User can't log in after registration**: Check if admin has approved the account
2. **Database errors**: Run `update_database.php` to add missing fields
3. **Approval buttons not showing**: Ensure user has admin role and is viewing pending users

### Support:
For technical support, check the database connection in `db.php` and ensure all required fields exist in the `users` table.

## Future Enhancements

Potential improvements for the registration system:
1. Email verification before approval
2. Automated approval for certain email domains
3. User profile management
4. Registration analytics and reporting
5. Bulk user import/export functionality
