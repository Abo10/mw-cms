<?php
return array(
    'map' =>
        array(
            'class' => 'CMapLink',
            'front_class' => 'CFrontMap',
            'css' =>
                array(
                    0 => 'style',
                ),
        ),
    'product_category' =>
        array(
            'class' => 'CCategoryProduct',
            'front_class' => 'CFrontProductCategory',
            'css' =>
                array(
                    0 => 'default',
                    1 => 'style',
                ),
            'components' =>
                array(
                    'product_links' => 'CProductToCategoryLinks',
                ),
        ),
    'brand' =>
        array(
            'class' => 'CBrand',
            'front_class' => 'CFrontBrand',
            'css' =>
                array(
                    0 => 'default',
                    1 => 'style',
                ),
            'components' =>
                array(
                    'product_links' => 'CProductToBrandLinks',
                ),
        ),
    'product' =>
        array(
            'class' => 'CProduct',
            'front_class' => 'CFrontProduct',
            'css' =>
                array(
                    0 => 'default',
                    1 => 'style',
                ),
            'components' =>
                array(
                    'currency' => 'CCurrency',
                    'multyprice' => 'CMultyPrice',
                ),
        ),
    'product_attributes' =>
        array(
            'class' => '',
            'css' =>
                array(
                    0 => 'default',
                    1 => 'style',
                ),
            'components' =>
                array(),
        ),
    'attributika' =>
        array(
            'class' => 'CAttributika',
            'front_class' => 'CFrontAttributika',
            'css' =>
                array(
                    0 => 'default',
                    1 => 'style',
                ),
            'components' =>
                array(
                    'attr_links' => 'CAttrLinks',
                ),
        ),
    'tags' =>
        array(
            'class' => 'CTags',
            'front_class' => '',
            'css' =>
                array(
                    0 => 'default',
                    1 => 'style',
                ),
            'components' =>
                array(
                    'product_links' => 'CProductsToTags',
                    'tags_list' => 'CTagsList',
                ),
        ),
    'slider' =>
        array(
            'class' => 'CSlider',
            'front_class' => 'CFrontSlider',
            'css' =>
                array(
                    0 => 'style',
                ),
        ),
    'discount' =>
        array(
            'class' => 'CDiscount',
            'front_class' => 'CFrontDiscount',
            'css' =>
                array(
                    0 => 'style',
                ),
        ),
    'user' =>
        array(
            'class' => 'CUserExt',
            'front_class' => 'CFrontUserExt',
            'css' =>
                array(
                    0 => 'style',
                ),
        ),
    'cart' =>
        array(
            'class' => 'CCart',
            'front_class' => 'CFrontCart',
            'css' =>
                array(
                    0 => 'style',
                ),
        ),
    'addressing' =>
        array(
            'class' => 'CAddressing',
            'front_class' => 'CFrontAddressing',
            'css' =>
                array(
                    0 => 'style',
                ),
        ),
);