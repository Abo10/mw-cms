<?php

class CFrontWishlist
{
    static $tbl_name = 'std_wishlist';

    static function GetWishlist()
    {
        if (isset($_SESSION['wishlist'])) {
            return $_SESSION['wishlist'];
        } else {
            return [];
        }
    }

    static function AddToWishlist($pr_id)
    {
        if(!isset($_SESSION['wishlist'])) $_SESSION['wishlist'] = [];
        if (isset($_SESSION['wishlist']) && !in_array($pr_id, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'][] = $pr_id;
            if (CUser::GetUserID()) {
                Cmwdb::$db->where('uid', CUser::GetUserID());
                Cmwdb::$db->update(self::$tbl_name, ['wishlist' => json_encode($_SESSION['wishlist'])]);
            }
        }

    }

    static function DeleteFromWishlist($pr_id)
    {
        if (isset($_SESSION['wishlist']) && in_array($pr_id, $_SESSION['wishlist'])) {
            unset($_SESSION['wishlist'][array_keys($_SESSION['wishlist'], $pr_id)[0]]);
            if (CUser::GetUserID()) {
                Cmwdb::$db->where('uid', CUser::GetUserID());
                Cmwdb::$db->update(self::$tbl_name, ['wishlist' => json_encode($_SESSION['wishlist'])]);
            }
        }

    }

    static function InitWishlist()
    {
        if(isset($_SESSION['wishlist']) && $_SESSION['wishlist']) $old_wishlist = $_SESSION['wishlist'];else $old_wishlist = [];
        var_dump($old_wishlist);
        Cmwdb::$db->where('uid', CUser::GetUserID());
        $data = Cmwdb::$db->getValue(self::$tbl_name, 'wishlist');
        if ($data) {
            $_SESSION['wishlist'] = array_unique(array_merge(json_decode($data, true),$old_wishlist));
        } else {
            Cmwdb::$db->insert(self::$tbl_name, ['wishlist' => json_encode([]),'uid'=>CUser::GetUserID()]);
            $_SESSION['wishlist'] = $old_wishlist;
        }

    }
    static function IsInWishlist($product_id){
        if(isset($_SESSION['wishlist']) && in_array($product_id,$_SESSION['wishlist'])){
            return true;
        }
        return false;
    }
}