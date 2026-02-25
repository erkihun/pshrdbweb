<?php

return [
    'index' => [
        'title' => 'Partners',
        'description' => 'Manage external partnerships.',
        'filters' => [
            'search' => 'Search',
            'placeholder' => 'Partner name or country',
            'status' => 'Status',
            'all' => 'All',
        ],
        'table' => [
            'partner' => 'Partner',
            'type' => 'Type',
            'location' => 'Location',
            'contact' => 'Contact',
            'status' => 'Status',
            'actions' => 'Actions',
        ],
        'empty' => 'No partners found.',
        'actions' => [
            'delete_confirm' => 'Remove partner?',
        ],
    ],
    'create' => [
        'title' => 'Create Partner',
        'description' => 'Record new partnership details.',
        'submit' => 'Save partner',
    ],
    'edit' => [
        'title' => 'Edit Partner',
        'description' => 'Update partnership data.',
        'submit' => 'Update partner',
    ],
    'actions' => [
        'back' => 'Back to list',
    ],
    'form' => [
        'name_am' => 'Name (Amharic)',
        'name_en' => 'Name (English)',
        'short_name' => 'Short name',
        'type' => 'Type',
        'country' => 'Country',
        'city' => 'City',
        'website' => 'Website',
        'phone' => 'Phone',
        'email' => 'Email',
        'logo' => 'Logo',
        'current_logo' => 'Current file: :filename',
        'address' => 'Address',
    ],
    'show' => [
        'heading' => 'Partner details',
        'fields' => [
            'type' => 'Type',
            'status' => 'Status',
            'contact' => 'Contact',
            'address' => 'Address',
            'logo' => 'Logo',
        ],
        'contact' => [
            'phone' => 'Phone',
            'email' => 'Email',
            'website' => 'Website',
        ],
    ],
    'types' => [
        'government' => 'Government',
        'ngo' => 'NGO',
        'private' => 'Private',
        'international' => 'International',
        'other' => 'Other',
    ],
    'statuses' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ],
];
