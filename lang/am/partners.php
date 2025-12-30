<?php

return [
    'index' => [
        'title' => 'አጋሮች',
        'description' => 'የውጭ አጋርነቶችን ያስተዳድሩ።',
        'filters' => [
            'search' => 'ፈልግ',
            'placeholder' => 'የአጋር ስም ወይም ሀገር',
            'status' => 'ሁኔታ',
            'all' => 'ሁሉም',
        ],
        'table' => [
            'partner' => 'አጋር',
            'type' => 'ዓይነት',
            'location' => 'አድራሻ',
            'contact' => 'ግንኙነት',
            'status' => 'ሁኔታ',
            'actions' => 'ተግባራት',
        ],
        'empty' => 'ምንም አጋር አልተገኘም።',
        'actions' => [
            'delete_confirm' => 'አጋሩ ይወገድ?',
        ],
    ],
    'create' => [
        'title' => 'አዲስ አጋር ፍጠር',
        'description' => 'የአጋርነት ዝርዝሮችን ይመዝግቡ።',
        'submit' => 'አጋሩን መዝግብ',
    ],
    'edit' => [
        'title' => 'አጋርን አርም',
        'description' => 'የአጋርነት መረጃን ያዘምኑ።',
        'submit' => 'አጋሩን አዘምን',
    ],
    'actions' => [
        'back' => 'ወደ ዝርዝር ተመለስ',
    ],
    'form' => [
        'name_am' => 'ስም (በአማርኛ)',
        'name_en' => 'ስም (በእንግሊዝኛ)',
        'short_name' => 'አጭር ስም',
        'type' => 'ዓይነት',
        'country' => 'ሀገር',
        'city' => 'ከተማ',
        'website' => 'ድረ-ገጽ',
        'phone' => 'ስልክ',
        'email' => 'ኢሜይል',
        'logo' => 'ሎጎ (አርማ)',
        'current_logo' => 'ያለው ፋይል: :filename',
        'address' => 'አድራሻ',
    ],
    'show' => [
        'heading' => 'የአጋር ዝርዝር መረጃ',
        'fields' => [
            'type' => 'ዓይነት',
            'status' => 'ሁኔታ',
            'contact' => 'ግንኙነት',
            'address' => 'አድራሻ',
            'logo' => 'ሎጎ (አርማ)',
        ],
        'contact' => [
            'phone' => 'ስልክ',
            'email' => 'ኢሜይል',
            'website' => 'ድረ-ገጽ',
        ],
    ],
    'types' => [
        'government' => 'መንግስታዊ',
        'ngo' => 'መንግስታዊ ያልሆነ ድርጅት (NGO)',
        'private' => 'ግል',
        'international' => 'ዓለም አቀፍ',
        'other' => 'ሌላ',
    ],
    'statuses' => [
        'active' => 'ንቁ',
        'inactive' => 'ንቁ ያልሆነ',
    ],
];