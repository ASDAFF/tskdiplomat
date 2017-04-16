<?php
#################################
#   Developer: Lynnik Danil     #
#   Site: http://bxmod.ru       #
#   E-mail: support@bxmod.ru    #
#################################

IncludeModuleLangFile(__FILE__);

class BxmodSeo
{
    /**
    * �������� ������� �� ID
    * 
    * @param mixed $id
    * @return CDBResult
    */
    public function GetByID ( $id ) {
        global $DB;
        
        // ����� ������������ � �������, ���� �� ����� ������� � �������
        $res = $DB->Query("SELECT * FROM `bxmod_seo` WHERE `ID`='". intval( $id ) ."' ORDER BY `SORT` ASC", true);
        
        return $res;
    }
    
    /**
    * �������� ��� �������� ����� ���������� �����
    * 
    * @param mixed $pidId
    * @return CDBResult
    */
    public function GetSubKeys ( $pidId = 0 ) {
        global $DB;
        
        // ����� ������������ � �������, ���� �� ����� ������� � �������
        $res = $DB->Query("SELECT * FROM `bxmod_seo` WHERE `PARENT_ID`='". intval($pidId) ."' ORDER BY `SORT` ASC", true);
        
        return $res;
    }
    
    /**
    * ���������� ������� ������ �� ��������� �� ����������
    * 
    * @param mixed $id
    */
    public function GetChainKeys ( $id ) {
        global $DB;
        
        $result = Array();
        
        // ���������� ���������� ��� ����� �� ��������� �� ���������
        $res = $DB->Query("SELECT * FROM `bxmod_seo` WHERE `ID`='{$id}'", true);
        if ( $arRes = $res->Fetch() ) {
            if ( intval( $arRes["PARENT_ID"] ) > 0 ) {
                $parent = self::GetChainKeys ( $arRes["PARENT_ID"] );
                if ( !empty( $parent ) ) {
                    $result = $parent;
                }
            }
            // � ��������� �� � ������� � �������� �������
            $result[] = $arRes;
        }
        
        return $result;
    }
    
    /**
    * ���������� ������ ��������
    * 
    * @param mixed $data
    */
    public function Add ( $data ) {
        global $DB;
        
        // ��������� ������������ �����
        $data = self::CheckFields( $data );
        
        // ���� ������ ���, �� ������ INSERT
        if ( !isset( $data["error"] ) ) {
            $DB->Query("INSERT INTO `bxmod_seo` (`PARENT_ID`, `ACTIVE`, `KEY`, `SEO_TEXT`, `META_KEYS`, `META_DESC`, `TITLE`, `H1`, `URL`, `SORT`) VALUES (". intval($data["data"]["PARENT_ID"]) .", '". $DB->ForSql($data["data"]["ACTIVE"]) ."', '". $DB->ForSql($data["data"]["KEY"]) ."', '". $DB->ForSql($data["data"]["SEO_TEXT"]) ."', '". $DB->ForSql($data["data"]["META_KEYS"]) ."', '". $DB->ForSql($data["data"]["META_DESC"]) ."', '". $DB->ForSql($data["data"]["TITLE"]) ."', '". $DB->ForSql($data["data"]["H1"]) ."', '". $DB->ForSql($data["data"]["URL"]) ."', ". intval($data["data"]["SORT"]) .")", true);
            return true;
        }
        
        // ���� ���� ������, �� ���������� ��
        return $data["error"];
    }
    
