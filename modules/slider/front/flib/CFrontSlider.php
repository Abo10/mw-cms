<?php

class CFrontSlider
{
    static $tbl_name = "sliders";
    static $tbl_sl_name = 'std_sliders';

    static function Initial()
    {
        self::$tbl_name = "sliders";
        self::$tbl_sl_name = "std_sliders";
    }

    static function GetSliderFront($slider, $base_size = "medium", $ext_size = "medium")
    {
        $id = null;
        $slider_name = null;
        if (is_numeric($slider)) {
            $id = $slider;
            Cmwdb::$db->where('slider_id', $id);
            $slider_name = Cmwdb::$db->getValue(self::$tbl_sl_name, 'slider_name');
        } else {
            $slider_name = $slider;
            Cmwdb::$db->where('slider_name', $slider);
            $id = Cmwdb::$db->getValue(self::$tbl_sl_name, 'slider_id');
        }

        Cmwdb::$db->where('s_group', $id);
        Cmwdb::$db->where('s_lang', CLanguage::getInstance()->getCurrentUser());
        $res = Cmwdb::$db->get(self::$tbl_name);
        // 		echo Cmwdb::$db->getLastQuery();
// 				var_dump($id);
        if (empty($res)) return [];
        $base = array();
        $ext = array();
        foreach ($res as $values) {
            $ext[$values['s_order']]['url'] = $values['s_url'];
            if ($values['ext_img']) {
                $at = new CAttach($values['ext_img']);
                $ext[$values['s_order']]['img_url'] = $at->GetURL($ext_size);
            } else $ext[$values['s_order']]['img_url'] = "";
            if ($values['base_img']) {
                $at = new CAttach($values['base_img']);
                $base[$values['s_order']] = $at->GetURL($base_size);;
            } else $base[$values['s_order']] = "";
        }
        return ['base' => $base, 'ext' => $ext];

    }

    static function RenderSlider($slider, $id = 'def_slider', $base_size = "medium", $ext_size = "medium")
    {
        self::Initial();
        $slider_content = self::GetSliderFront($slider, $base_size, $ext_size);
        $slider_config = [
            'effect' => 'random',
            'slices' => 15,
            'boxCols' => 8,
            'boxRows' => 4,
            'animSpeed' => 500,
            'pauseTime' => 5000,
            'startSlide' => 0,
            'directionNav' => true,
            'controlNavThumbs' => true,
            'pauseOnHover' => true,
            'manualAdvance' => false
        ];
        if ($slider_content) {
            echo '<div id="' . $id . '" class="slides nivoSlider">';
            foreach ($slider_content['base'] as $key => $item) {
                echo '<img src="' . $item . '" alt="" title="#slider-direction-' . $key . '">';
            }
            echo ' </div>';
            foreach ($slider_content['ext'] as $key => $item) {
                echo '<div id="slider-direction-' . $key . '" class="t-cn slider-direction">';
                echo '<div class="slider-content t-lfl slider-2">';
                echo '<div class="title-container">';
                echo '<h1 class="title1"><a href="' . $item['url'] . '"><img src="' . $item['img_url'] . '" alt=""></a></h1>';
                echo '</div></div></div>';
            }
            echo '<script>';
            echo '$(function(){
                $("#' . $id . '").nivoSlider(' . json_encode($slider_config, JSON_PRETTY_PRINT) . ');
            })';
            echo '</script>';
        }

    }
    static function RenderSliderByIDs($items,$config=[], $id = 'def_slider', $base_size = "medium", $ext_size = "medium")
    {
        self::Initial();

        $slider_config = [
            'effect' => 'random',
            'slices' => 15,
            'boxCols' => 8,
            'boxRows' => 4,
            'animSpeed' => 500,
            'pauseTime' => 5000,
            'startSlide' => 0,
            'directionNav' => true,
            'controlNavThumbs' => true,
            'pauseOnHover' => true,
            'manualAdvance' => false
        ];
        $slider_config = array_merge($slider_config,$config);
        if ($items) {
            echo '<div id="' . $id . '" class="slides nivoSlider">';
            foreach ($items as $key => $item) {
                echo '<img src="' . $item['original'] . '" alt="" title="#slider-direction-' . $key . '">';
            }
            echo ' </div>';

            echo '<script>';
            echo '$(function(){
                $("#' . $id . '").nivoSlider(' . json_encode($slider_config, JSON_PRETTY_PRINT) . ');
            })';
            echo '</script>';
        }

    }
}

