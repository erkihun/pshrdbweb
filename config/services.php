<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'sentry' => [
        'dsn' => env('SENTRY_DSN'),
        'environment' => env('SENTRY_ENVIRONMENT', env('APP_ENV', 'production')),
    ],

    'sms' => [
        'enabled' => (bool) env('SMS_ENABLED', false),
        'base_url' => env('SMS_BASE_URL'),
        'api_key' => env('SMS_API_KEY'),
        'sender_id' => env('SMS_SENDER_ID'),
        'templates' => [
        'ticket_created' => env('SMS_TEMPLATE_TICKET_CREATED', 'Your ticket {reference_code} has been created.'),
        'ticket_replied' => env('SMS_TEMPLATE_TICKET_REPLIED', 'Your ticket {reference_code} has a new reply. Status: {status}.'),
        'service_request_status' => env('SMS_TEMPLATE_SERVICE_REQUEST_STATUS', 'Your request {reference_code} status: {status}.'),
        'appointment_booked' => env('SMS_TEMPLATE_APPOINTMENT_BOOKED', 'Your appointment {reference_code} for {service} is confirmed for {date} at {time}.'),
        'appointment_status' => env('SMS_TEMPLATE_APPOINTMENT_STATUS', 'Your appointment {reference_code} status is {status}.'),
        'appointment_reminder' => env('SMS_TEMPLATE_APPOINTMENT_REMINDER', 'Reminder: your appointment {reference_code} for {service} is tomorrow at {time}.'),
        'document_request_submitted' => env('SMS_TEMPLATE_DOCUMENT_REQUEST_SUBMITTED', 'Your document request {reference_code} has been received.'),
            'document_request_status' => env('SMS_TEMPLATE_DOCUMENT_REQUEST_STATUS', 'Your document request {reference_code} status: {status}.'),
        ],
    ],

];
