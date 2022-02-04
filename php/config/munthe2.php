<?php

return [
    'actions'     => [
        'getstock'     => [
            'wsdl'    => 'https://texb2b.aspect4.com/muv_webservices/services/EA6183RA.EA6183RA',
            'mapping' => [
                'nextterm'     => 'next_week',
                'nexttermquan' => 'next_quantity',
                'quantity'     => 'quantity',
            ],
        ],
        'createorder'  => [
            'wsdl' => 'https://texb2b.aspect4.com/muv_webservices/services/EA6183RA.EA6183RA',
        ],
        'updatestatus' => [
            'wsdl' => 'https://texb2b.aspect4.com/muv_qm/services/SHODELETEDELIVERY?wsdl',
        ],
    ],
    'export'      => [
        'item'    => [
            'wsdl'     => 'https://texb2b.aspect4.com/muv_qm/services/SHOVaremasterITEM?wsdl',
            'function' => 'itemget',
            'mapping'  => [
                'item'           => 'external_id',
                'itembrand'      => 'bran_number',
                'itemstyle'      => 'style',
                'itemquality'    => 'quality',
                'itemtext'       => 'text',
                'brand'          => 'brand',
                'brandtext'      => 'brand_text',
                'itemgroup'      => 'group',
                'itemgrouptext'  => 'group_text',
                'compcode'       => 'comp_code',
                'comptext'       => 'comp_text',
                'image2'         => 'image_2',
                'itemcolor'      => 'item_color',
                'piccolor'       => 'pic_color',
                'picstyle'       => 'pic_style',
                'piccolor2'      => 'item_color_2',
                'piccolor3'      => 'item_color_3',
                'piccolor4'      => 'item_color_4',
                'piccolor5'      => 'item_color_5',
                'piccolor6'      => 'item_color_6',
                'piccolor7'      => 'item_color_7',
                'piccolor8'      => 'item_color_8',
                'piccolor9'      => 'item_color_9',
                'piccolor10'     => 'item_color_10',
                'itemcolortext'  => 'item_color_text',
                'collection'     => 'collection',
                'collectionname' => 'collection_name',
                'new'            => 'new',
                'activ'          => 'active',
            ],
        ],
        'variant' => [
            'wsdl'     => 'https://texb2b.aspect4.com/muv_qm/services/SHOVarianterVARIANT?wsdl',
            'function' => 'getVariant',
            'mapping'  => [
                'variant'     => 'external_id',
                'colourcode'  => 'colour_code',
                'colourtext'  => 'colour_text',
                'sizeno'      => 'size_no',
                'size'        => 'size',
                'pricelevel'  => 'price_level',
                'activcolour' => 'activ_colour',
                'ean'         => 'ean',
                'itemstyle'   => 'item_style',
                'piccolor'    => 'pic_color',
                'itemtext'    => 'item_text',
            ],
        ],
        'price'   => [
            'wsdl'     => 'https://texb2b.aspect4.com/muv_qm/services/SHOPriserPRICES?wsdl',
            'function' => 'getprices',
            'mapping'  => [
                'pricelevel' => 'price_level',
                'pricelist'  => 'price_list',
                'price'      => 'price',
                'currency'   => 'currency',
            ],
        ],
        'text'    => [
            'wsdl'     => 'https://texb2b.aspect4.com/muv_qm/services/SHOLangeTeksterITEMTEXT?wsdl',
            'function' => 'getitemtext',
            'mapping'  => [
                'language' => 'language',
                'text'     => 'text',
            ],
        ],
    ],
    'credentials' => [
        'user'     => 'MUVWEB',
        'password' => 'muv756ab',
    ],
];
