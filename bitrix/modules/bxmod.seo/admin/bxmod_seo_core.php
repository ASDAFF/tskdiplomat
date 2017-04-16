<?
#################################
#   Developer: Lynnik Danil     #
#   Site: http://bxmod.ru       #
#   E-mail: support@bxmod.ru    #
#################################

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

if ( !CModule::IncludeModule("bxmod.seo") ) {
    CAdminMessage::ShowMessage( GetMessage("BXMOD_SEO_CORE_ERROR_MESS") );
}

global $USER, $APPLICATION;

// �������� ���� �������
$groupRight = $APPLICATION->GetGroupRight("bxmod_seo");
if ( $groupRight == "D" ) $APPLICATION->AuthForm( GetMessage("ACCESS_DENIED") );

IncludeModuleLangFile(__FILE__); 

// ���������� JQuery
CJSCore::Init(array("jquery"));
// ���������� JS
$APPLICATION->AddHeadScript('/bitrix/js/bxmod.seo/admin_core.js');

$sTableID = "bxmod_seo";

$oSort = new CAdminSorting($sTableID, "ID", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilterFields = array();

$lAdmin->InitFilter($arFilterFields);

// ������ ������� ���������
$arFilter = array();

// ������� ID ��������, ������ ������� ������
if ( isset($_GET['pid']) ) {
    $arFilter['PARENT_ID'] = intval($_GET['pid']);
}

// ��������� ��������
if ( $arID = $lAdmin->GroupAction() )
{
    foreach ($arID as $ID)
    {
        if (strlen($ID) <= 0) continue;

        // �������� ��������
        if ($_REQUEST['action'] == 'delete')
        {
            BxmodSeo::Delete( $ID );
        }
    }
}

// ��������� ������ ���������� �������.
$lAdmin->AddHeaders(array(
    array("id"=>"ID", "content"=>"ID", "default"=>false),
    array("id"=>"ACTIVE", "content"=>GetMessage("BXMOD_SEO_CORE_HEAD_ACTIVE"), "default"=>true),
    array("id"=>"KEY", "content"=>GetMessage("BXMOD_SEO_CORE_HEAD_KEY"), "default"=>true),
    array("id"=>"SEO_TEXT", "content"=>GetMessage("BXMOD_SEO_CORE_HEAD_SEO_TEXT"), "default"=>true),
    array("id"=>"FIELDS", "content"=>GetMessage("BXMOD_SEO_CORE_HEAD_FIELDS"), "default"=>true),
));

$arVisibleColumns = $lAdmin->GetVisibleHeaderColumns();

// ������� ����
$currentKey = Array();

// ���������� ������
function BxmodSeoFieldsAddRow( &$lAdmin, $arFilter, $depth = 0 ) {
    global $currentKey;
    
    // �������� ������ ���������
    if ( isset( $arFilter['ID'] ) ) {
        $dbResultList = BxmodSeo::GetByID( $arFilter['ID'] );
        if ( !$dbResultList ) {
            LocalRedirect( "bxmod_seo_core.php?lang=" . urlencode(LANGUAGE_ID) );
        }
    } else {
        $pid = isset($arFilter['PARENT_ID']) ? intval( $arFilter['PARENT_ID'] ) : 0;
        $dbResultList = BxmodSeo::GetSubKeys( $pid );
    }
    
    if ( $dbResultList ) {
        // ����������� ������ ��������� � ��������� ������ CAdminResult
        $dbResultList = new CAdminResult($dbResultList, $sTableID);
        // �������������� ������������ ���������
        // $dbResultList->NavStart();
        // �������� ����� ������������� ������� � �������� ������ $lAdmin
        $lAdmin->NavText($dbResultList->GetNavPrint(GetMessage("SMILE_NAV")));
        
        while ($arItem = $dbResultList->GetNext())
        {
            if ( $depth > 0 ) {
                $hiddenCss = 'display: none; overflow: hidden;';
            }
            
            $hiddenCss = strlen( $hiddenCss ) > 0 ? '" style="' . $hiddenCss : '';
            
            $row =& $lAdmin->AddRow($arItem["ID"], $arItem, "bxmod_seo_edit.php?id=".$arItem["ID"]."&lang=".LANG, GetMessage("BXMOD_SEO_CORE_ACT_EDIT") . $hiddenCss . '" data-key-id="bxmodSeoKey' . $arItem["ID"] .'" data-parent-key="bxmodSeoKey' . $arItem["PARENT_ID"]);
            
            // ��������� ����
            if ( isset( $arFilter['ID'] ) ) {
                $currentKey = $arItem;
            }
            
            // ���� � ������
            $collapseButton = '<td style="width: '. (50 + (25 * $depth)) .'px;">';
            $subKeys = BxmodSeo::GetSubKeys( $arItem["ID"] );
            if ( $subKeys->Fetch() ) {
                $collapseButton .= '<a href="#" class="adm-btn bxmodSeoCollapse" title="����������">+</a>';
            }
            $collapseButton .= "</td>";
            
            $row->AddViewField("KEY", '<table><tr>'. $collapseButton .'<td><a href="bxmod_seo_edit.php?id='. $arItem["ID"] .'&lang='. LANG .'"><b>'. $arItem["KEY"] .'</b></a></td></tr></table>');
            
            // ���� � �����������
            $row->AddViewField("ACTIVE", $arItem["ACTIVE"] == "Y" ? GetMessage("BXMOD_SEO_CORE_ACTIVE_YES") : GetMessage("BXMOD_SEO_CORE_ACTIVE_NO"));
            
            // ���� � ������� �������
            $row->AddViewField("SEO_TEXT", '<div style="height: 80px; overflow: hidden; max-width: 400px;">' . strip_tags( htmlspecialchars_decode( $arItem["SEO_TEXT"], ENT_QUOTES ) ) . '</div>');
            
            // ���� � ������
            $row->AddViewField("FIELDS", '<b>URL:</b> '. $arItem["URL"] .'<br><b>Title:</b> '. $arItem["TITLE"] .'<br><b>H1:</b> '. $arItem["H1"] .'<br><b>Keywords</b>: '. $arItem["META_KEYS"] .'<br><b>Description</b>: '. $arItem["META_DESC"]);
            
            // ���������� ���� ��� ������ ��������
            $arActions = Array(
                array("ICON"=>"add", "TEXT"=>GetMessage("BXMOD_SEO_CORE_ACT_ADD_CHILD"), "ACTION"=>$lAdmin->ActionRedirect("bxmod_seo_edit.php?pid=".$arItem["ID"]."&lang=".LANG."&".GetFilterParams("filter_")."")),
                array("ICON"=>"edit", "TEXT"=>GetMessage("BXMOD_SEO_CORE_ACT_EDIT"), "ACTION"=>$lAdmin->ActionRedirect("bxmod_seo_edit.php?id=".$arItem["ID"]."&lang=".LANG."&".GetFilterParams("filter_")."")),
                array("SEPARATOR" => true),
                array("ICON"=>"delete", "TEXT"=>GetMessage("BXMOD_SEO_CORE_ACT_DELETE"), "ACTION"=>"if(confirm('". GetMessage("BXMOD_SEO_CORE_ACT_DEL_CONFIRM") ."')) ".$lAdmin->ActionDoGroup($arItem["ID"], "delete"))
            );
            
            $row->AddActions($arActions);
            
            BxmodSeoFieldsAddRow( $lAdmin, Array("PARENT_ID" => $arItem["ID"]), ($depth + 1));
        }
    }
}

// ��������� ������ � ������� ������
BxmodSeoFieldsAddRow( $lAdmin, Array("PARENT_ID" => 0) );

// ��������� ��������� ��������
$APPLICATION->SetTitle( GetMessage("BXMOD_SEO_CORE_TITLE") );

// ������ ��������� ��������, ������������ ��� ��������
$lAdmin->AddGroupActionTable(
    array(
        "delete" => true,
    )
);

// ������ � ����� ������� (��������, ������� � �.�.)
$aContext = array(
    array(
        "TEXT" => GetMessage("BXMOD_SEO_CORE_BUTTON_ADD_NEW"),
        "LINK" => "bxmod_seo_edit.php?lang=" . LANG,
        "TITLE" => GetMessage("BXMOD_SEO_CORE_BUTTON_ADD_NEW_ALT"),
        "ICON" => "btn_new",
    ),
);

$lAdmin->AddAdminContextMenu($aContext);

// ���������� ��� ��������� �������������� ������� ������ (Ajax � �.�.)
$lAdmin->CheckListMode();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// ������� �������
$lAdmin->DisplayList();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>