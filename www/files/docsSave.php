<?php
session_start();

$_classPath = "../phpshop/";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("security");

$PHPShopValutaArray = new PHPShopValutaArray();
$PHPShopSystem = new PHPShopSystem();

class DocSave {

    function DocSave() {
        $this->debug = false;
        $this->tip = $_GET['tip'];
        $this->list = $_GET['list'];
        $this->path = "../1cManager";
    }

    function autorization() {
        if (PHPShopSecurity::true_num($_GET['orderId']) and PHPShopSecurity::true_num($_GET['datas'])) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
            $where['id'] = '=' . $_GET['orderId'];

            if (!empty($_SESSION['UsersId']))
                $where['user'] = '=' . $_SESSION['UsersId'];
            else
                $where['user'] = '=0';

            $data = $PHPShopOrm->select(array('id'), $where, false, array('limit' => 1));

            if (is_array($data))
                return true;
        }
    }

    function select() {
        if ($this->autorization()) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['1c_docs']);
            $where = array('uid' => '=' . $_GET['orderId'], 'datas' => '=' . $_GET['datas']);
            $row = $PHPShopOrm->select(array('*'), $where, false, array('limit' => 1));

            if (!empty($row['datas']))
                $order_t = date("m", $row['datas']) . "-" . date("Y", $row['datas']);

            if (!empty($row['cid'])) {

                $file = $order_t . "/" . $row['cid'];

                switch ($this->tip) {
                    case("doc"):
                        $f_r = ".doc";
                        break;

                    case("xls"):
                        $f_r = ".xls";
                        break;

                    case("html"):
                        $f_r = ".htm";
                        break;

                    case("pdf"):
                        $f_r = ".pdf";
                        break;

                    default:
                        $f_r = ".htm";
                        break;
                }

                switch ($this->list) {
                    case("accounts"):
                        $f_l = "/accounts/";
                        break;

                    case("invoice"):
                        $f_l = "/invoice/";
                        break;

                    default:
                        $f_l = "/accounts/";
                        break;
                }

                // ְהנוס פאיכא
                $this->file = $this->path . $f_l . $file . $f_r;

                // ָלÿ פאיכא
                $this->filename = $row['cid'] . $f_r;
            }
        }
    }

    function compile() {
        if (file_exists($this->file)) {
            if(empty($_GET['check_file'])){
            header("Content-Description: File Transfer");
            header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename=' . $this->filename);
            header("Content-Transfer-Encoding: binary");
            header('Content-Length: ' . filesize($this->file));
            readfile($this->file);
            }else exit($this->filename);
        } else {
            header("Location: /error/");
            exit;
        }
    }

}

$DocSave = new DocSave();
$DocSave->select();
$DocSave->compile();
?>