    /**
    * �������������� ��������
    * 
    * @param mixed $id
    * @param mixed $data
    */
    public function Edit ( $id, $data ) {
        global $DB;
        
        // ��������� ������������ �����
        $data = self::CheckFields( $data );
        
        // ���� ������ ���, �� ������ UPDATE
        if ( !isset( $data["error"] ) ) {
            $DB->Query("UPDATE `bxmod_seo` SET
            `PARENT_ID`=". intval($data["data"]["PARENT_ID"]) .",
            `ACTIVE`='". $DB->ForSql($data["data"]["ACTIVE"]) ."',
            `KEY`='". $DB->ForSql($data["data"]["KEY"]) ."',
            `SEO_TEXT`='". $DB->ForSql($data["data"]["SEO_TEXT"]) ."',
            `META_KEYS`='". $DB->ForSql($data["data"]["META_KEYS"]) ."',
            `META_DESC`='". $DB->ForSql($data["data"]["META_DESC"]) ."',
            `TITLE`='". $DB->ForSql($data["data"]["TITLE"]) ."',
            `H1`='". $DB->ForSql($data["data"]["H1"]) ."',
            `URL`='". $DB->ForSql($data["data"]["URL"]) ."',
            `SORT`=". intval($data["data"]["SORT"]) ."
            WHERE `ID`=". intval($id), true);
            return true;
        }
        
        // ���� ���� ������, �� ���������� ��
        return $data["error"];
    }
    
    /**
    * �������� ����� ����� �����������/���������������
    * 
    * @param mixed $data
    */
    public function CheckFields ( $data ) {
        
        $result = Array();
        
        // ������ ���� �����
        $fields = Array(
            "PARENT_ID" => Array(
                "title" => GetMessage("BXMOD_SEO_MODULE_FIELD_1"),
                "type" => "int",
                "require" => false,
                "default" => 0
            ),
            "ACTIVE" => Array(
                "title" => GetMessage("BXMOD_SEO_MODULE_FIELD_2"),
                "maxLength" => 1,
                "require" => false,
                "default" => "Y"
            ),
            "KEY" => Array(
                "title" => GetMessage("BXMOD_SEO_MODULE_FIELD_3"),
                "type" => "str",
                "maxLength" => 255,
                "require" => true,
                "default" => ""
            ),
            "SEO_TEXT" => Array(
                "title" => GetMessage("BXMOD_SEO_MODULE_FIELD_4"),
                "type" => "str",
                "maxLength" => 10000000,
                "require" => false,
                "default" => ""
            ),
            "META_KEYS" => Array(
                "title" => GetMessage("BXMOD_SEO_MODULE_FIELD_5"),
                "type" => "str",
                "maxLength" => 255,
                "require" => false,
                "default" => ""
            ),
            "META_DESC" => Array(
                "title" => GetMessage("BXMOD_SEO_MODULE_FIELD_6"),
                "type" => "str",
                "maxLength" => 255,
                "require" => false,
                "default" => ""
            ),
            "TITLE" => Array(
                "title" => GetMessage("BXMOD_SEO_MODULE_FIELD_7"),
                "type" => "str",
                "maxLength" => 255,
                "require" => false,
                "default" => ""
            ),
            "H1" => Array(
                "title" => GetMessage("BXMOD_SEO_MODULE_FIELD_8"),
                "type" => "str",
                "maxLength" => 255,
                "require" => false,
                "default" => ""
            ),
            "URL" => Array(
                "title" => GetMessage("BXMOD_SEO_MODULE_FIELD_9"),
                "type" => "str",
                "maxLength" => 255,
                "require" => false,
                "default" => ""
            ),
            "SORT" => Array(
                "title" => GetMessage("BXMOD_SEO_MODULE_FIELD_10"),
                "type" => "int",
                "require" => false,
                "default" => ""
            )
        );
        
        // ���������� ��� ����
        foreach ( $fields AS $k => $v ) {
            if ( $v["require"] && !isset( $data[$k] ) ) {
                // ��������� ������� ������������
                $result["error"] = GetMessage("BXMOD_SEO_MODULE_ERROR_REQUIRE") . " [{$v["title"]}]";
                return $result;
            }
            if ( $v["type"] == "int" ) {
                // �������� � ������
                $data[$k] = isset( $data[$k] ) ? intval( $data[$k] ) : $v["default"];
                $result["data"][$k] = $data[$k];
            } elseif ( $v["type"] == "str" ) {
                // �������� � ������
                $data[$k] = isset( $data[$k] ) ? strval( trim($data[$k]) ) : $v["default"];
                // ��������� ������������ �����
                if ( $v["maxLength"] < strlen( $data[$k] ) ) {
                    $result["error"] = GetMessage("BXMOD_SEO_MODULE_ERROR_MAXLENGTH") . " [{$v["title"]}]: {$v["maxLength"]}";
                    return $result;
                }
                $result["data"][$k] = $data[$k];
            }
        }
        
        // ��� ���� ACTIVE ����������� ������
        if ( !isset( $data["ACTIVE"] ) || !$data["ACTIVE"] ) $result["data"]["ACTIVE"] = "N";
        else $result["data"]["ACTIVE"] = "Y";
        
        return $result;
    }
    
    /**
    * ������� ������� � ��������� ID � ���� ��� ��������
    * 
    * @param mixed $id
    */
    public function Delete ( $id ) {
        global $DB;
        
        // ������� ��� �������
        $DB->Query("DELETE FROM `bxmod_seo` WHERE `ID`=". intval( $id ), true);
        
        // � ���������� ���������� �� ���� ��� ��������
        $res = $DB->Query("SELECT * FROM `bxmod_seo` WHERE `PARENT_ID`=". intval( $id ), true);
        while ( $arRes = $res->Fetch() ) {
            self::Delete( $arRes["ID"] );
        }
    }
    
    /**
    * ���� ���� ��� ������������� URL
    * 
    * @param string $url
    * @return array
    */
    public function FindKey ( $url ) {
        global $DB;
        
        // ���� ���� ��� �������� � ����� URL-��
        $res = $DB->Query("SELECT * FROM `bxmod_seo` WHERE `URL`='". $DB->ForSql($url) ."' AND `ACTIVE`='Y' LIMIT 1", true);
        if ( $arRes = $res->Fetch() ) {
            // ��������� ���������� ���� ���������
            foreach ( self::GetChainKeys( $arRes["PARENT_ID"] ) AS $v ) {
                // ���� ���� �� ���� �� ��������� � ������� �� �������� - ���������� false
                if ( $v["ACTIVE"] != "Y" ) return false;
            }
            
            // ��������� ���������� ������� ��� ���������� ��������� �����
            AddEventHandler("main", "OnEpilog", Array("BxmodSeo", "OnAfterEpilogHandler"));
            
            // ���� �����, �� ���������� =)
            return $arRes;
        }
        
        return false;
    }
    
    /**
    * ���� ������ � ��������� ����� �� �������� ����� � ��� �������� �����
    * 
    * @param mixed $id
    */
    public function FindLinks ( $id ) {
        global $DB;
        
        $id = intval( $id );
        
        $result = Array();
        
        // ���� �������� �����
        $arRes = self::GetChainKeys( $id );
        if ( !empty( $arRes ) ) {
            $res = array_shift( $arRes );
            
            if ( $res["ACTIVE"] == "Y" ) {
                $result[$res["ID"]] = $res;
                
                // ���� ������������ �����
                if ( count( $arRes ) > 1 ) {
                    array_pop( $arRes );
                    $res = array_pop( $arRes );
                    $result[$res["ID"]] = $res;
                }
            }
        }
        
        // �������� ��� �������� �����
        $res = $DB->Query("SELECT * FROM `bxmod_seo` WHERE `PARENT_ID`='{$id}' AND `ACTIVE`='Y' ORDER BY `SORT`", true);
        while ( $arRes = $res->Fetch() ) {
            // ���� ������ �� ����������� �� ��� ����
            if ( !isset( $result["ID"] ) ) {
                $result[] = $arRes;
            }
        }
        
        return $result;
    }
    
    /**
    * ����� ����� ��� ���������� ��������� ��������� �������� � ���� �����.
    */
    public function OnAfterEpilogHandler () {
        global $APPLICATION;
        
        // Title
        if ( defined( "BXMOD_SEO_TAG_TITLE" ) ) {
            $APPLICATION->SetPageProperty("title", BXMOD_SEO_TAG_TITLE);
        }
        // Description
        if ( defined( "BXMOD_SEO_TAG_DESCRIPTION" ) ) {
            $APPLICATION->SetPageProperty("description", BXMOD_SEO_TAG_DESCRIPTION);
        }
        // Keywords
        if ( defined( "BXMOD_SEO_TAG_KEYWORDS" ) ) {
            $APPLICATION->SetPageProperty("keywords", BXMOD_SEO_TAG_KEYWORDS);
        }
    }
}
?>