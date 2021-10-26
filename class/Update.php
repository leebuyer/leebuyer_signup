<?php
// 如「模組目錄」= signup，則「首字大寫模組目錄」= Signup
// 如「資料表名」= actions，則「模組物件」= Actions

namespace XoopsModules\Leebuyer_signup; //此處是給自動功能用的

class Update
{
    // 建立群組
    public static function mk_group($name = "")
    {
        global $xoopsDB;
        $sql = "select groupid from " . $xoopsDB->prefix("groups") . " where `name`='$name'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($groupid) = $xoopsDB->fetchRow($result);
        if (empty($groupid)) {
            $sql = "insert into " . $xoopsDB->prefix("groups") . " (`name`) values('{$name}')";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            //取得最後新增資料的流水編號
            $groupid = $xoopsDB->getInsertId();
        }
        return $groupid;
    }

    // 進行有無候補欄位檢查
    public static function chk_candidate()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`candidate`) FROM ' . $xoopsDB->prefix('leebuyer_signup_actions') . ' ';
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    // 執行新增候補欄位
    public static function go_candidate()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('leebuyer_signup_actions') . " ADD `candidate` tinyint(3) unsigned NOT NULL '後補功能'";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());
    }

}
